<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Página de Acesso</title>
        <link rel="stylesheet" href="/Public/assets/trabalho/css/login_aluno.css">
    </head>
    <body>
        <div class="login-container">
            <div class="login-box">
                <h1>Login do aluno</h1>
                <form action="/?rota=loginAluno" method="POST">
                    <div class="input-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    </div>
                    <div class="input-group">
                        <label for="password">Senha</label>
                        <input type="password" id="password" name="password" required>
                         <?php if (!empty($erro)): ?>
                               <p style="color: red;"><?php echo $erro; ?></p>
                           <?php endif; ?>
                    </div>
                    <button type="submit" class="login-btn">Fazer login</button>
                    <a href="/app/views/telas/recuperar_senha_aluno.php" class="forgot-password">Esqueceu a senha?</a>
                    <a href="/app/views/telas/main.php" class="backmain">
                        Voltar para Inicio
                    </a>
                </form>
            </div>
        </div>
    </body>
</html>