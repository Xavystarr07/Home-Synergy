<?php
session_start();

// Ensure the user is logged in with a user_id session variable
if (!isset($_SESSION['user_id'])) {
    echo "User not logged in.";
    exit;
}

$user_id = $_SESSION['user_id'];

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "home_synergy";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Update payment_status from 'unpaid' to 'paid' for the user’s appointments
$sql = "UPDATE appointments SET payment_status = 'paid' WHERE user_id = ? AND payment_status = 'unpaid'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);

// Execute the statement
$stmt->execute();

// Close connection
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jacquarda+Bastarda+9&display=swap" rel="stylesheet">
    <style>
        /* Highlight selected payment option */
        .li-card a.selected {
            outline: 3px solid blue;
        }
    </style>
</head>
<body>
    <nav class="navBar">
        <a href="home" class="gohome"><label for="" class="heading">Home Synergy</label></a><label for="" class=""><img src="logoHomeSynergy.jpg" alt="#" class="Logo"></label>
    </nav>

    <div class="container">
        <form id="paymentForm" action="../user_dashboard.php" method="POST">
            <div class="row">
                <div class="column">
                    <h3 class="Title">Billing Address</h3>
                    <div class="input-box">
                        <span>Fullname : </span>
                        <input type="text" id="fullname" placeholder="Preston Govindsamy" required pattern="^[a-zA-Z\s]+$" title="Fullname should only contain letters and spaces.">
                    </div>
                    <div class="input-box">
                        <span>Email : </span>
                        <input type="email" id="email" placeholder="prestong595@gmail.com" required>
                    </div>
                    <div class="input-box">
                        <span>Address : </span>
                        <input type="text" id="address" placeholder="29 Otterpalm Road" required pattern="^[0-9]+\s[a-zA-Z\s]+$" title="Address should contain a number followed by letters.">
                    </div>
                    <div class="input-box">
                        <span>City : </span>
                        <input type="text" id="city" placeholder="Durban" required pattern="^[a-zA-Z\-]+$" title="City should only contain letters and dashes.">
                    </div>
                    <!-- Province Selection -->
<div class="input-box">
    <span>Province : </span>
    <select id="province" required>
        <option value="">Select Province</option>
        <option value="KwaZulu-Natal">KwaZulu-Natal</option>
        <option value="Gauteng">Gauteng</option>
        <option value="Western Cape">Western Cape</option>
        <option value="Eastern Cape">Eastern Cape</option>
        <option value="Limpopo">Limpopo</option>
        <option value="Mpumalanga">Mpumalanga</option>
        <option value="Free State">Free State</option>
        <option value="North West">North West</option>
        <option value="Northern Cape">Northern Cape</option>
        <option value="North West">North West</option>
    </select>
</div>
                        <div class="input-box">
                            <span>Zip-Code : </span>
                            <input type="number" id="zipCode" placeholder="4068" required>
                        </div>
                    </div>
                </div>
                <div class="column">
                    <h3 class="Title">Payment Method</h3>
                    <!-- Select Bank Dropdown -->
                    <div class="input-box">
                        <span>Select a Bank : </span>
                        <select id="bankSelect" required>
                            <option value="" disabled selected>Select your bank</option>
                            <option value="absa">Absa</option>
                            <option value="fnb">First National Bank</option>
                            <option value="standard">Standard Bank</option>
                            <option value="nedbank">Nedbank</option>
                            <option value="capitec">Capitec Bank</option>
                            <option value="investec">Investec</option>
                            <option value="santam">Santam</option>
                            <option value="bankofbaroda">Bank of Baroda</option>
                            <option value="rmb">Rand Merchant Bank</option>
                            <option value="wesbank">WesBank</option>
                            <option value="tdbank">TD Bank</option>
                            <option value="barclays">Barclays</option>
                            <option value="spurbank">Spur Bank</option>
                            <option value="abnb">African Bank</option>
                            <option value="bankwithus">Bank with Us</option>
                        </select>
                    </div>
                    <div class="input-box">
                        <span class="PaymentMethods">Different Methods Accepted:</span>
                        <div class="cards">
                            <ul class="li-cards">
                                <li class="li-card"><a href="#" class="paypal"><img src="paypal.webp" id="paypal"></a></li>
                                <li class="li-card"><a href="#" class="mastercard"><img src="mastercard.jpg" id="mastercard"></a></li>
                                <li class="li-card"><a href="#" class="gpay"><img src="gpay.png" id="gpay"></a></li>
                                <li class="li-card"><a href="#" class="visa"><img src="visa.webp" id="visa"></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="input-box">
                        <span>Name of card Holder : </span>
                        <input type="text" id="cardHolder" placeholder="Mr. P. Govindsamy" required pattern="^[a-zA-Z\s]+$" title="Cardholder name should only contain letters.">
                    </div>
                    <div class="input-box">
                        <span>Card Number : </span>
                        <input type="number" id="cardNumber" placeholder="11122222222222" required>
                    </div>
                    <!-- Expiry Month Selection -->
<div class="input-box">
    <span>Expiry Month : </span>
    <select id="expiryMonth" required>
        <option value="">Select Month</option>
        <option value="January">January</option>
        <option value="February">February</option>
        <option value="March">March</option>
        <option value="April">April</option>
        <option value="May">May</option>
        <option value="June">June</option>
        <option value="July">July</option>
        <option value="August">August</option>
        <option value="September">September</option>
        <option value="October">October</option>
        <option value="November">November</option>
        <option value="December">December</option>
    </select>
</div>

<!-- Expiry Year Selection (from 2024 onwards) -->
<div class="flex">
    <div class="input-box">
        <span>Expiry Year : </span>
        <select id="expiryYear" required>
            <option value="">Select Year</option>
            <?php
                // Generate year options from 2024 to 2030
                $currentYear = date("Y");
                for ($year = 2025; $year <= $currentYear + 8; $year++) {
                    echo "<option value=\"$year\">$year</option>";
                }
            ?>
        </select>
    </div>
</div>
                        <div class="input-box">
                            <span>CVW : </span>
                            <input type="number" id="cvw" placeholder="111" required>
                        </div>
						<button type="submit" class="btn">Submit</button>
                    </div>
					
                </div>
				
            </div>
            
        </form>
    </div>

    <script>
        let cardNumberErrorShown = false;
let selectedPaymentMethod = null;

const fields = [
    {
        id: 'fullname',
        message: 'Please enter a valid full name with at least 4 letters, only letters and spaces.',
        pattern: /^[a-zA-Z\s]{4,}$/  // At least 4 letters, allowing spaces
    },
    {
        id: 'email',
        message: 'Please enter a valid email with a dot followed by a domain.',
        pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    },
    {
        id: 'address',
        message: 'Address should start with a number followed by text.',
        pattern: /^[0-9]+\s[a-zA-Z\s]+$/
    },
    {
        id: 'city',
        message: 'City name should be at least 4 letters, only letters or dashes.',
        pattern: /^[a-zA-Z\-]{4,}$/  // At least 4 letters, allowing dashes
    },
    {
        id: 'province',
        message: 'Province name should only contain letters or dashes.',
        pattern: /^[a-zA-Z\-]+$/
    },
    {
        id: 'zipCode',
        message: 'Please enter a valid zip code with a maximum of 4 digits.',
        pattern: /^[0-9]{4}$/  // Zip code must be exactly 4 digits
    },
    {
        id: 'cardHolder',
        message: 'Cardholder name should be at least 5 letters, allowing spaces.',
        pattern: /^[a-zA-Z\s]{5,}$/  // At least 5 letters, allowing spaces
    },
    {
        id: 'cardNumber',
        message: 'Enter a valid card number with at least 13 digits.',
        pattern: /^[0-9]{13,19}$/
    },
    {
        id: 'expiryMonth',
        message: 'Enter a valid expiry month in text form.',
        pattern: /^[a-zA-Z]+$/
    },
    {
        id: 'expiryYear',
        message: 'Enter a valid expiry year.',
        pattern: /^[0-9]{4}$/
    },
    {
        id: 'cvw',
        message: 'Enter a valid CVV number with a maximum of 3 digits.',
        pattern: /^[0-9]{3}$/  // CVV must be exactly 3 digits
    }
];

fields.forEach(field => {
    const inputElement = document.getElementById(field.id);

    let isTouched = false;

    inputElement.addEventListener('input', () => {
        isTouched = true;
    });

    inputElement.addEventListener('blur', () => {
        if (!isTouched) return;

        const value = inputElement.value;
        const pattern = field.pattern;

        if (!pattern.test(value)) {
            alert(field.message);
            isTouched = false;
        }
    });
});

// Adding event listeners for payment method selection
document.querySelectorAll('.li-card a').forEach(link => {
    link.addEventListener('click', function(event) {
        // Check if the clicked card already has the 'selected' class
        if (event.target.classList.contains('selected')) {
            // Deselect the card by removing the 'selected' class
            event.target.classList.remove('selected');
            selectedPaymentMethod = null; // Reset the selected payment method
        } else {
            // Remove the 'selected' class from all cards to deselect
            document.querySelectorAll('.li-card a').forEach(item => item.classList.remove('selected'));

            // Add the 'selected' class to the clicked card
            event.target.classList.add('selected');
            selectedPaymentMethod = event.target; // Store the newly selected payment method
        }
    });
});

// On form submit
document.getElementById('paymentForm').addEventListener('submit', function(event) {
    event.preventDefault();

    // Check if a payment method is selected
    if (!selectedPaymentMethod) {
        alert('Please select a payment method.');
        return;
    }

    let isValid = true;
    fields.forEach(field => {
        const inputElement = document.getElementById(field.id);
        const value = inputElement.value;
        const pattern = field.pattern;

        if (!pattern.test(value)) {
            isValid = false;
            alert(field.message);
        }
    });

    if (isValid) {
        alert("Your payment was successful, return to dashboard.");
        window.location.href = "../user_dashboard.php";
    }
});

    </script>
</body>
</html>
