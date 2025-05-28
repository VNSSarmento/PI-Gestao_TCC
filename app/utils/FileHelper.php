<?php class FileHelper {
        public static function salvarArquivo($arquivo) {
            if (!$arquivo || $arquivo['error'] !== UPLOAD_ERR_OK) {
                return null;
            }

            $nomeArquivo = basename($arquivo['name']);
            $caminho = "uploads/" . uniqid() . "_" . $nomeArquivo;

            if (move_uploaded_file($arquivo['tmp_name'], $caminho)) {
                return $caminho;
            }

            return null;
        }
}
