<?php
session_start();
require_once 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve job ID and new status from the request
    $jobId = isset($_POST['id']) ? intval($_POST['id']) : 0; // Ensure it's an integer
    $status = isset($_POST['status']) ? $_POST['status'] : 'pending';

    // Log incoming data for debugging
    error_log("Job ID: " . $jobId . " | Status: " . $status);

    // Prepare SQL statement to update the job status
    $stmt = $conn->prepare("UPDATE appointments SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $jobId);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo "Job status updated successfully."; // Confirm change
        } else {
            echo "No changes made. Job ID may not exist or status is already set."; // Handle no changes
        }
    } else {
        // Log error if update fails
        error_log("SQL Error: " . $stmt->error);
        echo "Error updating job status: " . $conn->error;
    }
    $stmt->close();
}
?>
