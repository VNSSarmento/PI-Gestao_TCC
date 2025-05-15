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
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

    public function autenticarProfessor($email, $senha) {
        // 1. Verificar se o e-mail existe na tabela orientador
        $sql = "SELECT * FROM orientador WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        $professor = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($professor) {
            if (password_verify($senha, $professor['senha'])) {
                return ['status' => 'ok', 'user' => $professor];
            } else {
                return ['status' => 'senha_incorreta'];
            }
        }

        // 2. Verificar se esse e-mail está cadastrado como aluno
        $sql = "SELECT * FROM aluno WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        $aluno = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($aluno) {
            return ['status' => 'email_eh_aluno'];
        }

        // 3. E-mail não encontrado
        return ['status' => 'email_nao_encontrado'];
    }
}
