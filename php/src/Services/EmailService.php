<?php 

namespace App\Services;

use App\Models\Users;
use DateTime;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class EmailService {

    /**
     * send an email
     *
     * @param string $from
     * @param string $to
     * @param string $subject
     * @param string $body
     * @param boolean $isHtml
     * @param string|null $name
     * @return boolean
     */
    public static function sendEmail(string $from,  string $to, string $subject, string $body, bool $isHtml, string $name=null, string $attachement=null): bool
    {
        $mail = new PHPMailer(true);

        try{
            $mail->SMTPDebug =3;
            //config SMTP
            $mail->isSMTP();
            $mail->isHTML($isHtml);
            $mail->CharSet = 'UTF-8';
            $mail->Host = $_ENV['SMTP_HOST'];
            $mail->SMTPAuth = true;
            $mail->Port = 2525;
            $mail->Username = $_ENV['EMAIL_USERNAME'];
            $mail->Password = $_ENV['EMAIL_PASSWORD'];
            $mail->SMTPSecure = 'tls';
            $mail->SMTPKeepAlive = true;
            if($attachement){
                $mail->addStringAttachment($attachement, 'soLoveResin.pdf');
            }

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

    /**
     * send an email to reset password or activate an account
     *
     * @param string $subject
     * @param string $to
     * @param integer $idUser
     * @return boolean
     */
    public static function specificSendEmail(string $subject, string $to, int $idUser): bool
    {
        $resetToken = bin2hex(random_bytes(32));
        date_default_timezone_set('Europe/Paris');
        $date = new DateTime();
        $date->modify('+1 hour');
        $createdAt = $date->format('Y-m-d H:i:s');
        $userModel = new Users;
        $userModel->updateResetToken($idUser, $resetToken, $createdAt);
        if($subject === "validation"){
            $userModel->updateCountLink($idUser);
            $subject = "Validation compte";
            $body = "Voici le nouveau lien de validation de votre compte : <br><br>
            <a href='localhost/validation/account/" . $resetToken . "'>Valider mon compte</a>";
        }elseif($subject === "reset"){
            $subject = "Réinitialisation du mot de passe";
            $body = "Voici le lien de réinitialisation du mot de passe : <br><br>
            <a href='localhost/newPassword/" . $resetToken . "'>Réinitialiser le mot de passe</a>";
        }else{
            return false;
        }
        $from = 'soloveresin@gmail.com';
        ob_start();
        $result = EmailService::sendEmail($from, $to, $subject, $body, true, 'So Love Resin');
        ob_get_clean();
        return $result;
    }

    /**
     * send an email of confirmation to a success order with an invoice
     *
     * @param integer $idUser
     * @param string $orderNumber
     * @param string $invoice
     * @return void
     */
    public static function confirmationOrderEmail(int $idUser, string $orderNumber, string $invoice){
        $from = 'soloveresin@gmail.com';
        $subject ="Confirmation de commande";
        $body = "Bonjour ! \n\n Nous vous confirmons que votre commande n°" . $orderNumber . " a bien été validée. \n
        Nous vous remercions de votre achat et votre confiance.\n
        Nous traiterons votre commande des que possible.\n\n
        So Love Resin.";
        $userModel = new Users;
        $to = $userModel->getUserById($idUser)->email;
        ob_start();
        $result = EmailService::sendEmail($from, $to, $subject, nl2br($body), true, 'So Love Resin', $invoice);
        ob_get_clean();
        return $result;
    }
}