<?php
session_start();

// If the user is not logged in, redirect to the login page
if (!isset($_SESSION['username'])) {
    header('Location: ../user_login.php');
    exit();
}

// Set a cookie for 1 hour to maintain session
$cookieExpireTime = time() + 3600; // 1 hour from now
setcookie('user_session', session_id(), $cookieExpireTime, '/', '', false, true);

// Database connection
$host = 'localhost';
$dbname = 'home_synergy';
$username = 'root';
$password = '';

try {
    // Connect to the database
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get the updated details from the form
        $newUsername = $_POST['username']; // New username
        $name = $_POST['name']; // New name (first name)
        $surname = $_POST['surname'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $phone = $_POST['phone'];

        // Default profile picture if none is uploaded
        $profilePic = isset($_SESSION['profile_picture']) ? $_SESSION['profile_picture'] : ''; // Ensure it's set

        // Check if username, email, or phone number already exists in the database
        $stmt = $conn->prepare("SELECT * FROM registration WHERE (username = :username OR email = :email OR phone_number = :phone) AND user_id != :user_id");
        $stmt->bindParam(':username', $newUsername);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->execute();

        $userExists = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($userExists) {
            // If any of the username, email, or phone exists, show an error message
            $alertMessage = '';
            if ($userExists['username'] == $newUsername) {
                $alertMessage .= "Username already exists, try another.\n";
            }
            if ($userExists['email'] == $email) {
                $alertMessage .= "Email already exists.\n";
            }
            if ($userExists['phone_number'] == $phone) {
                $alertMessage .= "Phone number already exists.\n";
            }
            echo "<script>alert('$alertMessage'); window.location.href = 'user_dashboard.php';</script>";
        } else {
            // Prepare the update query
            $updateQuery = "UPDATE registration SET username = :username, name = :name, surname = :surname, email = :email, address = :address, phone_number = :phone, profile_picture = :profile_picture WHERE user_id = :user_id";
            $stmt = $conn->prepare($updateQuery);

            // Bind parameters
            $stmt->bindParam(':username', $newUsername);
            $stmt->bindParam(':name', $name); // Bind name
            $stmt->bindParam(':surname', $surname);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':profile_picture', $profilePic); // Keep current profile picture or empty
            $stmt->bindParam(':user_id', $_SESSION['user_id']);

            // Execute the update query
            if ($stmt->execute()) {
                // Update session variables with the new values
                $_SESSION['username'] = $newUsername;
                $_SESSION['name'] = $name; // Update name in session
                $_SESSION['surname'] = $surname;
                $_SESSION['email'] = $email;
                $_SESSION['address'] = $address;
                $_SESSION['phone_number'] = $phone;
                $_SESSION['profile_picture'] = $profilePic; // Keep profile picture session updated

                // JavaScript to alert the user and check if the profile picture was updated
                echo "<script>
                        alert('Your profile has been updated successfully');
                        window.location.href = 'user_dashboard.php';
                      </script>";
            } else {
                // If update fails, show error
                echo "<script>alert('Failed to update profile. Please try again later.'); window.location.href = 'user_dashboard.php';</script>";
            }
        }
    }

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

?>