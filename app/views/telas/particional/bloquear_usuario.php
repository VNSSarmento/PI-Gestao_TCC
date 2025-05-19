<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Public/assets/trabalho/css/bloquear.css">
    <title>Document</title>
</head>
<body>

    <main class="modal_block_main">
        <header class="modal_block_header">
            <section class="modal_block_usuario_title">
                <div class="modal_block_img">
                    <img src="/img/pngtree-add-user-icon-in-black-png-image_5016120-removebg-preview.png" alt="">
                </div>
                <h1>Bloquear Usuário</h1>
            </section>
            <section class="modal_block_seta" onclick="fecharModalUnico()">
                <img src="/img/seta.png" alt="Voltar">
            </section>
        </header>

        <section class="modal_block_pesquisa">
                <div class="modal_block_lupa">
                    <img src="/img/lupa.png" alt="">
                </div>
                <input type="text" placeholder="Digite o nome do usuário">
        </section>

        <section class="modal_block_lista_wrapper" aria-label="Lista de usuarios">
            <ul class="modal_block_lista">
                <?php foreach ($usuarios as $usuario): ?>
                    <li class="modal_block_usuario" data-id="<?= $usuario['id'] ?>" data-tipo="<?= $usuario['tipo'] ?>">
                        <div class="modal_block_usuario_info">
                            <img src="/img/pngtree-user-vector-avatar-png-image_1541962-removebg-preview.png" alt="">
                            <div>
                                <p class="modal_block_nome"><?= htmlspecialchars($usuario['nome']) ?></p>
                                <p class="colaborador_lista"><?= ucfirst($usuario['tipo']) ?></p> <!-- AQUI MOSTRA O TIPO -->
                            </div>
                        </div>
                        <div class="modal_block_acoes">
                            <button class="modal_block_botao_azul"
                                type="button"
                                onclick="toggleStatusUsuario(<?= $usuario['id'] ?>, <?= $usuario['ativo'] ? 'true' : 'false' ?>, '<?= $usuario['tipo'] ?>')">
                                <?= $usuario['ativo'] ? 'Bloquear' : 'Desbloquear' ?>
                            </button>
                            <button class="modal_block_botao_vermelho"
                                type="button"
                                onclick="abrirEdicaoUsuario(<?= $usuario['id'] ?>, '<?= $usuario['tipo'] ?>')">
                                Alterar
                            </button>
                        </div>
                    </li>
                <?php endforeach; ?>

            </ul>

        </section>
    </main>
<script>
// === BLOQUEAR/DESBLOQUEAR USUÁRIO VIA AJAX ===
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.modal_block_botao_azul').forEach(button => {
    button.addEventListener('click', () => {
      const li = button.closest('.modal_block_usuario');
      const userId = li.dataset.id;
      const tipoUsuario = li.dataset.tipo;
      const novoStatus = button.textContent.trim() === 'Bloquear' ? 0 : 1;

      fetch('/?rota=alterarStatusUsuario', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `usuario_id=${userId}&tipo_usuario=${tipoUsuario}&novo_status=${novoStatus}`
      })
      .then(res => res.text())
      .then(res => {
        if (res.trim() === 'ok') {
          button.textContent = novoStatus === 0 ? 'Desbloquear' : 'Bloquear';
          alert('Status atualizado com sucesso!');
        } else {
          alert('Erro ao atualizar status: ' + res);
        }
      })
      .catch(err => alert('Erro AJAX: ' + err));
    });
  });
});
</script>

</body>
</html>