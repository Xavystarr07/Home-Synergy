<?php
session_start(); // Start the session
session_unset(); // Clear all session variables (optional, but good practice)
session_destroy(); // End the session
header('Location: User_login/login.html'); // Redirect to the login page
exit(); // Ensure no further code is executed
?>
