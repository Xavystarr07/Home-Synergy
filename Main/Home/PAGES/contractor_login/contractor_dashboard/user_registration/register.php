<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
session_start();

$host = 'localhost';
$db = 'home_synergy';
$user = 'root'; 
$pass = ''; 
$charset = 'utf8mb4';

// Set up PDO for database interaction
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

// Function to check if email, phone, or username already exists
function checkEmail($pdo, $email) {
    $sql = "SELECT COUNT(*) FROM registration WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    return $stmt->fetchColumn() > 0;
}

function checkPhone($pdo, $phone) {
    $sql = "SELECT COUNT(*) FROM registration WHERE phone_number = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$phone]);
    return $stmt->fetchColumn() > 0;
}

function checkUsername($pdo, $username) {
    $sql = "SELECT COUNT(*) FROM registration WHERE username = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username]);
    return $stmt->fetchColumn() > 0;
}

// Handle AJAX request for duplicate checks
if (isset($_POST['checkDuplicates'])) {
    header('Content-Type: application/json');
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';

    $error = '';

    // Check email
    if (checkEmail($pdo, $email)) {
        $error = 'Email already exists.';
    }

    // Check phone
    if (checkPhone($pdo, $phone)) {
        $error = $error ?: 'Phone number already exists.';
    }

    // Respond with error or success
    if ($error) {
        echo json_encode(['error' => $error]);
    } else {
        echo json_encode(['success' => true]);
    }
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $address = $_POST['address'];

    // Check if email exists
    if (checkEmail($pdo, $email)) {
        echo "<script>
            alert('Email already exists');
            // Clear email and phone number fields
            document.getElementById('email').value = '';
            document.getElementById('phone_number').value = '';
        </script>";
        exit;
    }

    // Check if phone number exists
    if (checkPhone($pdo, $phone_number)) {
        echo "<script>
            alert('Phone number already exists');
            // Clear email and phone number fields
            document.getElementById('email').value = '';
            document.getElementById('phone_number').value = '';
        </script>";
        exit;
    }

    // Check if the username exists
    if (checkUsername($pdo, $username)) {
        echo "<script>
            alert('Username already exists, try another');
            window.history.back();  // Go back to the registration form
        </script>";
        exit;
    }

// Validate username length
if (strlen($username) < 4 || strlen($username) > 20) {
    echo "<script>
        alert('Username must be between 4 and 20 characters long.');
        window.history.back();  // Go back to the registration form
    </script>";
    exit;
}

    // Validate password
    $password_pattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,12}$/";
    if (!preg_match($password_pattern, $password)) {
        echo "<script>
            alert('Password must be 8-12 characters long, include at least one uppercase letter, one lowercase letter, one number, and one symbol.');
            window.history.back();  // Go back to the registration form
        </script>";
        exit;
    }

    // Handle password hashing
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    // Insert user details into the database
    $sql = "INSERT INTO registration (name, surname, email, phone_number, username, password_hash, address)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute([$name, $surname, $email, $phone_number, $username, $password_hash, $address]);

        // Set session variables for the user
        $_SESSION['username'] = $username;
        $_SESSION['name'] = $name;
        $_SESSION['surname'] = $surname;
        $_SESSION['email'] = $email;
        $_SESSION['phone'] = $phone_number;
        $_SESSION['address'] = $address;

        // Respond with success message (as a JS alert)
        echo "<script>
            alert('Registration was successful, entering the dashboard...');
            window.location.href = '/Home_Synergy_Code/Main/Home/PAGES/contractor_login/contractor_dashboard/user_registration/user_login/user_dashboard/user_dashboard.php';
        </script>";

    } catch (\PDOException $e) {
        echo "<script>alert('Database Error: " . $e->getMessage() . "');</script>";
    }
}
?>
