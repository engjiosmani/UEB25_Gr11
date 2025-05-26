<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';

$mail = new PHPMailer(true);

try {
    // Konfigurimi i SMTP me Gmail
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;

    // Vendos emailin dhe fjalëkalimin (ose App Password)
    $mail->Username   = 'emriyt@gmail.com'; 
    $mail->Password   = 'fjalekalimi_ose_app_password'; 

    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    // Dërguesi dhe marrësi
    $mail->setFrom('emriyt@gmail.com', 'Festava Live');
    $mail->addAddress('admin@festava.com'); // ku shkon emaili

    // Përmbajtja e emailit
    $mail->isHTML(true);
    $mail->Subject = 'Mesazh nga përdoruesi';
    $mail->Body    = "Kompania: {$_POST['contact-company']}<br>Mesazhi: {$_POST['contact-message']}";

    $mail->send();
    echo 'Emaili u dërgua me sukses!';
} catch (Exception $e) {
    echo "Dërgimi dështoi. Gabimi: {$mail->ErrorInfo}";
}
?>
