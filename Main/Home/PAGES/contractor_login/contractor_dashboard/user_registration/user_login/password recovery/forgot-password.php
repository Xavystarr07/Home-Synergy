<?php
session_start(); // Start the session

require_once "send_email.php"; 

// Check if form is submitted
if (isset($_POST['check-email'])) {
    $email = $_POST['email']; // Get the email from the input
    $_SESSION['email'] = $email; // Store the email in the session

    // Call the function to generate and send the verification code
    generateAndSendVerificationCode($email);

    // Redirect to reset-code.php after sending the email
    header('Location: reset-code.php');
    exit(); // Stop further execution
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4 form">
                <form action="send_email.php" method="POST" autocomplete="">
                    <h3 class="text-center">Password recovery</h3>
                    <p class="text-center">🔐</p>
                    <div class="form-group">
                        <input class="form-control" type="email" name="email" placeholder="Enter your email address" required>
                    </div>
                    <div class="form-group">
                        <input class="form-control button" type="submit" name="check-email" value="Continue">
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>