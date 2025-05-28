 document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('file-input');
            const fileNameDisplay = document.getElementById('file-name-display');
            const submitBtn = document.getElementById('submit-btn');
            const errorMessage = document.getElementById('error-message');
            
            fileInput.addEventListener('change', function(e) {
                if (this.files && this.files.length > 0) {
                    const file = this.files[0];
                    const fileName = file.name;
                    const fileExtension = fileName.split('.').pop().toLowerCase();
                    
                    if (fileExtension === 'pdf' || fileExtension === 'doc' || fileExtension === 'docx') {
                        fileNameDisplay.textContent = fileName;
                        fileNameDisplay.style.color = '#333';
                        errorMessage.style.display = 'none';
                    } else {
                        fileNameDisplay.textContent = 'Tipo de arquivo inválido (' + fileName + ')';
                        fileNameDisplay.style.color = '#d32f2f';
                        errorMessage.style.display = 'block';
                        this.value = '';
                    }
                } else {
                    fileNameDisplay.textContent = 'Nenhum arquivo selecionado';
                    fileNameDisplay.style.color = '#333';
                }
            });
            
            submitBtn.addEventListener('click', function() {
                if (!fileInput.files || fileInput.files.length === 0) {
                    errorMessage.textContent = 'Por favor, selecione um arquivo';
                    errorMessage.style.display = 'block';
                } else if (fileNameDisplay.textContent.startsWith('Tipo de arquivo inválido')) {
                    errorMessage.style.display = 'block';
                } else {
                    // Simulação do envio do arquivo
                    alert('Arquivo enviado com sucesso: ' + fileNameDisplay.textContent);
                }
            });
        });