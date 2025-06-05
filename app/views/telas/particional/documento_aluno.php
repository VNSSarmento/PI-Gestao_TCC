<?php if (empty($documentos)): ?>
    <p>Nenhum documento enviado ainda por <?= htmlspecialchars($nomeAluno) ?>.</p>
<?php else: ?>
    <?php
    // Agrupar documentos por número de entrega
    $entregasAgrupadas = [];
    foreach ($documentos as $doc) {
        $numeroEntrega = $doc['entrega'] ?? 1; // fallback para entrega 1 se não existir
        $entregasAgrupadas[$numeroEntrega][] = $doc;
    }
    ?>

    <?php foreach ($entregasAgrupadas as $numeroEntrega => $docs): ?>
        <section class="entrega_item">
            <div class="entrega_header" onclick="toggleEntrega(this)">
                <h3>Entrega <?= $numeroEntrega ?> do aluno <?= htmlspecialchars($nomeAluno) ?></h3>    
            </div>
            <div class="documentos_entrega">
                <?php foreach ($docs as $doc): ?>
                    <?php if (($doc['tipo_remetente'] ?? '') === 'aluno'): ?>
                        <div class="documento_item aluno">
                            <div class="icone_documento">
                                <img src="/Public/assets/trabalho/imagens/contrato.png" alt="">
                            </div>
                            <div class="documento_info">
                                <span class="nome_documento">Envio do Aluno</span>
                                <span class="separador">|</span>
                                <span class="autor">Aluno: <?= htmlspecialchars($nomeAluno) ?></span>
                                <span class="separador">|</span>
                                <span class="data">Enviado: <?= date('d/m/Y', strtotime($doc['data_envio'])) ?></span>
                            </div>
                                <a href="<?= htmlspecialchars($doc['caminho_arquivo']) ?>" download>
                                    <button class="botao_download">Fazer Download</button>
                                </a>
                        </div>
                    <?php else: ?>
                        <div class="documento_item professor">
                            <div class="icone_documento">
                                <img src="/Public/assets/trabalho/imagens/documentosEnvio.png" alt="">
                            </div>
                            <div class="documento_info">
                                <span class="nome_documento">Documento do orientador</span>
                                <span class="separador">|</span>
                                <span class="autor">Professor: <?= htmlspecialchars($user['nome']) ?></span>
                                <span class="separador">|</span>
                                <span class="data">Enviado: <?= date('d/m/Y', strtotime($doc['data_envio'])) ?></span>
                                <span class="separador">|</span>
                                <span class="data">Prazo da entrega: <?= htmlspecialchars($doc['prazo_entrega']) ?></span>
                            </div>
                                <a href="<?= htmlspecialchars($doc['caminho_arquivo']) ?>" download>
                                    <button class="botao_download">Fazer Download</button>
                                </a>
                        </div>

                        <?php if (!empty($doc['comentario'])): ?>
                            <div class="comentario_exibido">
                                <strong>Comentário:</strong>
                                <p><?= htmlspecialchars($doc['comentario']) ?></p>                          
                            </div>  
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; ?>

                <button 
                    class="botao_acao btn-anexar"  
                    data-id="<?= $alunoId ?>"
                    onclick="abrirModalComConteudo('/?rota=modal_anexar_doc&id=<?= htmlspecialchars($_GET['id']) ?>')">
                    Anexar Documento
                </button>
            </div>
        </section>
    <?php endforeach; ?>
<?php endif; ?>
