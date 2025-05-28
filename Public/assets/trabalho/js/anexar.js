// Função para abrir o modal e carregar o conteúdo
function abrirModalComConteudo(htmlPath) {
  fetch(htmlPath)
    .then(res => res.text())
    .then(html => {
      document.getElementById('modalContent').innerHTML = html;
      document.getElementById('modalUnico').style.display = 'flex';

      // Aguarda o DOM renderizar o conteúdo injetado
      setTimeout(() => {
        // ➤ Define a data mínima no input de prazo
        const inputData = document.getElementById("prazo-entrega");
        if (inputData) {
          const hoje = new Date().toISOString().split("T")[0];
          inputData.setAttribute("min", hoje);
        }

        // ➤ Exibe o nome do arquivo selecionado
        const fileInput = document.getElementById("file-input");
        const fileNameDisplay = document.getElementById("file-name-display");

        if (fileInput && fileNameDisplay) {
          fileInput.addEventListener("change", () => {
            const file = fileInput.files[0];
            fileNameDisplay.textContent = file ? file.name : "Nenhum arquivo selecionado";
          });
        }
      }, 50); // Espera um pouco para garantir que o conteúdo esteja no DOM
    });
}

// Função para fechar o modal
function fecharModalUnico() {
  document.getElementById("modalUnico").style.display = "none";
  document.getElementById("modalContent").innerHTML = "";
}

// Fechar modal ao clicar fora do conteúdo
document.addEventListener("click", function (e) {
  const modal = document.getElementById("modalUnico");
  if (e.target === modal) {
    fecharModalUnico();
  }
});

document.addEventListener("DOMContentLoaded", function () {
  const submitBtn = document.getElementById("submit-btn");
  if (submitBtn) {
    submitBtn.addEventListener("click", function () {
      const fileInput = document.getElementById("file-input");
      const comentario = document.getElementById("comentario-texto").value;
      const alunoId = document.getElementById("alunoSelecionado").value;
      const tipoRemetente = "<?php echo $_SESSION['tipo']; ?>"; // Se precisar enviar também
      const prazo = document.getElementById("prazo_entrega")?.value || "";

      if (!fileInput.files[0]) {
        alert("Por favor, selecione um arquivo.");
        return;
      }

      const formData = new FormData();
      formData.append("arquivo", fileInput.files[0]);
      formData.append("comentario", comentario);
      formData.append("aluno", alunoId);
      formData.append("tipo", tipoRemetente);
      formData.append("prazo_entrega", prazo);

      fetch("/?rota=salvarAnexo", {
        method: "POST",
        body: formData,
      })
        .then(res => res.json())
        .then(data => {
          if (data.sucesso) {
            alert("Documento enviado com sucesso!");
            fecharModalUnico(); // Fecha o modal
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
});

