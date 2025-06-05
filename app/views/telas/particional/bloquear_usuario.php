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
               <input type="text" id="campoPesquisa" placeholder="Digite o nome do usuário">
      
        </section>

        <section class="modal_block_lista_wrapper" aria-label="Lista de usuarios">
            

        
            <ul class="colaborador_lista" id="listaColaboradores" >
                 <?php foreach ($colaboradores as $colab): ?>

                    
                    <li class="colaborador_item" data-id="<?= $colab['id'] ?>" data-tipo="<?= $colab['tipo'] ?>">
                        <div class="modal_block_usuario_info">
                            <img src="/img/pngtree-user-vector-avatar-png-image_1541962-removebg-preview.png" alt="">
                            <span class="nome"><?= htmlspecialchars($colab['nome']) ?></span>
                            <span class="tipo"><?= htmlspecialchars($colab['tipo'] === 'Orientando' ? 'Aluno' : ucfirst($colab['tipo'])) ?></span>
                        </div>
                        <div class="modal_block_acoes">
                            <button class="btn-bloquear <?= $colab['ativo'] ? 'modal_block_botao_vermelho' : 'botao_verde' ?>" type="button">
                            <?= $colab['ativo'] ? 'Bloquear' : 'Desbloquear' ?>
                            </button>
                            <button class="modal_block_botao_azul" type="button">Alterar</button>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    </main>


<script>
  
document.addEventListener('DOMContentLoaded', function () {
  const input = document.getElementById('campoPesquisa');
  const lista = document.getElementById('listaColaboradores');
  const todosLi = Array.from(lista.querySelectorAll('li'));

  input.addEventListener('input', function () {
    const termo = input.value.trim().toLowerCase();

    todosLi.forEach(li => {
      const nome = li.querySelector('.nome').textContent.toLowerCase();
      const tipo = li.querySelector('.tipo').textContent.toLowerCase();

      const deveMostrar = nome.includes(termo) || tipo.includes(termo);
      li.style.display = deveMostrar ? 'flex' : 'none';
    });
  });
});
</script>

</body>
</html>