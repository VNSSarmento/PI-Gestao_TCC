<?php
require_once __DIR__ . '/../models/clientes.php';
require_once __DIR__ . '/../utils/EmailHelper.php';
require_once __DIR__ . '/../../vendor/autoload.php';


class controller {
    private $user;
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->user = new User($pdo);
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

     public function main_aluno() {
        include __DIR__.'/../views/telas/main_aluno.php';
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
        $colaboradores = [];

        foreach ($alunos as $a) {
            $a['tipo'] = 'aluno';  // marca o tipo manualmente
            $colaboradores[] = $a;
        }

        foreach ($orientadores as $o) {
            $o['tipo'] = 'orientador';  // marca o tipo manualmente
            $colaboradores[] = $o;
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

        $orientadorId = $_SESSION['cliente_id'];

        $alunos = $this->user->buscarAlunosDoOrientador($orientadorId);

        include __DIR__ . '/../views/telas/main_orientador.php';
    }

public function mainAluno() {
       session_start();
    if (!isset($_SESSION['cliente_id'])) {
        header("Location: /?rota=telaloginAluno");
        exit;
    }

    $id = $_SESSION['cliente_id'];

    $stmt = $this->pdo->prepare("SELECT * FROM aluno WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $alunoId = $_SESSION['cliente_id']; // ou onde você guardar o ID do orientador

    $orientador = $this->user->buscarOrientadorDoAluno($alunoId);

    $documentos = $this->user->buscarDocumentosPorAluno($id); //COLOCAR ISSO QUANDO FOR FAZER PARA PROFESSOR

    include __DIR__ . '/../views/telas/main_aluno.php';
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

        if (is_array($resultado) && isset($resultado['status']) && $resultado['status'] === 'ok') {
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
            case 'Usuario_block':
                $erro = "Usuario Bloqueado.";
                break;

            case 'email_eh_prof':
                $erro = "Email invalido.";
                break;

            case 'email_nao_encontrado':
                $erro = "E-mail não encontrado.";
                break;
        }

        include __DIR__ . '/../views/telas/login_aluno.php';
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

public function bloquearUsuarios() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['erro' => 'Método não permitido']);
            return;
        }

        $id = $_POST['id'] ?? null;
        $tipo = $_POST['tipo'] ?? null;
        $status = $_POST['ativo'] ?? null;

        if (!$id || !$tipo || !isset($status)) {
            http_response_code(400);
            echo json_encode(['erro' => 'Dados incompletos.']);
            return;
        }

    $resultado = $this->user->atualizarBloqueioUser($id, $tipo, $status);
    file_put_contents('log_update.txt', $resultado ? 'Update OK' : 'Update falhou');
        if ($resultado) {
            echo json_encode(['sucesso' => true, 'mensagem' => 'Usuário bloqueado com sucesso']);
        } else {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro ao atualizar.']);
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

public function documentosAluno() {

    session_start();
    if ($_SESSION['tipo'] === 'aluno') {
        $idAluno = (int) ($_SESSION['cliente_id'] ?? 0);
    } else {
        $idAluno = (int) ($_GET['id'] ?? 0);
    }

    if (!$idAluno) {
        echo "ID do aluno não encontrado.";
        return;
    }

    $documentos = $this->user->buscarDocumentosPorAluno($idAluno);
    $nomeAluno = $this->user->buscarNomeAluno($idAluno);

    $user = $_SESSION['user'] ?? ['nome' => 'Professor'];

    include 'app/views/telas/particional/documento_aluno.php';
}



public function salvarAnexo() {

    session_start();

    if (!isset($_SESSION['tipo'])) {
        header("Location: login.php");
        exit;
    }

    $tipo = $_SESSION['tipo'];
    error_log("Tipo remetente na sessão: " . $tipo);
    $id_aluno = $tipo === 'aluno' ? $_SESSION['cliente_id'] : ($_POST['aluno'] ?? null);
    $nome_remetente = $_SESSION['cliente_nome'];
    $comentario = $_POST['comentario'] ?? '';
    $prazo_entrega = $tipo === 'aluno' ? null : ($_POST['prazo_entrega'] ?? null);
    $data_envio = date('Y-m-d');

    if (empty($_FILES['documento']['name'])) {
        echo "Nenhum arquivo foi enviado.";
        exit;
    }

    if ($tipo !== 'aluno' && empty($prazo_entrega)) {
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Prazo de entrega é obrigatório para professores.'
    ]);
    exit;
}


    require_once __DIR__ . '/../utils/FileHelper.php';
    $caminho = FileHelper::salvarArquivo($_FILES['documento']);

    $dados = [
        'id_aluno' => $id_aluno,
        'tipo_remetente' => $tipo,
        'nome_remetente' => $nome_remetente,
        'caminho_arquivo' => $caminho,
        'comentario' => $comentario,
        'data_envio' => $data_envio,
        'prazo_entrega' => $prazo_entrega   
    ];

    header('Content-Type: application/json');

        if ($this->user->salvarDocumentoNoBanco($dados)) {
            echo json_encode([
                'sucesso' => true,
                'mensagem' => 'Anexo salvo com sucesso!'
            ]);
        } else {
            echo json_encode([
                'sucesso' => false,
                'mensagem' => 'Erro ao salvar o anexo no banco de dados.'
            ]);
        }
}

  public function modalAnexarDoc() {
    session_start();
    include __DIR__. '/../views/telas/particional/anexar_documento.php';
    }

}
