

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="/Public/assets/trabalho/css/tela_inicial_coordenador.css" />
  <title>Painel do Coordenador</title>
</head>
<body>

    <header>
      <section class="perfil">
        <div class="foto_perfil">
          <img src="/Public/assets/uploadsADM/fotos/<?php echo htmlspecialchars($user['id_upload'] ?? 'default.png'); ?>" alt="Foto de perfil">
        </div>
        <div class="perfil_info"> 
          <h2><?php echo htmlspecialchars($user['nome']); ?></h2>
          <p>ADS</p>
        </div>
      </section>
      
      <section class="link">
        <nav>
          <ul>
            <li><button onclick="">Meu Perfil</button></li>
            <li><button onclick="">Etapas do TCC</button></li>
            <a href="/?rota=logout"><li><button class="Blogout" onclick="">Fazer logout</button></li></a> 
          </ul>
        </nav>
      </section>
    </header>
        <div class="content-wrapper">
         <aside class="barra_lateral">
                <div class="pesquisa">
                  <input type="text" id="campoPesquisa" placeholder="Digite o nome do colaborador">
                </div>
                <h3>Colaboradores</h3>
                <ul class="colaborador_lista" id="listaColaboradores">
                  <?php foreach ($colaboradores as $colab): ?>
                  <li>
                    <span class="nome"><?= htmlspecialchars($colab['nome']) ?></span>
                    <span class="tipo"><?= htmlspecialchars($colab['tipo'] === 'Orientando' ? 'Aluno' : ucfirst($colab['tipo'])) ?></span>
                  </li>
                <?php endforeach; ?>
                </ul>
      </aside>

      <main class="content">
        <div class="card_container">
          <div class="card_acao">
            <div class="icone_acao">
              <img src="/Public/assets/trabalho/imagens/usuarios-com-perfil-de-grupo.png" alt="Adicionar usuário">
            </div>
            <button class="botao_acao" onclick="abrirModalComConteudo('/?rota=modal_add_usuario')">Adicionar Usuário</button>
          </div>
          <div class="card_acao">
            <div class="icone_acao">
              <img src="/Public/assets/trabalho/imagens/funcao-do-usuario.png" alt="Bloquear usuário">
            </div>
           <button class="botao_acao" onclick="abrirModalComConteudo('/?rota=modal_block_usuario')">Configurar Usuário</button>
          </div>
        </div>
      </main>
    </div>

    <!-- Modal Único para conteúdo dinâmico -->
    <div id="modalUnico" class="modal">
      <div id="modalContent" class="modal-content">
        
        <!-- Conteúdo será carregado dinamicamente aqui -->
      </div>
    </div>

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
    <script src="/Public/assets/trabalho/js/card.js"></script>
    <script src="/Public/assets/trabalho/js/formulario.js"></script>
</body>
</html>

