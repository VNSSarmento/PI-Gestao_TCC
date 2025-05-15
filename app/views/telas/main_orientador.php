<!DOCTYPE html>
<html lang="pt_br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Public/assets/trabalho/css/tela_inicial_orientador.css">
    <title>Document</title>
</head>
<body>
    <header>
        <section class="perfil">
            <div class="foto_perfil">
              <img src="/Public/assets/uploads/<?php echo htmlspecialchars($user['foto'] ?? 'default.png'); ?>" alt="foto de perfil">
            </div>
            <div class="perfil_info">
                <h2><?php echo htmlspecialchars($user['nome']); ?></h2>
                <p>ADS</p>
            </div>
        </section>
        <section class="link">
            <nav>
                <ul>
                    <button onclick="abrirModal('upload')">Anexar Documento</button>
                    <button onclick="abrirModal('meus_alunos')">Meus Alunos</button>
                   <a href="/?rota=logout"><button onclick="abrirModal('logout')">Fazer LogOut</button></a> 
                </ul>
            </nav>
        </section>
    </header>

    <div class="content-wrapper">
         <aside class="barra_lateral">
      <div class="pesquisa">
        <input type="text" placeholder="Digite o nome do aluno">
      </div>
      <h3>Colaboradores</h3>
      <ul class="colaborador_lista">
        <li><span class="nome">Renan Machado</span> <span class="tipo">Orientando</span></li>
        <li><span class="nome">Paula Lima</span> <span class="tipo">Orientador</span></li>
        <li><span class="nome">Carlos Alberto</span> <span class="tipo">Orientando</span></li>
        <li><span class="nome">Fernanda Souza</span> <span class="tipo">Orientador</span></li>
      </ul>
    </aside>
        <main class="content">
            <section class="lista_documentos">
                <div class="documento_item">
                    <div class="icone_documento">
                        <img src="/Public/assets/trabalho/imagens/documento.png" alt="">
                    </div>
                    <div class="documento_info">
                        <span class="nome_documento">Documento V1_Tipo.word</span>
                        <span class="separador">|</span>
                        <span class="autor">Aluno: Fulano da Silva - 202501001</span>
                        <span class="separador">|</span>
                        <span class="descricao">Análise e desenvolvimento de Sistema</span>
                        <span class="separador">|</span>
                        <span class="data">Enviado: 01/01/1989 23:59:59</span>
                    </div>
                    <button class="botao_download">Fazer Download</button>
                </div>
                <!-- Outros documento_item omitidos para brevidade -->
            </section>

            <!-- Modal -->
           <!-- Modal de Anexar Documento -->
            <div id="modalAnexar" class="modal">
                <div class="modal-overlay" onclick="fecharModal()"></div>
                <div class="modal-content">
                    <div class="modal-header">
                        <h3>Anexar Documento</h3>
                        <button class="modal-close" onclick="fecharModal()">&times;</button>
                    </div>
                    
                    <div class="upload-box">
                        <div class="dox-icon">Dox</div>
                        <p>Arraste o arquivo aqui ou clique para selecionar</p>
                        
                        <div class="upload-actions">
                            <label for="file-upload" class="custom-file-input">
                                Escolher arquivo
                            </label>
                            <div class="file-name">Nenhum arquivo escolhido</div>
                        </div>
                        
                        <input id="file-upload" type="file" style="display: none;">
                    </div>
                    
                    <div class="modal-footer">
                        <button class="btn" onclick="enviarDocumento()">Enviar</button>
                        <button class="btn-secondary" onclick="fecharModal()">Cancelar</button>
                    </div>
                </div>
            </div>
              
        </main>
    </div>
    <script src="/asstes/js/script_sobreposicao.js"></script>
</body>
</html>
