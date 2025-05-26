<?php
// Start the session
session_start();

// Unset all of the session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Redirect to the user login page
header("Location: /Home_Synergy_Code/Main/Home/PAGES/contractor_login/contractor_dashboard/user_registration/user_login/user_login.php");
exit(); // Ensure no further code is executed after the redirect
?>
