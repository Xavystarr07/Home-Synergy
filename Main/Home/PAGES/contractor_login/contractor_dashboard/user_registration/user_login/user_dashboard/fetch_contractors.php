<?php
// Start the session at the beginning
session_start();

// Database connection parameters
$host = 'localhost';
$db_name = 'home_synergy';
$username = 'root';
$password = '';

// Create a connection
$conn = new mysqli($host, $username, $password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the service type from the query parameter
$serviceType = isset($_GET['serviceType']) ? $_GET['serviceType'] : '';

// Store the selected service type in session for later use
$_SESSION['serviceType'] = $serviceType;

// Prepare the SQL query based on the service type
switch ($serviceType) {
    case "plumbing":
        $sql = "SELECT contractor_id, contractor_profile_picture, contractor_name, contractor_surname, contractor_profession, contractor_rating, contractor_experience FROM contractors WHERE contractor_profession = 'plumbing'";
        break;
    case "electrical":
        $sql = "SELECT contractor_id, contractor_profile_picture, contractor_name, contractor_surname, contractor_profession, contractor_rating, contractor_experience FROM contractors WHERE contractor_profession = 'electrical'";
        break;
    case "carpentry":
        $sql = "SELECT contractor_id, contractor_profile_picture, contractor_name, contractor_surname, contractor_profession, contractor_rating, contractor_experience FROM contractors WHERE contractor_profession = 'carpentry'";
        break;
    case "painting":
        $sql = "SELECT contractor_id, contractor_profile_picture, contractor_name, contractor_surname, contractor_profession, contractor_rating, contractor_experience FROM contractors WHERE contractor_profession = 'painting'";
        break;
    default:
        // If no valid service type is selected, fetch all contractors
        $sql = "SELECT contractor_id, contractor_profile_picture, contractor_name, contractor_surname, contractor_profession, contractor_rating, contractor_experience FROM contractors";
}

// Execute the query
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

// Prepare an array to store contractor details
$contractors = array();
$defaultProfilePicture = 'http://localhost/default_profile.png'; // Set default profile picture URL

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Correctly reference the contractor_profile_picture field
        $row['contractor_profile_picture'] = isset($row['contractor_profile_picture']) && !empty($row['contractor_profile_picture']) 
            ? 'http://localhost/Home_Synergy_Code/Main/Home/PAGES/contractor_login/contractor_dashboard/user_registration/user_login/user_dashboard/contractor_uploads_user/' . $row['contractor_profile_picture'] 
            : $defaultProfilePicture; // Use default if profile picture is NULL or empty
        
        // Add contractor data to the array
        $contractors[] = $row; 
    }
}

// Return the contractors as JSON
header('Content-Type: application/json'); // Set the header for JSON response
echo json_encode($contractors);

// Close the connection
$conn->close();
?>