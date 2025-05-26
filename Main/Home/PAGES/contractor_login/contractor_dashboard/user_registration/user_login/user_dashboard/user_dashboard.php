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

    // Get user information from the registration table using the session username
    $stmt = $conn->prepare("SELECT user_id, name, surname, email, phone_number, address, profile_picture FROM registration WHERE username = :username");
    $stmt->bindParam(':username', $_SESSION['username']);
    $stmt->execute();

    // Fetch user details if they exist
    if ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $_SESSION['user_id'] = $user['user_id']; // Store user_id in session
        $_SESSION['name'] = $user['name'];
        $_SESSION['surname'] = $user['surname'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['address'] = $user['address'];
        $_SESSION['phone_number'] = $user['phone_number'];

        // Check if profile_picture is NULL or empty, set default
        $_SESSION['profile_picture'] = !empty($user['profile_picture']) ? $user['profile_picture'] : 'default_user.png';
    }

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Store user details in variables
$name = isset($_SESSION['name']) ? $_SESSION['name'] : '';
$surname = isset($_SESSION['surname']) ? $_SESSION['surname'] : '';
$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
$address = isset($_SESSION['address']) ? $_SESSION['address'] : '';
$phone = isset($_SESSION['phone_number']) ? $_SESSION['phone_number'] : '';
$profile_picture = isset($_SESSION['profile_picture']) ? $_SESSION['profile_picture'] : 'default_user.png';

// Fetch appointments for the logged-in user
$user_id = $_SESSION['user_id']; // Assume user ID is stored in the session
$query = "SELECT * FROM appointments WHERE user_id = :user_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();

// Fetch all appointments
$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Pass appointments data to JavaScript
echo "<script>var appointments = " . json_encode($appointments) . ";</script>";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
    <script src="dashboard.js" defer></script> <!-- Added defer to ensure the script loads after HTML -->
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <h2>Dashboard</h2>
            <ul>
                <li><a href="#" id="profile-btn">Profile</a></li>
<li><a href="#" id="bookings-btn">Bookings</a></li>
<li><a href="#" id="notifications-btn">Notifications</a></li>
<li><a href="Home Improvements/hi.html" id="home-improvements--btn">Home Improvements</a></li>
<li><a href="Home Insurance/Insurance.html" id="home-insurance-btn">Home Insurance</a></li>
<li><a href="user_logout.php" id="logout-btn">Logout</a></li>

            </ul>
        </aside>

        <main class="main-content">
            <header>
                <div class="profile-section">
                    <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture" class="profile-pic">
                    <p class="username"><?php echo htmlspecialchars($name . ' ' . $surname); ?></p>
                </div>
                <h1>Welcome, <?php echo htmlspecialchars($name); ?></h1>
            </header>
 <script>
    document.addEventListener('DOMContentLoaded', function() {
        const welcomeMessage = document.querySelector('h1');

        setTimeout(() => {
            welcomeMessage.style.opacity = 1;
            const fadeOutInterval = setInterval(() => {
                welcomeMessage.style.opacity -= 0.1;
                if (welcomeMessage.style.opacity <= 0) {
                    clearInterval(fadeOutInterval);
                    welcomeMessage.style.display = 'none';
                }
            }, 50);
        }, 1000);
    });
</script>
            <!-- Service Section -->
            <section class="service-section">
                <h2>Select a service</h2>
                <div class="service-grid">
                    <div class="service-card plumbing-container" data-service-type="plumbing">
                        <h3>Plumbing</h3>
                        <img src="plumbing.png" alt="Plumbing Service">
                        <p>Fix leaks, install pipes, and more.</p>
                    </div>
                    <div class="service-card electrical-container" data-service-type="electrical">
                        <h3>Electrical</h3>
                        <img src="electrical.png" alt="Electrician Service">
                        <p>Handle electrical issues and installations.</p>
                    </div>
                    <div class="service-card carpentry-container" data-service-type="carpentry">
                        <h3>Carpentry</h3>
                        <img src="carpentry.png" alt="Carpentry Service">
                        <p>Woodwork, furniture, and repairs.</p>
                    </div>
                    <div class="service-card painting-container" data-service-type="painting">
                        <h3>Painting</h3>
                        <img src="painting.png" alt="Painter Service">
                        <p>Painting, decoration, and finishing.</p>
                    </div>
                </div>
            </section>

<!-- Contractor Section -->
<section class="contractor-section" id="contractor-section" style="display: none;">
    <h2 id="contractor-title"></h2> <!-- Change to have an ID for dynamic update -->
    <div class="contractor-grid" id="contractor-container">
	
        <!-- Contractor cards will be dynamically loaded here -->
    </div>
    <button id="back-to-services-btn">Back to services</button>
</section>

            
            <!-- Appointment Section -->
<section class="appointment-section" id="appointment-section" style="display: none;">
    <form id="appointment-form" method="POST"> 
        <h2>Appointment details</h2>
		<input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>" />
        <label for="appointment-date">Date:</label>
        <input type="date" id="appointment-date" name="appointment-date" required>

        <label for="appointment-time">Time:</label>
        <input type="time" id="appointment-time" name="appointment-time" required>

        <label for="location">Location:</label>
        <input type="text" id="location" name="location" placeholder="Enter appointment location" required>

        <label for="details">Details:</label>
        <textarea id="details" name="details" rows="4" placeholder="Enter appointment details"></textarea>

        <button type="submit" name="submit" id="submit">Request appointment</button>
    </form>
    <div id="confirmation-message"></div>
    <button id="back-to-contractors-btn">Back to contractors</button>
</section>


            <!-- Profile Section -->

<section class="profile-details" id="profile-details" style="display: none;">
    <h2>User Details</h2>
    <div class="profile-update">

        <form id="profile-update-form" method="POST" action="profile_update.php" onsubmit="return validateForm()" enctype="multipart/form-data">
			
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" required>

            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($_SESSION['name']); ?>" required>

            <label for="surname">Surname:</label>
            <input type="text" id="surname" name="surname" value="<?php echo htmlspecialchars($_SESSION['surname']); ?>" required>

            <label for="address">Address:</label>
            <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($_SESSION['address']); ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_SESSION['email']); ?>" required>

            <label for="phone">Phone number:</label>
            <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($_SESSION['phone_number']); ?>" required>

            <button type="submit" id="update-profile-btn">Update your profile</button>
        </form>
    </div>
	
                <button id="back-to-dashboard-btn" class="back-button">Back to dashboard</button>
</section>

<script>
    function validateForm() {
    // Get form values
    var name = document.getElementById("name").value;
    var surname = document.getElementById("surname").value;
    var username = document.getElementById("username").value;
    var address = document.getElementById("address").value;
    var email = document.getElementById("email").value;
    var phone = document.getElementById("phone").value;

    var namePattern = /^[A-Za-z]+$/; // Letters only for name and surname
    var phonePattern = /^0\d{9}$/; // Phone should start with 0 and have 10 digits

    // Validation for Name (at least 2 characters)
    if (name.length < 2) {
        alert("Name should be at least 2 characters.");
        return false;
    }
    if (!namePattern.test(name)) {
        alert("Name should contain only letters.");
        return false;
    }

    // Validation for Surname (at least 2 characters)
    if (surname.length < 2) {
        alert("Surname should be at least 2 characters.");
        return false;
    }
    if (!namePattern.test(surname)) {
        alert("Surname should contain only letters.");
        return false;
    }

    // Validation for Username (between 4 and 20 characters)
    if (username.length < 4 || username.length > 20) {
        alert("Username should be between 4 and 20 characters.");
        return false;
    }

    // Email Validation (Basic format check)
    var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
    if (!emailPattern.test(email)) {
        alert("Please enter a valid email address.");
        return false;
    }

    // Phone Validation
    if (!phonePattern.test(phone)) {
        alert("Phone number must start with '0' and be 10 digits long.");
        return false;
    }

    // Ajax request to check username, email, and phone existence
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "check_user_exists.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var response = JSON.parse(xhr.responseText);
            var alertMessage = '';

            if (response.usernameExists) {
                alertMessage += "Username already exists, try another.\n";
            }
            if (response.emailExists) {
                alertMessage += "Email already exists.\n";
            }
            if (response.phoneExists) {
                alertMessage += "Phone number already exists.\n";
            }

            if (alertMessage != '') {
                alert(alertMessage);
                return false; // Prevent form submission
            } else {
                // Manually submit the form after AJAX validation
                document.getElementById("update-profile-btn").form.submit(); // Submit the form
            }
        }
    };

    // Send the AJAX request with user input data
    xhr.send("username=" + username + "&email=" + email + "&phone=" + phone);
    return false; // Prevent the form from submitting immediately while we check
}


</script>

            <!-- Bookings Section -->
            <section class="bookings-section" id="bookings-section" style="display: none;">
                <h2>Your bookings</h2>
                <div id="booking-details">
				
                    <!-- Booking details will be populated here -->
                </div>
                <button id="back-to-dashboard-btn-bookings">Back to dashboard</button>
            </section>

            <section class="notifications" id="notifications-section" style="display: none;">
    <h2>Notification</h2>
    <p>Your appointment details have been sent to the contractor.</p>
   
    <div class="appointment-box"> <!-- Appointment details will be appended here --> </div>
</section>

        </main>
    </div>
</body>
</html>
