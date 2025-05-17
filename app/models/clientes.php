<?php
class User {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function buscarPorId($id) {
    $sql = "SELECT * FROM orientador WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($resultado) {
        return $resultado;
    }

    $sql = "SELECT * FROM aluno WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/*     public function autenticarCoordenador($email, $senha){
        $sql = "SELECT * FROM coordenador WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        $coordenador = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$coordenador) {
            return ['status' => 'email_nao_encontrado'];
        }

        if (!password_verify($senha, $coordenador['senha'])) {
            return ['status' => 'senha_incorreta'];
        }

        return ['status' => 'ok', 'user' => $coordenador];
} */


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

}


