function atualizarCampoOrientador() {
  const tipoUsuario = document.querySelector('input[name="tipo_usuario"]:checked');
  const campoOrientador = document.getElementById('campo_orientador');

  if (tipoUsuario && tipoUsuario.value === 'orientando') {
    campoOrientador.style.display = 'flex';
  } else {
    campoOrientador.style.display = 'none';
  }
}
