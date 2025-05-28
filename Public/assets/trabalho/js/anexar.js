function abrirModalComConteudo(htmlPath) {
  fetch(htmlPath)
    .then(res => res.text())
    .then(html => {
      document.getElementById('modalContent').innerHTML = html;
      document.getElementById('modalUnico').style.display = 'flex';

      setTimeout(() => {
        const hoje = new Date().toISOString().split("T")[0];
        const inputData = document.getElementById("prazo-entrega");
        if (inputData) {
          inputData.setAttribute("min", hoje);
        }

        const fileInput = document.getElementById("file-input");
        const fileNameDisplay = document.getElementById("file-name-display");
        const errorMsg = document.getElementById("error-message");

        if (fileInput && fileNameDisplay && errorMsg) {
          fileInput.addEventListener("change", () => {
            const file = fileInput.files[0];
            if (file) {
              const ext = file.name.split(".").pop().toLowerCase();
              const allowed = ["pdf", "doc", "docx"];
              if (!allowed.includes(ext)) {
                errorMsg.style.display = "block";
                fileNameDisplay.textContent = "Nenhum arquivo selecionado";
                fileInput.value = '';
              } else {
                errorMsg.style.display = "none";
                fileNameDisplay.textContent = file.name;
              }
            }
          });
        }

        const submitBtn = document.getElementById("submit-btn");
        if (submitBtn) {
          submitBtn.addEventListener("click", function (e) {
            e.preventDefault();

            const file = fileInput.files[0];
            const comentario = document.getElementById("comentario-texto")?.value || '';
            const alunoId = document.getElementById("alunoSelecionado")?.value || '';
            const tipoRemetente = submitBtn.dataset.tipoRemetente;
            const prazo = document.getElementById("prazo_entrega")?.value || '';

            if (!file) {
              alert("Por favor, selecione um arquivo válido.");
              return;
            }

            const formData = new FormData();
            formData.append("documento", file);
            formData.append("comentario", comentario);
            formData.append("aluno", alunoId);
            formData.append("tipo", tipoRemetente);
            formData.append("prazo_entrega", prazo);

            fetch("/?rota=salvar_anexo", {
              method: "POST",
              body: formData,
            })
              .then(res => res.json())
              .then(data => {
                if (data.sucesso) {
                  alert("Documento enviado com sucesso!");
                  fecharModalUnico();
                } else {
                  alert("Erro ao enviar: " + data.mensagem);
                }
              })
              .catch(err => {
                console.error("Erro no envio:", err);
                alert("Erro técnico ao enviar o documento.");
              });
          });
        }
      }, 50);
    });
}

function fecharModalUnico() {
  document.getElementById("modalUnico").style.display = "none";
  document.getElementById("modalContent").innerHTML = "";
}

document.addEventListener("click", function (e) {
  const modal = document.getElementById("modalUnico");
  if (e.target === modal) {
    fecharModalUnico();
  }
});
