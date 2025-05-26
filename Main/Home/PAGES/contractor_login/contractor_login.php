<?php
session_start();
require_once 'database.php'; 

// Checks if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get input values
    $username = isset($_POST['contractor_username']) ? $_POST['contractor_username'] : ''; 
    $password = isset($_POST['contractor_password']) ? $_POST['contractor_password'] : ''; // Plain password entered by the user

    // Prepare and execute the SQL statement
    $stmt = $conn->prepare("SELECT * FROM contractors WHERE contractor_username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Checks if the username exists
    if ($result->num_rows > 0) {
        $contractor = $result->fetch_assoc();

        // Verifies the password (plain password against the hashed password in DB)
        if (password_verify($password, $contractor['contractor_password'])) {
            // Password is correct, set session variables
            $_SESSION['contractor_id'] = $contractor['contractor_id']; // Store contractor_id in session
            $_SESSION['contractor_username'] = $contractor['contractor_username'];
            $_SESSION['contractor_name'] = $contractor['contractor_name'];
            $_SESSION['contractor_surname'] = $contractor['contractor_surname'];
            $_SESSION['contractor_email'] = $contractor['contractor_email'];
            $_SESSION['contractor_phone_number'] = $contractor['contractor_phone_number'];
            $_SESSION['contractor_location'] = $contractor['contractor_location'];
            $_SESSION['contractor_profile_picture'] = $contractor['contractor_profile_picture'];
            $_SESSION['contractor_profession'] = $contractor['contractor_profession'];
            $_SESSION['contractor_experience'] = $contractor['contractor_experience'];
            $_SESSION['contractor_rating'] = $contractor['contractor_rating'];
            
            // Redirect to the dashboard
            header('Location: contractor_dashboard/contractor_dashboard.php');
            exit();
        } else {
            $error = "Invalid password."; // Password doesn't match
        }
    } else {
        $error = "Username not found."; // No such username exists
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="contractor_login.css">
</head>
<body>
    <div class="background">
        <video autoplay muted loop>
            <source src="oceanVid.mp4" type="video/webm">
        </video>
    </div>

    <div class="login-container">
        <h2>Contractor login</h2>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        <form method="POST" action="">
            <label for="username">Username:</label>
            <input type="text" id="username" name="contractor_username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="contractor_password" required>

            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
