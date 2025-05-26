<?php
// Check if session is already started
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start the session if it hasn't been started yet
}

// Initialize the errors array
$errors = [];

// Database connection details
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "home_synergy"; 

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if (isset($_POST['change-password'])) {
    // Retrieve the password and confirm password
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];

    // Validate passwords
    if (empty($password) || empty($cpassword)) {
        $errors[] = "Both fields are required.";
    } elseif ($password !== $cpassword) {
        $errors[] = "Passwords do not match.";
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Update the password in the database
        $email = $_SESSION['email']; // Assuming email is stored in session
        
        $stmt = $conn->prepare("UPDATE registration SET password_hash = ? WHERE email = ?");
        $stmt->bind_param("ss", $hashedPassword, $email);

        if ($stmt->execute()) {
            $_SESSION['info'] = "Password changed successfully!";
            header('Location: /Home_Synergy_Code/Main/Home/PAGES/contractor_login/contractor_dashboard/user_registration/user_login/user_login.php');
exit();

        } else {
            $errors[] = "Error changing password. Please try again.";
        }
        
        $stmt->close();
    }
}

$conn->close(); // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create a New Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4 form">
                <form action="new-password.php" method="POST" autocomplete="off">
                    <h2 class="text-center">New Password</h2>
                    <?php 
                    if (isset($_SESSION['info'])) {
                        ?>
                        <div class="alert alert-success text-center">
                            <?php echo $_SESSION['info']; ?>
                        </div>
                        <?php
                        unset($_SESSION['info']); // Clear the message after displaying it
                    }
                    ?>
                    <?php
                    if (count($errors) > 0) {
                        ?>
                        <div class="alert alert-danger text-center">
                            <?php
                            foreach ($errors as $showerror) {
                                echo $showerror;
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="form-group">
                        <input class="form-control" type="password" name="password" placeholder="Create new password" required>
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="password" name="cpassword" placeholder="Confirm your password" required>
                    </div>
                    <div class="form-group">
                        <input class="form-control button" type="submit" name="change-password" value="Change">
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>