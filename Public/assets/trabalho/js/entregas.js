function toggleEntrega(header) {
    const content = header.nextElementSibling;
    if (content.style.display === 'flex') {
        content.style.display = 'none';
    } else {
        content.style.display = 'flex';
    }
}
