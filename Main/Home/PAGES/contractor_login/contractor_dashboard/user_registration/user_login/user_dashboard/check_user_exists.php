<?php
session_start();

$host = 'localhost';
$dbname = 'home_synergy';
$username = 'root';
$password = '';

try {
    // Connect to the database
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get the parameters sent from the JS request
    $inputUsername = $_POST['username'];
    $inputEmail = $_POST['email'];
    $inputPhone = $_POST['phone'];

    // Prepare the query to check username, email, and phone
    $stmt = $conn->prepare("SELECT * FROM registration WHERE (username = :username OR email = :email OR phone_number = :phone) AND user_id != :user_id");
    $stmt->bindParam(':username', $inputUsername);
    $stmt->bindParam(':email', $inputEmail);
    $stmt->bindParam(':phone', $inputPhone);
    $stmt->bindParam(':user_id', $_SESSION['user_id']);
    $stmt->execute();

    $userExists = $stmt->fetch(PDO::FETCH_ASSOC);

    // Prepare the response
    $response = [
        'usernameExists' => false,
        'emailExists' => false,
        'phoneExists' => false
    ];

    if ($userExists) {
        if ($userExists['username'] == $inputUsername) {
            $response['usernameExists'] = true;
        }
        if ($userExists['email'] == $inputEmail) {
            $response['emailExists'] = true;
        }
        if ($userExists['phone_number'] == $inputPhone) {
            $response['phoneExists'] = true;
        }
    }

    // Send the response back as JSON
    echo json_encode($response);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
