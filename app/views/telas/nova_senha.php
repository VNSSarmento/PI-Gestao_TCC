<form method="POST" action="/?rota=atualizar_senha">
    <h2>Nova senha</h2>
    <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
    <input type="hidden" name="tipo" value="<?php echo htmlspecialchars($_GET['tipo']); ?>">
    <label for="nova_senha">Nova senha:</label>
    <input type="password" name="nova_senha" required>
    <button type="submit">Atualizar senha</button>
</form>
