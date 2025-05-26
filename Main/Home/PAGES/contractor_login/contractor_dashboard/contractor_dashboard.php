<?php
session_start();
require_once 'database.php';

// Check if the user is logged in
if (!isset($_SESSION['contractor_username']) || !isset($_SESSION['contractor_id'])) {
    header("Location: ../contractor_login.php"); // Redirect to login if not logged in
    exit();
}

// Query to fetch contractor details from the database
$query_contractor = "SELECT contractor_name, contractor_surname FROM contractors WHERE contractor_id = ?";
$stmt_contractor = $conn->prepare($query_contractor);
$stmt_contractor->bind_param("i", $contractor_id);
$stmt_contractor->execute();
$result_contractor = $stmt_contractor->get_result();

// Fetch contractor name and surname
if ($row = $result_contractor->fetch_assoc()) {
    $contractor_name = $row['contractor_name'];
    $contractor_surname = $row['contractor_surname'];
} else {
    // Handle case where contractor doesn't exist
    $contractor_name = 'N/A';
    $contractor_surname = 'N/A';
}

// Retrieve contractor details from the session
$contractor_id = $_SESSION['contractor_id']; // Ensure contractor_id is stored in session during login
$contractor_username = $_SESSION['contractor_username'];
$contractor_name = $_SESSION['contractor_name'];
$contractor_surname = $_SESSION['contractor_surname'];
$contractor_email = $_SESSION['contractor_email'] ?? 'N/A';
$contractor_phone_number = $_SESSION['contractor_phone_number'] ?? 'N/A'; 
$contractor_location = $_SESSION['contractor_location'] ?? 'N/A'; 
$contractor_profession = $_SESSION['contractor_profession'] ?? 'N/A'; 
$contractor_profile_picture = $_SESSION['contractor_profile_picture'] ?? 'N/A';
$contractor_experience = $_SESSION['contractor_experience'] ?? 'N/A'; 
$contractor_rating = $_SESSION['contractor_rating'] ?? 'N/A';

// Query for pending jobs
$query_pending = "SELECT * FROM appointments WHERE contractor_id = ? AND status = 'pending' ORDER BY id";
$stmt_pending = $conn->prepare($query_pending);
$stmt_pending->bind_param("i", $contractor_id); // Using "i" for integer
$stmt_pending->execute();
$result_pending = $stmt_pending->get_result();
$pending_jobs = $result_pending->fetch_all(MYSQLI_ASSOC); // Fetch all pending jobs

// Query for accepted jobs
$query_accepted = "SELECT * FROM appointments WHERE contractor_id = ? AND status = 'accepted'";
$stmt_accepted = $conn->prepare($query_accepted);
$stmt_accepted->bind_param("i", $contractor_id); // Using "i" for integer
$stmt_accepted->execute();
$result_accepted = $stmt_accepted->get_result();
$accepted_jobs = $result_accepted->fetch_all(MYSQLI_ASSOC); // Fetch accepted jobs
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contractor Dashboard</title>
    <link rel="stylesheet" href="css/contractor_dashboard.css">
	<script src="js/contractor_dashboard.js" defer></script>
</head>
<body>
    <div class="sidebar">
        <h2>Dashboard</h2>
        <ul>
            <li><a href="#" onclick="showSection('jobs')">JOBS AVAILABLE</a></li>
            <li><a href="#" onclick="showSection('profile')">PROFILE</a></li>
            <li><a href="#" onclick="showSection('appointments')">APPOINTMENTS</a></li>
            <li><a href="#" onclick="showSection('notifications')">NOTIFICATIONS</a></li>
            <li><a href="#" onclick="showSection('logout')">LOGOUT</a></li>
        </ul>
    </div>

  <!-- Jobs Available Section -->
<section id="jobs" class="section active">
    <h3>Jobs Available</h3>
    <?php
    if (count($pending_jobs) > 0) {
        foreach ($pending_jobs as $job) {
    ?>
    <div class="job-card jobcard<?= ($job['id'] % 9) + 1 ?>" id="job_<?= $job['id'] ?>" data-id="<?= $job['id'] ?>">
        <p><strong>User:</strong> <?= htmlspecialchars($job['user_id']) ?></p>
        <p><strong>Location:</strong> <?= htmlspecialchars($job['location']) ?></p>
        <p><strong>Service:</strong> <?= htmlspecialchars($job['contractor_profession']) ?></p>
        <p><strong>Date:</strong> <?= htmlspecialchars($job['appointment_date']) ?></p>
        <p><strong>Time:</strong> <?= htmlspecialchars($job['appointment_time']) ?></p>
        <p><strong>Details:</strong> <?= htmlspecialchars($job['details']) ?></p>

        <!-- Accept job button -->
        <form method="POST" action="" id="jobFormAccept_<?= $job['id'] ?>" onsubmit="return handleJobStatus(event, <?= $job['id'] ?>, 'accepted')">
            <input type="hidden" name="id" value="<?= $job['id'] ?>">
            <input type="hidden" name="status" value="accepted">
            <button type="button" id="acceptBtn_<?= $job['id'] ?>" class="accept-btn" onclick="acceptJob(<?= $job['id'] ?>)">✔ Accept</button>
        </form>

        <!-- Decline job button -->
        <form method="POST" action="" id="jobFormDecline_<?= $job['id'] ?>" onsubmit="return handleJobStatus(event, <?= $job['id'] ?>, 'rejected')">
            <input type="hidden" name="id" value="<?= $job['id'] ?>">
            <input type="hidden" name="status" value="rejected">
            <button type="button" id="declineBtn_<?= $job['id'] ?>" class="decline-btn" onclick="declineJob(<?= $job['id'] ?>)">✘ Decline</button>
        </form>


    </div>
    <?php
        }
    } else {
        echo '<p class="no-jobs-message1">Jobs available</p><br>';
        echo '<p class="no-jobs-message">No jobs available at the moment.</p>';
    }
    ?>
</section>


    <!-- Profile Section -->
    <section id="profile" class="section">
        <h3>Profile Information</h3>
        <div class="profile-details">
            <?php 
                // Set a default profile image path if $contractor_profile_picture is empty or not set
                $profileImage = 'default_profile.png';
            ?>
            <img src="<?= $profileImage ?>" alt="Profile Picture" class="profile-pic">
            
            <h4><?= htmlspecialchars($contractor_username) ?></h4>
            <p>Name: <?= htmlspecialchars($contractor_name) ?> <?= htmlspecialchars($contractor_surname) ?></p>
            <p>Email: <?= htmlspecialchars($contractor_email) ?></p>
            <p>Phone Number: <?= htmlspecialchars($contractor_phone_number) ?></p>
            <p>Location: <?= htmlspecialchars($contractor_location) ?></p>
            <p>Profession: <?= htmlspecialchars($contractor_profession) ?></p>
            <p>Experience: <?= htmlspecialchars($contractor_experience) ?> Years</p>
            <p>Rating: 
                <span class="rating"><?= str_repeat('★', round($contractor_rating)) . str_repeat('☆', 5 - round($contractor_rating)) ?></span>
            </p>
        </div>
       <form id="profile-update-form" action="php/update_profile.php" method="POST" enctype="multipart/form-data" class="profile-update-form">
    <h4>Login update</h4>
    <input type="text" name="contractor_username" id="contractor_username" placeholder="Username" value="<?= htmlspecialchars($contractor_username) ?>" required>
    <input type="password" name="contractor_password" id="contractor_password" placeholder="Password" required>
    <button type="submit" class="update-btn" id="update_btn">Update Profile</button>
</form>
<script>
document.getElementById("profile-update-form").addEventListener("submit", function(event) {
    var username = document.getElementById("contractor_username").value;
    var password = document.getElementById("contractor_password").value;
    
    // Validate username length
    if (username.length < 4 || username.length > 20) {
        alert("Username must be between 4 and 20 characters.");
        event.preventDefault();
        return;
    }

    // Check if username already exists (via check_exist.php)
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "php/check_exist.php?username=" + username, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            if (xhr.responseText == "exists") {
                alert("Username already exists.");
                event.preventDefault();
                return;
            }
        }
    };
    xhr.send();

    // Validate password
    var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,12}$/;
    if (!passwordRegex.test(password)) {
        alert("Password must be between 8 and 12 characters, include uppercase, lowercase, number, and symbol.");
        event.preventDefault();
        return;
    }
});
</script>
    </section>

    <!-- Appointments Section -->
<section id="appointments" class="section">
    <h3>Appointments</h3>
    <div id="appointments-list">
        <?php
        if (count($accepted_jobs) > 0) {
            foreach ($accepted_jobs as $appointment) {
                // Check if the payment status is 'paid' and add a class
                $jobClass = ($appointment['payment_status'] == 'paid') ? 'paid-appointment' : 'unpaid-appointment';
                
                echo '<div class="job-card ' . $jobClass . '" id="job_' . $appointment['id'] . '">';
                echo '<p><strong>User ID:</strong> ' . htmlspecialchars($appointment['user_id']) . '</p>';
                echo '<p><strong>Location: </strong> ' . htmlspecialchars($appointment['location']) . '</p>';
                echo '<p><strong>Service: </strong> ' . htmlspecialchars($appointment['contractor_profession']) . '</p>';
                echo '<p><strong>Date: </strong>' . htmlspecialchars($appointment['appointment_date']) . '</p>';
                echo '<p><strong>Time: </strong> ' . htmlspecialchars($appointment['appointment_time']) . '</p>';
                echo '<p><strong>Details: </strong> ' . htmlspecialchars($appointment['details']) . '</p>';
                echo '<p><strong>Payment status: </strong>' . htmlspecialchars($appointment['payment_status']) . '</p>';
                echo '</div>';
            }
        } else {
            echo '<p class="no-appointments-message1">Appointments</p><br>';
            echo '<p class="no-appointments-message">No accepted appointments.</p>';
        }
        ?>
    </div>
</section>



    <!-- Notifications Section -->
<section id="notifications" class="section">
    <h3>Notifications</h3>
    <div id="notifications-list">
        <p id="notification-message">No notifications available.</p>
    </div>
</section>


    <!-- Logout Section -->
    <section id="logout" class="section">
        <p>Are you sure you want to log out?</p>
        <a href="\Home_Synergy_Code\Main\Home\PAGES\contractor_login\contractor_dashboard\contractor_logout.php" class="logout-btn">Log Out</a>
    </section>
	
	<script>
	// Toggle visibility for notifications
document.getElementById('show-notifications').addEventListener('click', function () {
    const notiSection = document.getElementById('notification-section');
    toggleVisibility(notiSection);
});

// Toggle visibility for logout
document.getElementById('show-logout').addEventListener('click', function () {
    const logoutSection = document.getElementById('logout-section');
    toggleVisibility(logoutSection);
});

// Utility function to toggle visibility
function toggleVisibility(element) {
    // Ensure only the desired elements are toggled without interfering with others
    if (element.style.display === 'block') {
        element.style.display = 'none';
    } else {
        element.style.display = 'block';
    }
}
	</script>
</body>
</html>