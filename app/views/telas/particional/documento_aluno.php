<?php if (empty($documentos)): ?>
    <p>Nenhum documento enviado ainda por <?= htmlspecialchars($nomeAluno) ?>.</p>
<?php else: ?>
    <section class="entrega_item">
    <div class="entrega_header" onclick="toggleEntrega(this)">
        <h3>Entrega 1</h3>
    </div>
    <div class="documentos_entrega">
        <?php if (empty($documentos)): ?>
            <p>Nenhum documento enviado ainda por <?= htmlspecialchars($nomeAluno) ?>.</p>
        <?php else: ?>
            <?php foreach ($documentos as $doc): ?>
                <?php if ($doc['tipo'] === 'aluno'): ?>
                    <div class="documento_item aluno">
                        <div class="icone_documento"><img src="/Public/assets/trabalho/imagens/documentosenvioaluno.png" alt=""></div>
                        <div class="documento_info">
                            <span class="nome_documento"><?= htmlspecialchars($doc['titulo']) ?></span>
                            <span class="separador">|</span>
                            <span class="autor">Aluno: <?= htmlspecialchars($nomeAluno) ?></span>
                            <span class="separador">|</span>
                            <span class="descricao"><?= htmlspecialchars($doc['descricao']) ?></span>
                            <span class="separador">|</span>
                            <span class="data">Enviado: <?= htmlspecialchars($doc['data_envio']) ?></span>
                        </div>
                        <a href="/Public/uploads/<?= htmlspecialchars($doc['arquivo']) ?>" class="botao_download" download>Fazer Download</a>
                    </div>
                <?php else: ?>
                    <div class="documento_item professor">
                        <div class="icone_documento"><img src="/Public/assets/trabalho/imagens/documentosEnvio.png" alt=""></div>
                        <div class="documento_info">
                            <span class="nome_documento"><?= htmlspecialchars($doc['titulo']) ?></span>
                            <span class="separador">|</span>
                            <span class="autor">Professor: <?= htmlspecialchars($user['nome']) ?></span>
                            <span class="separador">|</span>
                            <span class="descricao"><?= htmlspecialchars($doc['descricao']) ?></span>
                            <span class="separador">|</span>
                            <span class="data">Enviado: <?= htmlspecialchars($doc['data_envio']) ?></span>
                        </div>
                        <a href="/Public/uploads/<?= htmlspecialchars($doc['arquivo']) ?>" class="botao_download" download>Fazer Download</a>
                    </div>
                    <?php if (!empty($doc['comentario'])): ?>
                        <div class="comentario_exibido">
                            <strong>Comentário:</strong>
                            <p><?= htmlspecialchars($doc['comentario']) ?></p>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>
<form action="/?rota=anexar_documento" method="GET">
    <input type="hidden" name="id" value="<?= htmlspecialchars($_GET['id']) ?>">
    <button type="submit" class="botao_anexar">Anexar Documento</button>
</form>

<?php endif; ?>
