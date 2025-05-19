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

    public function telaCoordenador(){
        include __DIR__.'/../views/telas/tela_inicial_coordenador.php';
    }

    public function main() {
        include __DIR__.'/../views/telas/main.php';
    }

    public function telaloginAluno() {
        include __DIR__.'/../views/telas/login_aluno.php';
    }
     public function telaloginProfessor() {
        include __DIR__.'/../views/telas/login_professor.php';
    }

    public function mostrarCadastro() {
        include __DIR__ . '/../views/cadastro.php';
    }


    public function esqueciSenha() {
        include __DIR__ . '/../views/telas/recuperar_senha.php';
    }

    public function cadastrarUser(){
        include __DIR__.'/../views/telas/add.adm.php';
    }

    public function modalAddUsuario() {
        include __DIR__. '/../views/telas/particional/add_usuario_modal.php';
    }

    public function modalBlockUsuario() {
        // 1. Carregar todos os usuários (alunos e orientadores, por exemplo)
        $alunos = $this->user->listarAlunosParaBlock(); // você precisa criar esse método se não existir
        $orientadores = $this->user->listarOrientadoresParaBlock(); // idem

        // 2. Juntar todos em um só array e marcar o tipo
        $usuarios = [];

        foreach ($alunos as $a) {
            $a['tipo'] = 'aluno';  // marca o tipo manualmente
            $usuarios[] = $a;
        }

        foreach ($orientadores as $o) {
            $o['tipo'] = 'orientador';  // marca o tipo manualmente
            $usuarios[] = $o;
        }

        // 3. Carregar a view passando a variável corretamente
    require 'app/views/telas/particional/bloquear_usuario.php';
}




    public function mainCoordenador() {
    session_start();
    if (!isset($_SESSION['cliente_id'])) {
        header("Location: /?rota=telaloginProfessor");
        exit;
    }

    $user = $this->user->buscarPorId($_SESSION['cliente_id']);
    $colaboradores = $this->user->buscarTodosColaboradores();

    include __DIR__ . '/../views/telas/tela_inicial_coordenador.php';
    }
    
    
    public function mainOrientador() {
        session_start();
        if (!isset($_SESSION['cliente_id'])) {
            header("Location: /?rota=telaloginProfessor");
            exit;
        }

        $id = $_SESSION['cliente_id'];
        $stmt = $this->pdo->prepare("SELECT * FROM orientador WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $orientadorId = $_SESSION['cliente_id']; // ou onde você guardar o ID do orientador

        $alunos = $this->user->buscarAlunosDoOrientador($orientadorId);

        include __DIR__ . '/../views/telas/main_orientador.php';
    }

    public function logout() {
    session_start();
    session_destroy();
    header("Location: /?rota=main");
    exit;
}

 public function loginOrientador() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';

        $resultado = $this->user->autenticarProfessor($email, $senha);

   if ($resultado['status'] === 'ok') {
            session_start();
            $_SESSION['cliente_id'] = $resultado['user']['id'];
            $_SESSION['cliente_nome'] = $resultado['user']['nome'];
            $_SESSION['tipo'] = $resultado['tipo'];

            if ($resultado['tipo'] === 'coordenador') {
                $user = $this->user->buscarPorId($_SESSION['cliente_id']);
                header("Location: /?rota=tela_inicial_coordenador");
            } else {
                header("Location: /?rota=main_orientador");
            }
            exit;
        } else {
            $erro = "Email ou senha incorretos.";
        }

        include __DIR__ . '/../views/telas/login_professor.php';
    } else {
        include __DIR__ . '/../views/telas/login_professor.php';
    }
}

public function loginAluno() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';

        $resultado = $this->user->autenticarAluno($email, $senha); //isso aqui tem que vir do model

        switch ($resultado['status']) {
            case 'ok':
                session_start();
                if (!in_array($email, $_SESSION['historico_emails'] ?? [])) {
                    $_SESSION['historico_emails'][] = $email;
                            }
                $_SESSION['cliente_id'] = $resultado['user']['id'];
                $_SESSION['cliente_nome'] = $resultado['user']['nome'];
                $_SESSION['tipo'] = 'aluno';
                header("Location: /?rota=main_aluno");
                exit;

            case 'senha_incorreta':
                $erro = "Senha incorreta.";
                break;

            case 'email_eh_prof':
                $erro = "Email invalido.";
                break;

            case 'email_nao_encontrado':
                $erro = "E-mail não encontrado.";
                break;
        }

        include __DIR__ . '/../views/telas/login_Aluno.php';
    } else {
        include __DIR__ . '/../views/telas/login_aluno.php';
        }
    }

public function admCadastrarUser() {
    $tipo = $_POST['tipo_usuario'] ?? '';
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $faculdade = $_POST['faculdade'] ?? '';
    $curso = $_POST['curso'] ?? '';

        if ($tipo === 'orientando') {
            $orientador_id = $_POST['orientador_id'] ?? null;
                if (empty($orientador_id)) {
                    echo 'Erro: orientador não selecionado.';
                    return;
            }

        $aluno = [$nome, $email, $faculdade, $curso, $orientador_id];
        $this->user->admCadastroAluno($aluno);
        echo 'Aluno cadastrado com sucesso!';
    } 
    else if ($tipo === 'orientador') {
        $prof = [$nome, $email, $faculdade, $curso];
        $this->user->admCadastroOrientador($prof); // corrigido
        echo 'Orientador cadastrado com sucesso!';
    } 
    else {
        echo 'Tipo de usuário não suportado para cadastro.';
    }
}


    public function listarOrientadores() {
    header('Content-Type: application/json');
        $orientadores = $this->user->buscarOrientadores(); // Chama o modelo
    echo json_encode($orientadores);
}

public function atualizarUsuario() {
    $id = $_POST['id'];
    $tipo = $_POST['tipo'];

    $dados = [
        'nome' => $_POST['nome'],
        'email' => $_POST['email'],
        'faculdade' => $_POST['faculdade'],
        'curso' => $_POST['curso']
    ];

    $this->user->atualizarUsuario($id, $tipo, $dados);
    echo 'Atualizado com sucesso!';
}

public function alterarStatusUsuario() {
    $id = $_POST['id'];
    $tipo = $_POST['tipo']; 
    $novoStatus = $_POST['ativo'] ? 0 : 1;

    $this->user->alterarStatus($id, $tipo, $novoStatus);
    echo 'Status atualizado.';
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
