function abrirModalComConteudo(url) {
  fetch(url)
    .then(res => res.text())
    .then(html => {
      document.getElementById('modalContent').innerHTML = html;
      document.getElementById('modalUnico').style.display = 'flex';

      // Verifica se é o modal de adicionar usuário
      const form = document.getElementById('formAdicionarUsuario');
      if (form) {
        form.addEventListener('submit', function (e) {
          e.preventDefault();

          const formData = new FormData(this);
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
      }

      // Ativa os botões de bloquear se existirem no modal
      if (document.querySelector('.btn-bloquear')) {
        ativarEventosDeBloqueio();
      }

      // Ativa filtro se campo de pesquisa existir
      if (document.getElementById('campoPesquisa')) {
        ativarFiltroColaboradores();
      }
    })
    .catch(err => {
      console.error('Erro ao carregar modal:', err);
    });
}



      function fecharModalUnico() {
        document.getElementById('modalUnico').style.display = 'none';
        document.getElementById('modalContent').innerHTML = '';
      }

      document.addEventListener('click', function (e) {
        const modal = document.getElementById('modalUnico');
        if (e.target === modal) fecharModalUnico();
      });


  