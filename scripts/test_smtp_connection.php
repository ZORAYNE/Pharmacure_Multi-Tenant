<?php
// Simple script to test SMTP connection using SwiftMailer

require __DIR__ . '/../vendor/autoload.php';

$transport = (new Swift_SmtpTransport('smtp.gmail.com', 587, 'tls'))
  ->setUsername('kithalforque@gmail.com')
  ->setPassword('oxow wjdz adle ivnc');

$mailer = new Swift_Mailer($transport);

$message = (new Swift_Message('Test SMTP Connection'))
  ->setFrom(['kithalforque@gmail.com' => 'Test'])
  ->setTo(['kithalforque@gmail.com'])
  ->setBody('This is a test email to verify SMTP connection.');

try {
    $result = $mailer->send($message);
    echo "Email sent successfully.\n";
} catch (Exception $e) {
    echo "Failed to send email: " . $e->getMessage() . "\n";
}
?>
