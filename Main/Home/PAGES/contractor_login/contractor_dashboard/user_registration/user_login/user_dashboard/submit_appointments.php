<?php
session_start();
require_once 'database.php'; 

ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $contractor_id = $_POST['contractor_id'];
    $contractor_name = $_POST['contractor_name'];
    $contractor_surname = $_POST['contractor_surname'];
    $contractor_profession = $_POST['contractor_profession'];
    $appointment_date = $_POST['appointment-date'];
    $appointment_time = $_POST['appointment-time'];
    $location = $_POST['location'];
    $details = $_POST['details'];
    $created_at = date('Y-m-d H:i:s');
    $user_id = $_SESSION['user_id'] ?? null;

    if (empty($contractor_id) || empty($contractor_name) || empty($contractor_surname) || empty($appointment_date) || empty($appointment_time) || empty($user_id)) {
        echo json_encode(["status" => "error", "message" => "All fields are required."]);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO appointments (contractor_id, contractor_name, contractor_surname, contractor_profession, appointment_date, appointment_time, location, details, created_at, user_id) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssssss", $contractor_id, $contractor_name, $contractor_surname, $contractor_profession, $appointment_date, $appointment_time, $location, $details, $created_at, $user_id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Your booking request has been successfully sent to the contractor."]);
    } else {
        error_log("SQL Error: " . $stmt->error);
        echo json_encode(["status" => "error", "message" => "Error submitting request."]);
    }
}
?>
