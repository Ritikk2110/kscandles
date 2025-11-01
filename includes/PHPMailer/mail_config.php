<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/PHPMailer/PHPMailer.php';
require __DIR__ . '/PHPMailer/SMTP.php';
require __DIR__ . '/PHPMailer/Exception.php';

function sendMail($to, $subject, $body, $alt = '') {
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // e.g., Gmail SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'kr2060398@gmail.com';  // ✅ your Gmail / business mail
        $mail->Password = 'xmrbxzhbchuabof';           // ✅ Use Gmail App Password (not your normal pass)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Sender
       $mail->setFrom('kr2060398@gmail.com', 'KS Candles');

        $mail->addReplyTo('inforscandles@gmail.com', 'KS Candles Support');

        // Recipient
        $mail->addAddress('kr2060398@gmail.com', 'Ritik Kumar');

       // $mail->addAddress($to);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AltBody = $alt ?: strip_tags($body);

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mail Error: {$mail->ErrorInfo}");
        return false;
    }
}
