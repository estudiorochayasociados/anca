<?php namespace Clases;
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

class Email
{
    private $asunto;
    private $receptor;
    private $emisor;
    private $mensaje;

    public function set($atributo, $valor)
    {
        $this->$atributo = $valor;
    }

    public function get($atributo)
    {
        return $this->$atributo;
    }

    public function emailEnviar()
    {
        $mail = new PHPMailer(true);
        $mensaje = '<body style="background: #0f74a8;margin:0;padding:0"><div style="background: #fff;width:700px;margin:auto;padding:20px"><div><br/><img src="'.LOGO.'" width="200"/><br/><hr/></div>'.$this->mensaje.'<br/></div></body>';
        try {
            //Server settings
            $mail->SMTPDebug = 0;                                 // Enable verbose debug output
            $mail->isSMTP();
            $mail->CharSet = 'UTF-8';
            // Set mailer to use SMTP
            $mail->Host = SMTP_EMAIL;  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = EMAIL;                 // SMTP username
            $mail->Password = PASS_EMAIL;                           // SMTP password
            $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 465;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom($this->emisor, TITULO);
            $mail->addAddress($this->receptor, '');     // Add a recipient

            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $this->asunto;
            $mail->Body = $mensaje;
            $mail->AltBody = strip_tags($mensaje);

            $mail->send();
            return 1;
        } catch (Exception $e) {
            return 0;
        }
    }
}

?>