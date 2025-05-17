function abrirModalComConteudo(htmlPath) {
  fetch(htmlPath)
    .then(res => res.text())
    .then(html => {
      const modalContent = document.getElementById('modalContent');
      modalContent.innerHTML = html;
      document.getElementById('modalUnico').style.display = 'flex';

      // Executa scripts embutidos
      const scripts = modalContent.querySelectorAll('script');
      scripts.forEach(script => {
        const newScript = document.createElement('script');
        if (script.src) {
          newScript.src = script.src;
        } else {
          newScript.textContent = script.textContent;
        }
        document.body.appendChild(newScript); // executa
        document.body.removeChild(newScript); // limpa
      });
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