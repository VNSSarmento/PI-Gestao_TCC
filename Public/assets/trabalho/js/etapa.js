// Abrir/fechar o conteúdo do card ao clicar no cabeçalho
function toggleCollapsible(headerElement) {
  const collapsible = headerElement.closest(".collapsible");

  // Se estiver editando, não fecha
  const isEditing = collapsible.querySelector("textarea") !== null;
  if (isEditing) return;

  collapsible.classList.toggle("active");
}

// Alternar visibilidade do menu de opções (três pontinhos)
function toggleMenu(button) {
  event.stopPropagation(); // Evita fechamento imediato
  const menu = button.nextElementSibling;
  const allMenus = document.querySelectorAll(".menu-options");

  allMenus.forEach(m => {
    if (m !== menu) m.style.display = "none";
  });

  menu.style.display = menu.style.display === "block" ? "none" : "block";
}

// Editar o conteúdo da etapa
function editCard(item) {
  const article = item.closest(".collapsible");
  const content = article.querySelector(".collapsible-content");
  const p = content.querySelector("p");
  const buttons = content.querySelector(".edit-buttons");

  // Mantém o container aberto
  article.classList.add("active");

  // Evita múltiplas edições
  if (p.querySelector("textarea")) return;

  // Armazena o texto original
  p.setAttribute("data-original", p.textContent.trim());

  // Cria o textarea
  const textarea = document.createElement("textarea");
  textarea.value = p.textContent.trim();
  textarea.style.width = "100%";
  textarea.style.minHeight = "100px";

  // Substitui o parágrafo pelo textarea
  p.innerHTML = "";
  p.appendChild(textarea);

  buttons.style.display = "flex";

  // Fecha menu
  const menu = item.closest(".menu-options");
  if (menu) menu.style.display = "none";
}

// Salvar o conteúdo editado
function saveEdit(button) {
  const article = button.closest(".collapsible");
  const p = article.querySelector(".collapsible-content p");
  const textarea = p.querySelector("textarea");

  if (textarea) {
    p.textContent = textarea.value;
  }

  article.querySelector(".edit-buttons").style.display = "none";
}

// Cancelar edição e restaurar o conteúdo original
function cancelEdit(button) {
  const article = button.closest(".collapsible");
  const p = article.querySelector(".collapsible-content p");
  const original = p.getAttribute("data-original");

  p.textContent = original;
  article.querySelector(".edit-buttons").style.display = "none";
}

// Fecha todos os menus ao clicar fora
document.addEventListener("click", () => {
  document.querySelectorAll(".menu-options").forEach(menu => {
    menu.style.display = "none";
  });
});
