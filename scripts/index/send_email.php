<?php
require_once __DIR__ . '/../../vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

// Debug: Print environment variables
error_log("Email sending process started");
error_log("GMAIL_USERNAME: " . ($_ENV['GMAIL_USERNAME'] ?? 'not set'));
error_log("GMAIL_PASSWORD: " . ($_ENV['GMAIL_PASSWORD'] ? 'set' : 'not set'));

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

try {
    // Get the message from POST data
    if (isset($_POST['message'])) {
        $message = stripslashes($_POST['message']);
        $message = html_entity_decode($message, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        error_log("Message received: " . $message);
    } else {
        $message = 'No message provided.';
        error_log("No message provided in POST data");
    }

    $mail = new PHPMailer(true);

    // Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = $_ENV['GMAIL_USERNAME'];
    $mail->Password = $_ENV['GMAIL_PASSWORD'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Debug SMTP settings
    error_log("SMTP settings configured");
    error_log("Host: smtp.gmail.com");
    error_log("Username: " . $_ENV['GMAIL_USERNAME']);
    error_log("Port: 587");

    // Recipients
    $mail->setFrom($_ENV['GMAIL_USERNAME'], 'Bugkat');
    $mail->addAddress('saidbugrak@gmail.com');

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'New Message from Bugkat';
    $mail->Body = $message;

    error_log("Attempting to send email...");
    $mail->send();
    error_log("Email sent successfully!");
    echo "Email sent successfully!";
} catch (Exception $e) {
    error_log("Email sending failed: " . $e->getMessage());
    error_log("Error details: " . $e->getTraceAsString());
    echo "Email could not be sent. Error: " . $e->getMessage();
}
?>