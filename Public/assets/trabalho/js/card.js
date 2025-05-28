 function abrirModalComConteudo(htmlPath) {
        fetch(htmlPath)
          .then(res => res.text())
          .then(html => {
            document.getElementById('modalContent').innerHTML = html;
            document.getElementById('modalUnico').style.display = 'flex';
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


  