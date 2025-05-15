<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php'; // Ajuste conforme a estrutura

class EmailHelper {
    public static function enviarEmailRedefinicao($email, $token, $nome) {
        $mail = new PHPMailer(true);

        try {
            // Configurações do servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.seudominio.com'; // Ex: smtp.gmail.com
            $mail->SMTPAuth = true;
            $mail->Username = 'seuemail@seudominio.com';
            $mail->Password = 'suasenha';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Conteúdo do e-mail
            $mail->setFrom('seuemail@seudominio.com', 'Sistema');
            $mail->addAddress($email, $nome);
            $mail->isHTML(true);
            $mail->Subject = 'Redefinição de senha';
            $link = "http://localhost/?rota=nova_senha&token=$token";
            $mail->Body = "Olá, $nome. <br>Clique no link abaixo para redefinir sua senha:<br><a href=\"$link\">$link</a>";

            $mail->send();
            return true;

        } catch (Exception $e) {
    echo 'Erro ao enviar e-mail: ' . $e->getMessage();
    // Também loga no erro do PHP, só pra garantir
    error_log('Erro ao enviar e-mail: ' . $e->getMessage());
    return false;
}


    }
}
