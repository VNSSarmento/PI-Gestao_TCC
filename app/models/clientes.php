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

    if ($coordenador && password_verify($senha, $coordenador['senha'])) {
        return ['status' => 'ok', 'user' => $coordenador, 'tipo' => 'coordenador'];
    }

    $sql = "SELECT * FROM orientador WHERE email = :email";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':email' => $email]);
    $orientador = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($orientador && password_verify($senha, $orientador['senha'])) {
        return ['status' => 'ok', 'user' => $orientador, 'tipo' => 'orientador'];
    }

    return ['status' => 'erro'];
}


    public function autenticarAluno($email, $senha) {

        $sql = "SELECT * FROM aluno WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        $aluno = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($aluno) {
            if (password_verify($senha, $aluno['senha'])) {
                return ['status' => 'ok', 'user' => $aluno];
            } else {
                return ['status' => 'senha_incorreta'];
            }
        }

        $sql = "SELECT * FROM orientador WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        $prof = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($prof) {
            return ['status' => 'email_eh_prof'];
        }

        // 3. E-mail não encontrado
        return ['status' => 'email_nao_encontrado'];
    }

    public function buscarAlunosDoOrientador($orientadorId) {
        $sql = "SELECT nome FROM aluno WHERE id_orientador = :id_orientador";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_orientador' => $orientadorId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function admCadastroAluno($aluno) {
        $sql = "INSERT INTO aluno (nome, email, faculdade, curso, id_orientador) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($aluno);
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



}


