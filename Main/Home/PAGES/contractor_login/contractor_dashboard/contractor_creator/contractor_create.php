<?php 
session_start();
require_once 'database.php'; 

// Checks if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $username = htmlspecialchars($_POST['contractor_username']);
    $password = $_POST['contractor_password'];
    $name = htmlspecialchars($_POST['contractor_name']);
    $surname = htmlspecialchars($_POST['contractor_surname']);
    $email = htmlspecialchars($_POST['contractor_email']);
    $phone_number = htmlspecialchars($_POST['contractor_phone_number']);
    $location = htmlspecialchars($_POST['contractor_location']);
    $profession = htmlspecialchars($_POST['contractor_profession']);
    $experiance = (int) htmlspecialchars($_POST['contractor_experiance']);
    $rating = (float) htmlspecialchars($_POST['contractor_rating']);
    $created_at = date('Y-m-d H:i:s');

    // File upload for profile picture
    $profile_picture_path = '';
    if (isset($_FILES['contractor_profile_picture']) && $_FILES['contractor_profile_picture']['error'] == 0) {
        $profile_picture = $_FILES['contractor_profile_picture']['name'];
        $target_dir1 = "contractor_uploads/";
        $target_file1 = $target_dir1 . basename($profile_picture);
        $target_dir2 = "../user_registration/user_login/user_dashboard/contractor_uploads_user/";
        $target_file2 = $target_dir2 . basename($profile_picture);

        if (!is_dir($target_dir2)) {
            mkdir($target_dir2, 0777, true);
        }

        if (move_uploaded_file($_FILES['contractor_profile_picture']['tmp_name'], $target_file1)) {
            if (copy($target_file1, $target_file2)) {
                $profile_picture_path = 'user_registration/user_login/user_dashboard/contractor_uploads_user/' . $profile_picture;
            }
        }
    }

    // Validate all fields before inserting into the database
    if ($username && $password && $name && $surname && $email && $phone_number && $location && $profession && $experiance > 0 && $rating >= 1 && $rating <= 5 && $profile_picture_path) {

        // Check if username, email, or phone number already exists
        $stmt = $conn->prepare("SELECT * FROM contractors WHERE contractor_username = ? OR contractor_email = ? OR contractor_phone_number = ?");
        $stmt->bind_param("sss", $username, $email, $phone_number);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Username, email, or phone number already exists. Please choose another one.";
        } else {
            // Hash the password
            $password = password_hash($password, PASSWORD_BCRYPT);

            // Fetch the current maximum contractor_id
            $result = $conn->query("SELECT MAX(contractor_id) AS max_id FROM contractors");
            $row = $result->fetch_assoc();
            $new_id = $row['max_id'] + 1; // Increment the max_id

            // Insert contractor into the database
            $sql = "INSERT INTO contractors (contractor_id, contractor_username, contractor_password, contractor_name, contractor_surname, contractor_email, contractor_phone_number, contractor_location, contractor_profile_picture, contractor_profession, contractor_experience, contractor_rating, created_at) 
                    VALUES ($new_id, '$username', '$password', '$name', '$surname', '$email', '$phone_number', '$location', '$profile_picture_path', '$profession', $experiance, $rating, '$created_at')";

            if ($conn->query($sql) === TRUE) {
                echo "<script>alert('Contractor has been successfully created.'); window.location.href = 'contractor_create.php';</script>";
            } else {
                $error = "Error: " . $conn->error;
            }
        }

        // Close the prepared statement
        $stmt->close();
    } else {
        $error = "Please make sure all fields are valid and all required fields are filled.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contractor Creator</title>
    <link rel="stylesheet" href="contractor_create.css">
    <script>
        // JavaScript form validation function
        function validateForm() {
            // Validate username (alphanumeric, 4-20 characters)
            var username = document.getElementById('username').value;
            var usernamePattern = /^[a-zA-Z0-9_ ]{4,20}$/;
            if (!usernamePattern.test(username)) {
                alert('Username must be 4-20 characters long and contain only letters, numbers, and underscores.');
                return false;
            }

            // Validate password (at least 8 characters, 1 uppercase, 1 lowercase, 1 symbol)
            var password = document.getElementById('password').value;
            var passwordPattern = /^(?=.*[A-Z])(?=.*[a-z])(?=.*[\W_])[A-Za-z\d\W_]{8,}$/;
            if (!passwordPattern.test(password)) {
                alert('Password must be at least 8 characters long, and contain at least one uppercase letter, one lowercase letter, and one symbol.');
                return false;
            }

            // Validate name and surname (only letters and spaces)
            var name = document.getElementById('name').value;
            var surname = document.getElementById('surname').value;
            var namePattern = /^[a-zA-Z\s]+$/;
            if (!namePattern.test(name)) {
                alert('Name must contain only letters and spaces.');
                return false;
            }
            if (!namePattern.test(surname)) {
                alert('Surname must contain only letters and spaces.');
                return false;
            }

            // Validate email format
            var email = document.getElementById('email').value;
            var emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (!emailPattern.test(email)) {
                alert('Please enter a valid email address.');
                return false;
            }

            // Validate phone number (starts with 0, max 10 digits)
            var phone_number = document.getElementById('phone_number').value;
            var phonePattern = /^0\d{9}$/;
            if (!phonePattern.test(phone_number)) {
                alert('Phone number must start with 0 and be exactly 10 digits.');
                return false;
            }

            // Validate years of experience (must be numeric)
            var experiance = document.getElementById('experiance').value;
            if (isNaN(experiance) || experiance <= 0) {
                alert('Years of experience must be a positive number.');
                return false;
            }

            // Validate rating (must be numeric, between 1 and 5)
            var rating = document.getElementById('rating').value;
            if (isNaN(rating) || rating < 1 || rating > 5) {
                alert('Rating must be a number between 1 and 5.');
                return false;
            }

 // Function to validate fields
function validateField(type, value) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "check_existing.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            if (xhr.responseText == "exists") {
                alert(type.charAt(0).toUpperCase() + type.slice(1) + " already exists. Please choose another " + type + ".");
                // Reload the page to show the error
                window.location.reload();
            }
        }
    };
    xhr.send("type=" + type + "&value=" + value);
}

var username = document.getElementById('username').value;
var email = document.getElementById('email').value;
var phone_number = document.getElementById('phone_number').value;

// Check username
validateField('username', username);

// Check email
validateField('email', email);

// Check phone number
validateField('phone', phone_number);


            // If validation is successful for all fields, submit the form
            return true;
        }
    </script>
</head>
<body>
  <div class="container">
        <video autoplay muted loop class="background-video" playsinline disablepictureinpicture controlslist="nodownload">
            <source src="blueVid1.mp4" type="video/webm">
            Your browser does not support the video tag.
        </video>
        <h2>Contractor Details</h2>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        <form method="POST" action="" enctype="multipart/form-data" onsubmit="return validateForm()">
            <label for="username">Username:</label>
            <input type="text" id="username" name="contractor_username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="contractor_password" required>

            <label for="name">Name:</label>
            <input type="text" id="name" name="contractor_name" required>

            <label for="surname">Surname:</label>
            <input type="text" id="surname" name="contractor_surname" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="contractor_email" required>

            <label for="phone_number">Phone Number:</label>
            <input type="text" id="phone_number" name="contractor_phone_number" required>

            <label for="profession">Profession:</label>
<select id="profession" name="contractor_profession" required>
    <option value="" disabled selected>Select your profession</option>
    <option value="plumbing">Plumbing</option>
    <option value="electrical">Electrical</option>
    <option value="carpentry">Carpentry</option>
    <option value="painting">Painting</option>
</select>

<label for="location">Location:</label>
<select id="location" name="contractor_location" required>
    <option value="" disabled selected>Select your province</option>
    <option value="eastern_cape">Eastern Cape</option>
    <option value="free_state">Free State</option>
    <option value="gauteng">Gauteng</option>
    <option value="kwazulu_natal">KwaZulu-Natal</option>
    <option value="limpopo">Limpopo</option>
    <option value="mpumalanga">Mpumalanga</option>
    <option value="north_west">North West</option>
    <option value="northern_cape">Northern Cape</option>
    <option value="western_cape">Western Cape</option>
</select>

            
            <label for="experiance">Years of Experience:</label>
            <input type="text" id="experiance" name="contractor_experiance" required>
            
            <label for="rating">Rating (1-5):</label>
            <input type="text" id="rating" name="contractor_rating" required>
            
            <label for="profile_picture">Profile Picture:</label>
            <input type="file" name="contractor_profile_picture" accept="image/*">

            <button type="submit">Create Contractor</button>
        </form>
    </div>
</body>
</html>
