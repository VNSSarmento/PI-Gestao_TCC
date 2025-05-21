<?php
class User {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

public function buscarPorId($id) {

    $sql = "SELECT * FROM coordenador WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($resultado) return $resultado;


    $sql = "SELECT * FROM orientador WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($resultado) return $resultado;


    $sql = "SELECT * FROM aluno WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC); // Pode retornar false se não achar
}

public function autenticarProfessor($email, $senha) {

    $sql = "SELECT * FROM coordenador WHERE email = :email";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':email' => $email]);
    $coordenador = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($coordenador) {
        if (isset($coordenador['ativo']) && !$coordenador['ativo']) {
            return ['status' => 'bloqueado'];
        }
        if (password_verify($senha, $coordenador['senha'])) {
            return ['status' => 'ok', 'user' => $coordenador, 'tipo' => 'coordenador'];
        } else {
            return ['status' => 'senha_incorreta'];
        }
    }

    $sql = "SELECT * FROM orientador WHERE email = :email";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':email' => $email]);
    $orientador = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($orientador) {
        if (isset($orientador['ativo']) && !$orientador['ativo']) {
            return ['status' => 'bloqueado']; 
        if (password_verify($senha, $orientador['senha'])) {
            return ['status' => 'ok', 'user' => $orientador, 'tipo' => 'orientador'];
        } else {
            return ['status' => 'senha_incorreta'];
        }
    }


    return ['status' => 'email_nao_encontrado'];
    }
}



public function autenticarAluno($email, $senha) {
    // 1. Verifica se o aluno existe
    $sql = "SELECT * FROM aluno WHERE email = :email";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':email' => $email]);
    $aluno = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($aluno) {

        if (isset($aluno['ativo']) && !$aluno['ativo']) {
            return ['status' => 'Usuario_block'];
        }

        // 3. Verifica a senha
        if (password_verify($senha, $aluno['senha'])) {
            return ['status' => 'ok', 'user' => $aluno];
        } else {
            return ['status' => 'senha_incorreta'];
        }
    }

    // 4. Verifica se o e-mail é de orientador
    $sql = "SELECT * FROM orientador WHERE email = :email";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':email' => $email]);
    $prof = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($prof) {
        return ['status' => 'email_eh_prof'];
    }

    // 5. E-mail não encontrado
    return ['status' => 'email_nao_encontrado'];
}

    public function buscarAlunosDoOrientador($orientadorId) {
        $sql = "SELECT nome FROM aluno WHERE id_orientador = :id_orientador";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_orientador' => $orientadorId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarOrientadorDoAluno($alunoId) {
    $sql = "SELECT o.nome 
            FROM orientador o
            INNER JOIN aluno a ON a.id_orientador = o.id
            WHERE a.id = :alunoId";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':alunoId' => $alunoId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function admCadastroAluno($aluno) {
        $senha = password_hash($aluno[1], PASSWORD_DEFAULT);
        $sql = "INSERT INTO aluno (nome, email, faculdade, curso, id_orientador, senha) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $aluno[] = $senha;
        $stmt->execute($aluno);
    }

    public function admCadastroOrientador($prof) {
        $sql = "INSERT INTO orientador (nome, email, faculdade, curso) VALUES (?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($prof);
    }

    public function buscarOrientadores() {
        $sql = "SELECT id, nome FROM orientador";
        $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarTodosColaboradores() {
    // Pega todos os orientadores
        $sql1 = "SELECT nome, 'Orientador' AS tipo FROM orientador";
        $stmt1 = $this->pdo->query($sql1);
        $orientadores = $stmt1->fetchAll(PDO::FETCH_ASSOC);

    // Pega todos os alunos
        $sql2 = "SELECT nome, 'Orientando' AS tipo FROM aluno";
        $stmt2 = $this->pdo->query($sql2);
        $alunos = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    // Junta os dois arrays
    return array_merge($orientadores, $alunos);
    }

    public function listarAlunosParaBlock() {
        $sql = "SELECT id, nome, ativo, 'aluno' AS tipo FROM aluno";
        $stmt = $this->pdo->query($sql);
        $alunos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $alunos;
    }

    public function listarOrientadoresParaBlock() {
        $sql = "SELECT id, nome, ativo, 'orientador' AS tipo FROM orientador";
        $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function atualizarBloqueioUser($id, $tipo, $status) {
        $tabela = $tipo === 'aluno' ? 'aluno' : 'orientador';

        $sql = "UPDATE $tabela SET ativo = :status WHERE id = :id";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':status', $status, PDO::PARAM_INT);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function atualizarUsuario($id, $tipo, $dados) {
        $sql = "UPDATE $tipo SET nome = :nome, email = :email, faculdade = :faculdade, curso = :curso WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':nome' => $dados['nome'],
            ':email' => $dados['email'],
            ':faculdade' => $dados['faculdade'],
            ':curso' => $dados['curso'],
            ':id' => $id
        ]);
    }

    public function alterarStatus($id, $tipo, $ativo) {
        $sql = "UPDATE $tipo SET ativo = :ativo WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':ativo' => $ativo,
            ':id' => $id
        ]);
    }


}


