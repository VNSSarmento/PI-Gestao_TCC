  function atualizarCampoOrientador() {
    const tipoUsuario = document.querySelector('input[name="tipo_usuario"]:checked');
    const campoOrientador = document.getElementById('campo_orientador');

    if (tipoUsuario && tipoUsuario.value === 'orientando') {
      campoOrientador.style.display = 'flex';
      carregarOrientadores(); // << CHAMAR AQUI!
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

  function ativarEventosDeBloqueio() {
  document.querySelectorAll('.btn-bloquear').forEach(function(botao) {
    botao.addEventListener('click', function () {
      const item = botao.closest('.colaborador_item');
      const id = item.dataset.id;
      const tipo = item.dataset.tipo;
      const estaBloqueado = botao.classList.contains('modal_block_botao_vermelho');
      const novoStatus = estaBloqueado ? 0 : 1;

      // Atualiza visualmente
      if (estaBloqueado) {
        botao.classList.remove('modal_block_botao_vermelho');
        botao.classList.add('botao_verde');
        botao.textContent = 'Desbloquear';
      } else {
        botao.classList.remove('botao_verde');
        botao.classList.add('modal_block_botao_vermelho');
        botao.textContent = 'Bloquear';
      }

      // Envia para o backend
      fetch('/?rota=bloquear_usuarios', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `id=${id}&tipo=${tipo}&ativo=${novoStatus}`
      })
      .then(response => response.json())
      .then(data => {
        if (!data.sucesso) {
          alert("Erro ao atualizar status.");
        }
      })
      .catch(() => {
        alert("Erro na requisição.");
      });
    });
  });
}

function ativarFiltroColaboradores() {
  const input = document.getElementById('campoPesquisa');
  const lista = document.getElementById('listaColaboradores');
  if (!input || !lista) return;

  const todosLi = Array.from(lista.querySelectorAll('.colaborador_item'));

  input.addEventListener('input', function () {
    const termo = input.value.trim().toLowerCase();
    todosLi.forEach(li => {
      const nome = li.querySelector('.nome').textContent.toLowerCase();
      const tipo = li.querySelector('.tipo').textContent.toLowerCase();
      const deveMostrar = nome.includes(termo) || tipo.includes(termo);
      li.style.display = deveMostrar ? 'flex' : 'none';
    });
  });
}



  document.addEventListener('DOMContentLoaded', function () {
    // 1. Filtro de busca
    const input = document.getElementById('campoPesquisa');
    const lista = document.getElementById('listaColaboradores');
    if (input && lista) {
      const todosLi = Array.from(lista.querySelectorAll('.colaborador_item'));

      input.addEventListener('input', function () {
        const termo = input.value.trim().toLowerCase();
        todosLi.forEach(li => {
          const nome = li.querySelector('.nome').textContent.toLowerCase();
          const tipo = li.querySelector('.tipo').textContent.toLowerCase();
          const deveMostrar = nome.includes(termo) || tipo.includes(termo);
          li.style.display = deveMostrar ? 'flex' : 'none';
        });
      });
    }

    // 2. Botões de bloquear/desbloquear
    document.querySelectorAll('.btn-bloquear').forEach(function(botao) {
      botao.addEventListener('click', function () {
        const item = botao.closest('.colaborador_item');
        const id = item.dataset.id;
        const tipo = item.dataset.tipo;
        const estaBloqueado = botao.classList.contains('modal_block_botao_vermelho');
        const novoStatus = estaBloqueado ? 0 : 1;

        // Atualiza visualmente
        if (estaBloqueado) {
          botao.classList.remove('modal_block_botao_vermelho');
          botao.classList.add('botao_verde');
          botao.textContent = 'Desbloquear';
        } else {
          botao.classList.remove('botao_verde');
          botao.classList.add('modal_block_botao_vermelho');
          botao.textContent = 'Bloquear';
        }

        // Envia para o backend
        fetch('/?rota=bloquear_usuarios', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: `id=${id}&tipo=${tipo}&ativo=${novoStatus}`
        })
        .then(response => response.json())
        .then(data => {
          if (!data.sucesso) {
            alert("Erro ao atualizar status.");
          }
        })
        .catch(() => {
          alert("Erro na requisição.");
        });
      });
    });

    // 3. Envio do formulário
    const form = document.getElementById('formAdicionarUsuario');
    if (form) {
      form.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        console.log('Tipo de usuário:', formData.get('tipo_usuario'));

        fetch('/?rota=cadastrarUser', {
          method: 'POST',
          body: formData
        })
        .then(res => res.text())
        .then(res => {
          if (res.toLowerCase().includes('erro')) {
            alert('Erro ao cadastrar usuário: ' + res);
          } else {
            alert('Usuário cadastrado com sucesso!');
            fecharModalUnico(); // Certifique-se de que essa função está declarada
          }
        })
        .catch(err => {
          alert('Erro na requisição: ' + err.message);
          console.error(err);
        });
      });
    }
  });