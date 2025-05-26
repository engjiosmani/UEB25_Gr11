<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';

require_once 'db_conn.php';
require_once 'error_handler.php';
require_once 'klasat/User.php';
require_once 'klasat/Admin.php';
session_start();

if (!isset($_SESSION['user'])) {
    $_SESSION['contact_error'] = "Duhet të jeni të kyçur për të dërguar mesazh.";
    header("Location: index.php#section_6");
    exit;
}

function get_bad_words() {
    $bad_words = [];
    if (file_exists('bad_words.txt')) {
        $bad_words = file('bad_words.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $bad_words = array_map('trim', $bad_words);
    }
    return $bad_words;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["contact-message"])) {
    $user_id = $_SESSION['user']->id;
    $company = htmlspecialchars(trim($_POST["contact-company"]));
    $message = htmlspecialchars(trim($_POST["contact-message"]));

    $message_cleaned = strip_tags($message);
    $message_cleaned = preg_replace('/[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}]/u', '', $message_cleaned);
    $message_cleaned = preg_replace("/[#@]\w+/", "", $message_cleaned);

    $bad_words = get_bad_words();
    if (!empty($bad_words)) {
        $pattern = '/\\b(' . implode('|', array_map('preg_quote', $bad_words)) . ')\\b/iu';
        $message_cleaned = preg_replace($pattern, "***", $message_cleaned);
    }

    try {
        if (empty($company) || empty($message_cleaned)) {
            throw new Exception("Ju lutem plotësoni të gjitha fushat.");
        }

        $stmt = $conn->prepare("INSERT INTO contact_messages (user_id, company, message) VALUES (?, ?, ?)");
        if (!$stmt) throw new Exception("Gabim gjatë përgatitjes së deklaratës.");
        $stmt->bind_param("iss", $user_id, $company, $message_cleaned);
        if (!$stmt->execute()) throw new Exception("Gabim gjatë ruajtjes së mesazhit.");

        // === LOGIMI NË FAJLL: contact_log.txt ===
        if (!file_exists('logs')) mkdir('logs', 0777, true);

        $contactLog = fopen("logs/contact_log.txt", "a");
        $timestamp = date("Y-m-d H:i:s");
        $logEntry = "[$timestamp] Perd: $user_id | Kompania: $company | Mesazh: $message_cleaned\n";
        fwrite($contactLog, $logEntry);
        fclose($contactLog);
        // =========================================

        // EMAIL
        if (isset($_POST['also_send_email'])) {
            $to = "engjiosmani5@gmail.com";
            $subject = "Mesazh i ri nga kompania: $company";
            $emailMessage = "Nga përdoruesi ID: $user_id\nKompania: $company\nMesazhi: $message_cleaned";

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'engjiosmani5@gmail.com';
                $mail->Password = 'zllo nfkv njdn ijyd'; // App password!
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('engjiosmani5@gmail.com', 'Festava Live');
                $mail->addAddress($to);
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body = nl2br($emailMessage);
                $mail->send();
            } catch (Exception $e) {
                error_log("Emaili nuk u dërgua: " . $mail->ErrorInfo);
            }
        }

        $_SESSION['message_sent'] = true;
        header("Location: index.php#section_6");
        exit;

    } catch (Exception $e) {
        echo "<div class='alert alert-danger text-center'>Gabim: " . htmlspecialchars($e->getMessage()) . "</div>";
    }
}

?>
