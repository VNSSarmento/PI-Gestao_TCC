<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="/Public/assets/trabalho/css/add.adm.css">
  <title>Adicionar Usuário</title>
</head>
<body>
  <main class="modal_add_content_main">
    <header class="modal_add_header">
      <section class="modal_add_usuario">
        <div class="modal_add_img">
          <img src="/img/pngtree-add-user-icon-in-black-png-image_5016120-removebg-preview.png" alt="Usuário" />
        </div>
        <h1>Adicionar Usuário</h1>
      </section>
      <section class="modal_add_seta" onclick="fecharModalUnico()">
        <img src="/img/seta.png" alt="Voltar" />
      </section>
    </header>

    <form class="modal_add_formulario">
      <section class="modal_add_informacoes">
        <div class="modal_add_campo">
          <label for="nome">Nome</label>
          <input type="text" id="nome" placeholder="Digitar nome do Usuário" />
        </div>

        <div class="modal_add_campo">
          <label for="email">E-mail</label>
          <input type="text" id="nome" placeholder="Digitar nome do Usuário" />
        </div>

        <div class="modal_add_campo">
          <label for="faculdade">Faculdade</label>
          <input type="text" id="faculdade" placeholder="Digitar nome da Faculdade" />
        </div>

        <div class="modal_add_campo">
          <label for="curso">Curso</label>
          <input type="text" id="curso" placeholder="Digitar nome do Curso" />
        </div>

        <div class="modal_add_campo" id="campo_orientador" style="display: none;">
          <label for="orientador">Selecionar Orientador</label>
          <select id="orientador" name="orientador">
            <option value="">Carregando orientadores...</option>
          </select>
        </div>
      </section>

      <section class="modal_add_tipo_grupo">
        <div class="modal_add_coluna">
          <h2>Tipo do Usuário</h2>
          <p>*Selecionar o tipo do usuário</p>

          <div class="modal_add_opcoes_tipo">
            <div class="modal_add_opcao">
              <input type="radio" id="tipo_orientando" name="tipo_usuario" value="orientando" onchange="atualizarCampoOrientador()" />
              <label for="tipo_orientando">Orientando</label>
            </div>

            <div class="modal_add_opcao">
              <input type="radio" id="tipo_orientador" name="tipo_usuario" value="orientador" onchange="atualizarCampoOrientador()" />
              <label for="tipo_orientador">Orientador</label>
            </div>

            <div class="modal_add_opcao">
              <input type="radio" id="tipo_coordenador" name="tipo_usuario" value="coordenador" onchange="atualizarCampoOrientador()" />
              <label for="tipo_coordenador">Coordenador</label>
            </div>
          </div>
        </div>
      </section>

      <button type="submit" class="modal_add_botao">Criar novo Usuário</button>
    </form>
  </main>

  <script src="/js/formulario.js"></script>
</body>
</html>
