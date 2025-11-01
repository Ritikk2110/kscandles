<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once(__DIR__ . '/../PHPMailer-master/src/Exception.php');
require_once(__DIR__ . '/../PHPMailer-master/src/PHPMailer.php');
require_once(__DIR__ . '/../PHPMailer-master/src/SMTP.php');
require_once(__DIR__ . '/config.php'); // for SMTP constants

function send_contact_email($name, $email, $message) {
    $mail = new PHPMailer(true);

    try {
        // SMTP CONFIG
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USER;
        $mail->Password   = SMTP_PASS;
        $mail->SMTPSecure = 'tls';
        $mail->Port       = SMTP_PORT;

        // ADMIN MAIL
        $mail->setFrom(SMTP_USER, SITE_NAME);
        $mail->addAddress(ADMIN_EMAIL, 'Admin');
        $mail->addReplyTo($email, $name);

        // EMBEDDED LOGO (optional)
        $logoPath = __DIR__ . '/../assets/img/logo.png';
        if (file_exists($logoPath)) {
            $mail->AddEmbeddedImage($logoPath, 'logoimg');
            $logoHTML = '<img src="cid:logoimg" width="120" alt="' . SITE_NAME . ' Logo">';
        } else {
            $logoHTML = '';
        }

        // MESSAGE BODY FOR ADMIN
        $mail->isHTML(true);
        $mail->Subject = "📩 New Contact Message from $name";
        $mail->Body = "
            $logoHTML
            <h3>New Inquiry Details</h3>
            <p><strong>Name:</strong> $name</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Message:</strong><br>" . nl2br(htmlspecialchars($message)) . "</p>
        ";

        $mail->send(); // Send to admin

        // =========================
        // AUTO-REPLY TO USER
        // =========================
        $autoReply = new PHPMailer(true);
        $autoReply->isSMTP();
        $autoReply->Host       = SMTP_HOST;
        $autoReply->SMTPAuth   = true;
        $autoReply->Username   = SMTP_USER;
        $autoReply->Password   = SMTP_PASS;
        $autoReply->SMTPSecure = 'tls';
        $autoReply->Port       = SMTP_PORT;

        $autoReply->setFrom(SMTP_USER, SITE_NAME);
        $autoReply->addAddress($email, $name);
        if (file_exists($logoPath)) {
            $autoReply->AddEmbeddedImage($logoPath, 'logoimg');
        }

        $autoReply->isHTML(true);
        $autoReply->Subject = "Thank you, $name, for contacting " . SITE_NAME;
        $autoReply->Body = "
            <p>Dear $name,</p>
            <p>Thank you for reaching out to <strong>" . SITE_NAME . "</strong>. We’ve received your message and will get back to you shortly.</p>
            <p>Warm regards,<br><strong>" . SITE_NAME . "</strong></p>
        ";

        $autoReply->send();

        return true;

    } catch (Exception $e) {
        error_log("Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}
