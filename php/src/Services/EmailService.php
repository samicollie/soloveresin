<?php 

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailService {

    public static function sendEmail(string $from,  string $to, string $subject, string $body, bool $isHtml, string $name=null): bool
    {
        $mail = new PHPMailer(true);

        try{
            $mail->SMTPDebug =3;
            //config SMTP
            $mail->isSMTP();
            $mail->isHTML($isHtml);
            $mail->CharSet = 'UTF-8';
            $mail->Host = 'sandbox.smtp.mailtrap.io';
            $mail->SMTPAuth = true;
            $mail->Port = 2525;
            $mail->Username = 'cb523b9e7c7b1d';
            $mail->Password = '8690411e68cb04';
            $mail->SMTPSecure = 'tls';
            $mail->SMTPKeepAlive = true;

            //config message
            $mail->setFrom($from, $name);
            $mail->addAddress($to);
            $mail->Subject = mb_encode_mimeheader($subject, 'UTF-8', 'B');
            $mail->Body = $body;
            

            //send message
            $mail->send();

            return true;
        } catch (Exception $e){
            error_log("Erreur lors de l'envoi de l'e-mail : " . $e->getMessage());
            return false;
        }
    } 
}