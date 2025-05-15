<?php
require_once __DIR__ . '/../models/clientes.php';
require_once __DIR__ . '/../utils/EmailHelper.php';
require_once __DIR__ . '/../../vendor/autoload.php';


class controller {
    private $user;
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->user = new user($pdo);
    }

    public function main() {
        include __DIR__.'/../views/telas/main.php';
    }

    public function esqueciSenha() {
        include __DIR__ . '/../views/telas/recuperar_senha.php';
    }
    public function mainOrientador() {
        session_start();
        if (!isset($_SESSION['cliente_id'])) {
            header("Location: /?rota=loginProfessor");
            exit;
        }

        $id = $_SESSION['cliente_id'];
        $stmt = $this->pdo->prepare("SELECT * FROM orientador WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        include __DIR__.'/../views/telas/main_orientador.php';
    }

    public function mostrarLogin() {
        include __DIR__.'/../views/login.php';
    }

    public function logout() {
    session_start();
    session_destroy();
    header("Location: /?rota=loginProfessor");
    exit;
}

    public function loginAluno() {
        include __DIR__.'/../views/telas/login_aluno.php';
    }
     public function loginProfessor() {
        include __DIR__.'/../views/telas/login_professor.php';
    }

    public function mostrarCadastro() {
        include __DIR__ . '/../views/cadastro.php';
    }

 public function loginOrientador() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';

        $resultado = $this->user->autenticarProfessor($email, $senha);

        switch ($resultado['status']) {
            case 'ok':
                session_start();
                if (!in_array($email, $_SESSION['historico_emails'] ?? [])) {
                    $_SESSION['historico_emails'][] = $email;
                            }
                $_SESSION['cliente_id'] = $resultado['user']['id'];
                $_SESSION['cliente_nome'] = $resultado['user']['nome'];
                $_SESSION['tipo'] = 'professor';
                header("Location: /?rota=main_orientador");
                exit;

            case 'senha_incorreta':
                $erro = "Senha incorreta.";
                break;

            case 'email_eh_aluno':
                $erro = "Este e-mail está cadastrado como aluno, não como professor.";
                break;

            case 'email_nao_encontrado':
                $erro = "E-mail não encontrado.";
                break;
        }

        include __DIR__ . '/../views/telas/login_professor.php';
    } else {
        include __DIR__ . '/../views/telas/login_professor.php';
        }
    }

public function enviarLinkRecuperacao() {
    $email = $_POST['email'] ?? '';

    $stmt = $this->pdo->prepare("SELECT id, nome FROM orientador WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $tipo = 'orientador';

    if (!$user) {
        $stmt = $this->pdo->prepare("SELECT id, nome FROM aluno WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $tipo = 'aluno';
    }

    if ($user) {
        $token = bin2hex(random_bytes(32));
        $expira = date('Y-m-d H:i:s', strtotime('+1 hour'));

        if ($tipo === 'orientador') {
            $this->pdo->prepare("UPDATE orientador SET token_recuperacao = ?, token_expira = ? WHERE id = ?")
                      ->execute([$token, $expira, $user['id']]);
        } else {
            $this->pdo->prepare("UPDATE aluno SET token_recuperacao = ?, token_expira = ? WHERE id = ?")
                      ->execute([$token, $expira, $user['id']]);
        }

        if (EmailHelper::enviarEmailRedefinicao($email, $token, $user['nome'])) {
            echo "Um link foi enviado para seu e-mail.";
        } else {
            echo "Erro ao enviar o e-mail.";
        }

    } else {
        echo "E-mail não encontrado.";
    }
}



    public function formNovaSenha() {
        $token = $_GET['token'] ?? '';
        $tipo = $_GET['tipo'] ?? '';

        if ($tipo === 'orientador') {
                $stmt = $this->pdo->prepare("SELECT * FROM orientador WHERE token_recuperacao = :token AND token_expira > NOW()");
            } else if ($tipo === 'aluno') {
                $stmt = $this->pdo->prepare("SELECT * FROM aluno WHERE token_recuperacao = :token AND token_expira > NOW()");
            } else {
                echo "Tipo inválido.";
                exit;
            }

            $stmt->execute(['token' => $token]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
                include __DIR__ . '/../views/telas/nova_senha.php';
            } else {
                echo "Link inválido ou expirado.";
            }
    }

    public function atualizarSenha() {
            $token = $_POST['token'] ?? '';
            $tipo = $_POST['tipo'] ?? '';
            $novaSenha = password_hash($_POST['nova_senha'], PASSWORD_DEFAULT);

        if ($tipo === 'orientador') {
                $stmt = $this->pdo->prepare("UPDATE orientador SET senha = ?, token_recuperacao = NULL, token_expira = NULL WHERE token_recuperacao = ?");
        } else if ($tipo === 'aluno') {
                $stmt = $this->pdo->prepare("UPDATE aluno SET senha = ?, token_recuperacao = NULL, token_expira = NULL WHERE token_recuperacao = ?");
        } else {
                echo "Tipo inválido.";
                exit;
        }

            $stmt->execute([$novaSenha, $token]);

            echo "Senha atualizada com sucesso. <a href='/?rota=loginProfessor'>Fazer login</a>";
    }


}