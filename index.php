<?php
require_once 'app/config.php';
require_once 'app/controllers/controller.php';

$controller = new controller($pdo);
$rota = $_GET['rota'] ?? 'main'; // padrão

switch ($rota) {
    case 'loginOrientador':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->loginOrientador();
        }else {
            $controller->telaloginProfessor();
     }
        break;
    case 'loginProfessor':
        $controller->telaloginProfessor();
        break;
    case 'main_orientador':
        $_SERVER['REQUEST_METHOD'] === 'POST' ? $controller->loginOrientador() : $controller->mainOrientador();
        break;
    case 'main_coordenador':
        $_SERVER['REQUEST_METHOD'] === 'POST' ? $controller->loginOrientador() : $controller->mainCoordenador();
        break;
    case 'tela_inicial_coordenador':
        $controller->mainCoordenador();
        break;  
    case 'telaloginAluno':
        $controller->telaloginAluno();
        break;
    case 'main_aluno':
        $_SERVER['REQUEST_METHOD'] === 'POST' ? $controller->loginAluno() : $controller->mainAluno();
        break;
    case 'modal_add_usuario':
        $controller->modalAddUsuario();
        break;
    case 'modal_anexar_doc':
        $controller->modalAnexarDoc();
        break;
    case 'modal_block_usuario':
        $controller->modalBlockUsuario();
        break;
    case 'logout':
        $controller->logout();
        break;
    case 'cadastrarUser':
        $controller->admCadastrarUser();
        break;
    case 'main':
        $controller->main();
        break;
    case 'esqueci_senha':
        $controller->esqueciSenha();
       break;
    case 'listar_orientadores':
        $controller->listarOrientadores();
        break;
    case 'bloquear_usuarios':
        if (isset($_GET['rota']) && $_GET['rota'] === 'bloquear_usuarios') {
        $controller->bloquearUsuarios();
        exit;  
        }
        break;
    case 'enviar_link_recuperacao':
        $controller->enviarLinkRecuperacao();
        break;
    case 'nova_senha':
        $controller->formNovaSenha();
        break;
    case 'atualizar_senha':
        $controller->atualizarSenha();
        break;
    case 'salvar_anexo':
        $controller->salvarAnexo();
        break;
    case 'documentos_aluno':
        $controller->documentosAluno();
        break;
    case 'etapas_tcc':
        $controller->etapasTcc();
        break;  
    default:
        echo "Página não encontrada.";
        break;
}
?>
