<?php

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

// Checks if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $profession = $_POST['profession'];
    $location = $_POST['location'];
    $contactMethod = $_POST['contactMethod'];
    $contactInfo = $_POST['contactInfo'];

    // Handle file upload for CV
    $cvPath = '';
    if (isset($_FILES['cv']) && $_FILES['cv']['error'] == 0) {
        $cvPath = 'application_uploads/' . basename($_FILES['cv']['name']);
        move_uploaded_file($_FILES['cv']['tmp_name'], $cvPath);
    }

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO application_forms (name, surname, profession, location, contact_method, contact_info, cv_path) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $name, $surname, $profession, $location, $contactMethod, $contactInfo, $cvPath);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Your application has been submitted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
}
$conn->close();
?>
