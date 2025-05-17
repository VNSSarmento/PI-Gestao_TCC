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
          <img src="/Public/assets/uploadsADM/<?php echo htmlspecialchars($user['foto'] ?? 'default.png'); ?>" alt="Foto de perfil">
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
            <li><button onclick="">Chat</button></li>
            <a href="/?rota=logout"><li><button onclick="">Fazer LogOut</button></li></a>
          </ul>
        </nav>
      </section>
    </header>

    <div class="content-wrapper">
      <aside class="barra_lateral">
        <div class="pesquisa">
          <input type="text" placeholder="Digite o nome do aluno">
        </div>
        <h3>Colaboradores</h3>
        <ul class="colaborador_lista">
          <li><span class="nome">Renan Machado</span> <span class="tipo">Orientando</span></li>
          <li><span class="nome">Paula Lima</span> <span class="tipo">Orientador</span></li>
          <li><span class="nome">Carlos Alberto</span> <span class="tipo">Orientando</span></li>
          <li><span class="nome">Fernanda Souza</span> <span class="tipo">Orientador</span></li>
        </ul>
      </aside>

      <main class="content">
        <div class="card_container">
          <div class="card_acao">
            <div class="icone_acao">
              <img src="/img/pngtree-add-user-icon-in-black-png-image_5016120-removebg-preview.png" alt="Adicionar usuário">
            </div>
            <button class="botao_acao" onclick="abrirModalComConteudo('/Views/telas/add.adm.php')">Adicionar Usuário</button>
          </div>

          <div class="card_acao">
            <div class="icone_acao">
              <img src="/img/pngtree-user-vector-avatar-png-image_1541962-removebg-preview.png" alt="Bloquear usuário">
            </div>
            <button class="botao_acao" onclick="abrirModalComConteudo('/Views/bloquear.html')">Bloquear Usuário</button>
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


    <script src="/js/card.js"></script>
    <script src="/js/formulario.js"></script>


</body>
</html>
