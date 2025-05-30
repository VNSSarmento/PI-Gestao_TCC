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

document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('formAdicionarUsuario');

  form.addEventListener('submit', function (e) {
    e.preventDefault(); // ← impede envio padrão

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
        fecharModalUnico();
      }
    })
    .catch(err => {
      alert('Erro na requisição: ' + err.message);
      console.error(err);
    });
  });
});