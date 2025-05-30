function abrirModalComConteudo(url) {
  fetch(url)
    .then(res => res.text())
    .then(html => {
      document.getElementById('modalContent').innerHTML = html;
      document.getElementById('modalUnico').style.display = 'flex';

      // Agora que o formulário foi carregado, adiciona o listener
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
              fecharModalUnico(); // ou atualiza a lista, etc.
            }
          })
          .catch(err => {
            alert('Erro na requisição: ' + err.message);
            console.error(err);
          });
        });
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


  