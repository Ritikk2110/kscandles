<?php
require_once __DIR__ . '/includes/PHPMailer/src/Exception.php';
require_once __DIR__ . '/includes/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/includes/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'kr2060398@gmail.com';   // your Gmail
    $mail->Password   = 'xmrbxzhbchuabof';          // App password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Sender
   $mail->setFrom('kr2060398@gmail.com', 'KS Candles');

    $mail->addReplyTo('inforscandles@gmail.com', 'KS Candles Support');

    // ✅ Add recipient (this was missing)
    $mail->addAddress('kr2060398@gmal.com', 'Ritik Kumar');

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Test Email from PHPMailer';
    $mail->Body    = '<h3>PHPMailer setup successful!</h3><p>Your email configuration is working perfectly 🎉</p>';

    $mail->send();
    echo '✅ Test Email Sent Successfully!';
} catch (Exception $e) {
    echo "❌ Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
