<?php
// Database configuration
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "home_synergy"; 

// Creates connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Checks connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
