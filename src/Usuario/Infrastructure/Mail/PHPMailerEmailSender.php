<?php
namespace App\Usuario\Infrastructure\Mail;

use App\Usuario\Domain\Service\EmailSenderInterface;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class PHPMailerEmailSender implements EmailSenderInterface {
    
    public function enviarCorreoRecuperacion(string $emailDestino, string $enlace): bool {
        $mail = new PHPMailer(true);

        try {
            // Configuración del Servidor SMTP
            $mail->isSMTP();
            $mail->Host       = $_ENV['SMTP_HOST'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['SMTP_USER']; 
            $mail->Password   = $_ENV['SMTP_PASS']; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $_ENV['SMTP_PORT'];

            // ---> ¡ESTO FALTABA! Destinatarios <---
            $mail->setFrom($_ENV['SMTP_USER'], 'CensoApp Unicartagena');
            $mail->addAddress($emailDestino);

            // Contenido del Correo
            $mail->isHTML(true);
            $mail->Subject = 'Recuperacion de Contrasena - CensoApp';
            $mail->Body    = "
                <div style='font-family: Arial, sans-serif; padding: 20px; color: #0f1f3d;'>
                    <h2>Hola,</h2>
                    <p>Recibimos una solicitud para restablecer tu contraseña en <b>CensoApp</b>.</p>
                    <p>Haz clic en el siguiente botón (válido por 1 hora):</p>
                    <a href='{$enlace}' style='background: #0d7377; color: white; padding: 12px 20px; text-decoration: none; border-radius: 8px; display: inline-block; font-weight: bold;'>Restablecer Contraseña</a>
                    <br><br>
                    <p style='color: #8896a8; font-size: 13px;'>Si el botón no funciona, copia y pega este enlace en tu navegador:<br>{$enlace}</p>
                    <hr style='border: 0; border-top: 1px solid #dce2ea; margin: 20px 0;'>
                    <p style='color: #8896a8; font-size: 12px;'>Si no solicitaste este cambio, ignora este mensaje.</p>
                </div>
            ";

            $mail->send();
            return true;
        } catch (Exception $e) {
            // TEMPORAL PARA PRUEBAS: Mostrará por qué falló exactamente
            echo "Error de PHPMailer: " . $mail->ErrorInfo; 
            exit(); 
        }
    }
}