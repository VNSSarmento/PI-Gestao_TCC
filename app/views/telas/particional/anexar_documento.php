<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="/Public/assets/trabalho/css/anexar_documento.css"/>
  <title>Anexar Documento</title>
</head>
<body>
  <div class="container">
      <h1>Anexando Documento</h1>
      <?php $tipo = $_SESSION['tipo'] ?? null; ?>
      <form action="/?rota=salvar_anexo" method="post" enctype="multipart/form-data">
        <div class="file-selection">
            <span class="file-select-button" onclick="document.getElementById('file-input').click()">Selecionar arquivo</span>
            <span class="file-name" id="file-name-display">Nenhum arquivo selecionado</span>

            <div class="error-message" id="error-message">
              Apenas arquivos PDF ou DOC são permitidos
            </div>
            <input type="file" id="file-input" name="documento" accept=".pdf,.doc,.docx" style="display: none;">
        </div>

        <div class="comentario">
            <?php if ($tipo !== 'aluno'): ?>
            <label for="comentario-texto">Comentário sobre a entrega:</label>
                <textarea name="comentario" id="comentario-texto" rows="4" placeholder="Digite seu comentário aqui..."></textarea>
            <?php endif; ?>
        </div>
            <input type="hidden" name="aluno" id="alunoSelecionado" value="<?= htmlspecialchars($_GET['id'] ?? '') ?>">
        <div class="entrega">
            <?php if ($tipo !== 'aluno'): ?>
                  <label for="prazo_entrega">Prazo para entrega:</label>
                  <input type="date" name="prazo_entrega" id="prazo_entrega">
                  <div id="erro-prazo" style="color: red; display: none; margin-top: 10px;">Por favor, selecione o prazo para entrega.</div>
            <?php endif; ?>
            <button class="submit-btn" id="submit-btn"  data-tipo-remetente="<?= $_SESSION['tipo'] ?>">Enviar</button>
        </div>
      </form>
  </div>

<script src="/Public/assets/trabalho/js/prazo_entrega.js"></script>
<script src="/Public/assets/trabalho/js/teste.js"></script>
<script src="/Public/assets/trabalho/js/anexar.js"></script> <!-- <- aqui fica o abrirmodalcomconteudo -->
</body>
</html>