<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); 
}
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Load Composer's autoloader

if (isset($_POST['check-email'])) {
    $email = $_POST['email'];

    // Generate a verification code
    $verification_code = rand(100000, 999999); // Generate a 6-digit code
    $_SESSION['verification_code'] = $verification_code; // Store it in the session
    $_SESSION['email'] = $email; // Store email for later use

    // Prepare the email content
    $subject = "Password Reset Code";
    $body = "Your verification code is: $verification_code";

    // Send the email
    if (sendEmail($email, $subject, $body)) {
        $_SESSION['info'] = "Verification code sent to $email";
        header('Location: reset-code.php'); // Redirect to code verification page
        exit();
    } else {
        $_SESSION['info'] = "Failed to send verification code. Please try again.";
        header('Location: forgot-password.php'); // Redirect back to forgot password
        exit();
    }
}

function sendEmail($to, $subject, $body) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'xavierchetty07@gmail.com'; 
        $mail->Password   = 'imtgguooltxmvcph'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('xavierchetty07@gmail.com', 'Home Synergy');
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        return true; // Return true if the email is sent
    } catch (Exception $e) {
        return false; // Return false if there's an error
    }
}
?>