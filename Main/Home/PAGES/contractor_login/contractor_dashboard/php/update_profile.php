<?php
session_start();
include('database.php');

// Ensure the contractor is logged in
if (!isset($_SESSION['contractor_username'])) {
    die("You must be logged in to update your profile.");
}

$contractor_username = $_POST['contractor_username'];
$contractor_password = $_POST['contractor_password'];

// Hash the password
$hashed_password = password_hash($contractor_password, PASSWORD_DEFAULT);

// Update contractor's details in the database
$sql = "UPDATE contractors SET contractor_username = ?, contractor_password = ? WHERE contractor_username = ?";
$stmt = $conn->prepare($sql);

// Bind parameters: 3 parameters for 3 placeholders
$stmt->bind_param("sss", $contractor_username, $hashed_password, $_SESSION['contractor_username']);

// Execute the query
if ($stmt->execute()) {
    // Update session variable with the new username
    $_SESSION['contractor_username'] = $contractor_username;
    echo "<script>alert('Your profile has been updated successfully'); window.location.href = '../contractor_dashboard.php';</script>";
} else {
    echo "Error updating profile: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
