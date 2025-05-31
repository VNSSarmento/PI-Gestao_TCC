function abrirModalComConteudo(htmlPath) {
  fetch(htmlPath)
    .then(res => res.text())
    .then(html => {
      const modalContent = document.getElementById('modalContent');
      const modalUnico = document.getElementById('modalUnico');

      modalContent.innerHTML = html;
      modalUnico.style.display = 'flex';

      // Função para configurar listeners, só uma vez
      function configurarListeners() {
        const hoje = new Date().toISOString().split("T")[0];
        const inputData = document.getElementById("prazo_entrega");
        if (inputData) {
          inputData.setAttribute("min", hoje);
        }

        const fileInput = document.getElementById("file-input");
        const fileNameDisplay = document.getElementById("file-name-display");
        const errorMsg = document.getElementById("error-message");
        const submitBtn = document.getElementById("submit-btn");

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

        if (submitBtn) {
          // Remover listener anterior para evitar múltiplos envios
          submitBtn.replaceWith(submitBtn.cloneNode(true));
          const novoSubmitBtn = document.getElementById("submit-btn");

          novoSubmitBtn.addEventListener("click", function handleSubmit(e) {
            e.preventDefault();

            const file = fileInput?.files[0];
            const comentario = document.getElementById("comentario-texto")?.value || '';
            const alunoId = document.getElementById("alunoSelecionado")?.value || '';
            const tipoRemetente = novoSubmitBtn.dataset.tipoRemetente;
            const prazo = document.getElementById("prazo_entrega")?.value || '';
            const erroPrazo = document.getElementById("erro-prazo");

            // Validações
            if (!file) {
              alert("Por favor, selecione um arquivo válido.");
              return;
            }

            if (tipoRemetente !== "aluno" && !prazo) {
              if (erroPrazo) erroPrazo.style.display = "block";
              return;
            } else {
              if (erroPrazo) erroPrazo.style.display = "none";
            }

            // Desativa botão para evitar múltiplos envios
            novoSubmitBtn.disabled = true;
            novoSubmitBtn.textContent = "Enviando...";

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
                  novoSubmitBtn.disabled = false;
                  novoSubmitBtn.textContent = "Enviar";
                }
              })
              .catch(err => {
                console.error("Erro no envio:", err);
                alert("Erro técnico ao enviar o documento.");
                novoSubmitBtn.disabled = false;
                novoSubmitBtn.textContent = "Enviar";
              });
          });
        }
      }

      configurarListeners();
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
