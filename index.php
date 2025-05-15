<?php
require_once 'app/config.php';
require_once 'app/controllers/controller.php';

$controller = new controller($pdo);
$rota = $_GET['rota'] ?? 'main'; // padrão

switch ($rota) {
    case 'cadastro':
        $_SERVER['REQUEST_METHOD'] === 'POST' ? $controller->cadastrar() : $controller->mostrarCadastro();  
        break;

    case 'loginOrientador':
        $_SERVER['REQUEST_METHOD'] === 'POST' ? $controller->loginOrientador() : $controller->loginProfessor();
        break;

    case 'loginProfessor':
        $controller->loginProfessor();
        break;

    case 'main_orientador':
        $_SERVER['REQUEST_METHOD'] === 'POST' ? $controller->loginOrientador() : $controller->mainOrientador();
        break;

    case 'logout':
        $controller->logout();
        break;

    case 'main':
        $controller->main();
        break;
    case 'esqueci_senha':
        $controller->esqueciSenha();
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
    default:
        echo "Página não encontrada.";
        break;
}
?>
