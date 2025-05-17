const chatMessages = document.getElementById("chat-messages");
const btnEnviar = document.getElementById("enviar-mensagem");
const inputMensagem = document.getElementById("mensagem-texto");

btnEnviar.addEventListener("click", enviarMensagem);

inputMensagem.addEventListener("keypress", function (e) {
  if (e.key === "Enter" && !e.shiftKey) {
    e.preventDefault();
    enviarMensagem();
  }
});

function enviarMensagem() {
  const texto = inputMensagem.value.trim();
  if (texto === "") return;

  adicionarMensagem(texto, "mensagem-orientando");
  inputMensagem.value = "";
  chatMessages.scrollTop = chatMessages.scrollHeight;

  setTimeout(() => {
    adicionarMensagem("Mensagem recebida. Vou verificar e te respondo em breve.", "mensagem-orientador");
    chatMessages.scrollTop = chatMessages.scrollHeight;
  }, 1000);
}

function adicionarMensagem(texto, classe) {
  const msg = document.createElement("div");
  msg.classList.add("mensagem", classe);
  msg.textContent = texto;
  chatMessages.appendChild(msg);
}
