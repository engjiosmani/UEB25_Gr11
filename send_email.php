<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';


$emri = "Testuesi";
$mesazhi = "Ky është një mesazh testues nga projekti!";
$email_marrës = "engjiosmani5@gmail.com"; 


$mail = new PHPMailer(true);

try {
    // Konfigurimi për Gmail
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; 
    $mail->SMTPAuth = true;
    $mail->Username = 'engjiosmani5@gmail.com'; // 
    $mail->Password = 'zllo nfkv njdn ijyd'; // 
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Dërguesi dhe Marrësi
    $mail->setFrom('emailiyt@gmail.com', 'Festava Live');
    $mail->addAddress($email_marrës, 'Përdorues');

    // Përmbajtja
    $mail->isHTML(true);
    $mail->Subject = 'Mesazh nga Festava';
    $mail->Body    = "<strong>Emri:</strong> $emri<br><strong>Mesazhi:</strong><br>$mesazhi";

    $mail->send();
    echo 'Emaili u dërgua me sukses!';
} catch (Exception $e) {
    echo "Gabim gjatë dërgimit të emailit. Mesazhi: {$mail->ErrorInfo}";
}
?>
