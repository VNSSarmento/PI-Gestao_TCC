<link rel="stylesheet" href="/Public/assets/trabalho/css/add.adm.css">
<form class="modal_add_formulario" id="formAdicionarUsuario">
  <section class="modal_add_informacoes">
    <div class="modal_add_campo">
      <label for="nome">Nome</label>
      <input type="text" id="nome" name="nome" placeholder="Digite o nome do Usuário" required />
    </div>

    <div class="modal_add_campo">
      <label for="email">E-mail</label>
      <input type="text" id="email" name="email" placeholder="Digitar o E-mail do Usuário" />
    </div>

    <div class="modal_add_campo">
      <label for="faculdade">Faculdade</label>
      <input type="text" id="faculdade" name="faculdade" placeholder="Nome da Faculdade" />
    </div>

    <div class="modal_add_campo">
      <label for="curso">Curso</label>
      <input type="text" id="curso" name="curso" placeholder="Nome do Curso" />
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

<script>
document.getElementById('formAdicionarUsuario').addEventListener('submit', function (e) {
  e.preventDefault();

  const formData = new FormData(this);

  fetch('/?rota=cadastrarUser', {
    method: 'POST',
    body: formData
  })
  .then(res => res.text())
  .then(res => {
    // Verifica se o retorno contém a palavra 'erro' para dar feedback mais útil
    if (res.toLowerCase().includes('erro')) {
      alert('Erro ao cadastrar usuário: ' + res);
    } else {
      alert('Usuário cadastrado com sucesso!');
      fecharModalUnico();
    }
  })
  .catch(err => {
    alert('Erro na requisição: ' + err.message);
    console.error(err);
  });
});
</script>


<script>
function atualizarCampoOrientador() {
  const campoOrientador = document.getElementById('campo_orientador');
  const tipoSelecionado = document.querySelector('input[name="tipo_usuario"]:checked');

  if (!tipoSelecionado) return;

  const tipo = tipoSelecionado.value;

  if (tipo === 'orientando') {
    campoOrientador.style.display = 'block';
    carregarOrientadores(); // ← Carrega do banco só se for "orientando"
  } else {
    campoOrientador.style.display = 'none';
  }
}

function carregarOrientadores() {
  const select = document.getElementById('orientador');
  if (!select) {
    console.error('Select de orientadores não encontrado!');
    return;
  }

select.innerHTML = '<option>Carregando orientadores...</option>';

 fetch('/?rota=listar_orientadores')
    .then(res => {
      if (!res.ok) throw new Error('Erro ao buscar orientadores');
      return res.json();
    })
    .then(orientadores => {
      if (!Array.isArray(orientadores) || orientadores.length === 0) {
        select.innerHTML = '<option value="">Nenhum orientador encontrado</option>';
        return;
      }

      select.innerHTML = '<option value="">Selecione um orientador</option>';
      orientadores.forEach(o => {
        const option = document.createElement('option');
        option.value = o.id;
        option.textContent = o.nome;
        select.appendChild(option);
      });
    })
    .catch(err => {
      console.error('Erro ao carregar orientadores:', err);
      select.innerHTML = '<option value="">Erro ao carregar orientadores</option>';
    });
}

</script>


