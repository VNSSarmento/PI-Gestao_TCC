// Observa se o campo de data é adicionado dinamicamente ao DOM (como num modal)
const observer = new MutationObserver(() => {
    const input = document.getElementById("prazo-entrega");
    if (input) {
        const hoje = new Date().toISOString().split("T")[0];
        input.setAttribute("min", hoje);
        observer.disconnect(); // Para de observar após aplicar
    }
});

// Inicia observação do DOM
observer.observe(document.body, {
    childList: true,
    subtree: true
});
