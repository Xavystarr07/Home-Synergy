<?php
session_start(); // Starts the session

include 'database.php';

$error = ""; // Initialize error message variable

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Safely access username and password
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    // Validate username and password are not empty
    if (empty($username) || empty($password)) {
        $error = "Both fields are required.";
    } else {
        // Prepare SQL statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT * FROM registration WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if the query returned any results
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Check if user data exists and validate password key
            if (array_key_exists('password_hash', $user) && !empty(trim($user['password_hash']))) {
                // Verify the password using the correct key
                if (password_verify($password, trim($user['password_hash']))) {
                    // Password is valid
                    $_SESSION['username'] = $username;
                    $_SESSION['logged_in'] = true;

                    // Set a cookie to remember the user (optional, expires in 1 day)
                    setcookie("username", $username, time() + (86400), "/"); 

                    // Redirect to dashboard or homepage
                    header("Location: user_dashboard/user_dashboard.php");
                    exit();
                } else {
                    // Invalid credentials
                    $error = "Invalid username or password.";
                }
            } else {
                $error = "Password not found in database.";
            }
        } else {
            $error = "Username does not exist.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Home Synergy</title>
    <link rel="stylesheet" href="user_login_styles.css">
</head>
<body>
<div class="main_con">
    <div class="logo_place">
        <div class="logo_image"> 
            <a href="/Home_Synergy_Code/Main/Home/PAGES/home.html" class="logo_container">
                <img src="logoHomeSynergy.jpg" alt="Home Synergy Logo" class="Logo">
            </a>
        </div>
    </div>

    <div class="container">
        <form action="user_login.php" method="POST">
            <div class="row">
                <div class="column">
                    <div class="input-box">
                        <span>Username: </span>
                        <input type="text" name="username" placeholder="Enter your username" required>   
                    </div>
                    <div class="input-box">
                        <span>Password: </span>
                        <input type="password" name="password" placeholder="Enter your password" required>
                    </div>
                </div>
            </div>

            <?php if (!empty($error)) { ?>
                <p style="color: red; font-weight: bold; font-size:14px;"><?php echo $error; ?></p>
            <?php } ?>

            <button type="submit" class="btn">Login</button>

            <div class="sign_up">
                <p class="su">Don't have an account? <br><a href="../register.html" class="sign_up">Register now!</a></p>
            </div>
        </form>
    </div>
</div>
</body>
</html>
