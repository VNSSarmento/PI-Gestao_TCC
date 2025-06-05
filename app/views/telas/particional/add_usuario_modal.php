<link rel="stylesheet" href="/Public/assets/trabalho/css/add.adm.css">
<form class="modal_add_formulario" id="formAdicionarUsuario">
  <section class="modal_add_informacoes">
    <div class="modal_add_campo">
      <label for="nome">Nome</label>
      <input type="text" id="nome" name="nome" placeholder="Digite o nome do Usuário" required />
    </div>

    <div class="modal_add_campo">
      <label for="email">E-mail</label>
      <input type="email" id="email" name="email" placeholder="Digitar o E-mail do Usuário" required />
    </div>

    <div class="modal_add_campo">
      <label for="faculdade">Faculdade</label>
      <input type="text" id="faculdade" name="faculdade" placeholder="Nome da Faculdade" required/>
    </div>

    <div class="modal_add_campo">
      <label for="curso">Curso</label>
      <input type="text" id="curso" name="curso" placeholder="Nome do Curso" required/>
    </div>

    <div class="modal_add_campo" id="campo_orientador" style="display: none;">
      <label for="orientador">Selecionar Orientador</label>
      <select id="orientador" name="orientador_id">
        <option value="">Orientadores</option>
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


