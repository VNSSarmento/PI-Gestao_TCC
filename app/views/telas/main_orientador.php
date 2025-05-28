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
                    <button class="botao_acao" onclick="abrirModalComConteudo('/?rota=modal_anexar_doc')">Anexar Documento</button>
                    <button class="botao_acao" onclick="abrirModalComConteudo('/?rota=perfil_orientador.html')">Meu Perfil</button>
                    <button onclick="">Etapas do TCC</button>
                   <a href="/?rota=logout"><button onclick="abrirModal('logout')">Fazer LogOut</button></a> 
                </ul>
            </nav>
        </section>
    </header>

    <div class="content-wrapper">   
        <aside class="barra_lateral">
            <div class="pesquisa">
                <input type="text" id="campoPesquisa" placeholder="Digite o nome do aluno">
            </div>
            <h3>Orientandos</h3>
            <ul class="colaborador_lista">
                <?php foreach ($alunos as $aluno): ?>
                    <button class="usuario" data-id="<?= $aluno['id'] ?>">
                        <span class="nome"><?= htmlspecialchars($aluno['nome']) ?></span>
                        <span class="tipo">Aluno</span>
                    </button>
                <?php endforeach; ?>
            </ul>
        </aside>
        <main class="content">
            <section class="entrega_item">
                <div class="entrega_header" onclick="toggleEntrega(this)">
                    <h3>Entrega 1</h3>
                </div>
                <div class="documentos_entrega">
                    <div class="documento_item aluno">
                        <div class="icone_documento"><img src="/./Public/assets/trabalho/imagens/documentosenvioaluno.png" alt=""></div>
                        <div class="documento_info">
                            <span class="nome_documento">Documento do Aluno</span>
                            <span class="separador">|</span>
                            <span class="autor">Aluno: Fulano da Silva - 202501001</span>
                            <span class="separador">|</span>
                            <span class="descricao">Análise e desenvolvimento de Sistema</span>
                            <span class="separador">|</span>
                            <span class="data">Enviado: 01/01/2025 23:59:59</span>
                        </div>
                        <button class="botao_download">Fazer Download</button>
                    </div>
                    <div class="documento_item professor">
                        <div class="icone_documento"><img src="/./Public/assets/trabalho/imagens/documentosEnvio.png"alt=""></div>
                        <div class="documento_info">
                            <span class="nome_documento">Correção do Professor</span>
                            <span class="separador">|</span>
                            <span class="autor">Professor: Paula Lima</span>
                            <span class="separador">|</span>
                            <span class="descricao">Correções e comentários</span>
                            <span class="separador">|</span>
                            <span class="data">Enviado: 02/01/2025 10:00:00</span>
                        </div>
                        <button class="botao_download">Fazer Download</button>
                    </div>
                    <div class="comentario_exibido">
                        <strong>Comentário:</strong>
                        <p>O documento está bem estruturado, mas revise a parte 3.2 e adicione referências atualizadas.</p>
                    </div>
                </div>
            </section>
        </main>
    </div>
    
    <div id="modalUnico" class="modal">
      <div id="modalContent" class="modal-content"></div>
      </div>
    </div>
    <script src="/asstes/js/script_sobreposicao.js"></script>
    <script src="/Public/assets/trabalho/js/card.js"></script>
    <script src="/Public/assets/trabalho/js/formulario.js"></script>
    <script src="/Public/assets/trabalho/js/anexar.js"></script>
    <script src="/Public/assets/trabalho/js/teste.js"></script>
    <script src="/Public/assets/trabalho/js/prazo_entrega.js"></script>
    <script src ="/Public/assets/trabalho/js/entregas.js"></script>

<!-- <script>
    function carregarDocumentosAluno(botao) {
        const alunoId = botao.getAttribute('data-id');
        console.log('Aluno selecionado: ', alunoId);

        // Define o valor do input hidden do formulário (caso exista no DOM já)
        const inputIdAluno = document.querySelector('input[name="id_aluno"]');
        if (inputIdAluno) {
            inputIdAluno.value = alunoId;
        }

        // Carrega os documentos do aluno via fetch
        fetch(`/?rota=documento_aluno&id=${alunoId}`)
            .then(res => res.text())
            .then(html => {
                document.querySelector('.content').innerHTML = html;

                const novoInput = document.querySelector('input[name="id_aluno"]');
                if (novoInput) {
                    novoInput.value = alunoId;
                }
            })
            .catch(err => {
                console.error('Erro ao carregar documentos:', err);
            });
    }
</script> -->


</body>
</html>
