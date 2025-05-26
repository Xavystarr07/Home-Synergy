<?php
require_once 'database.php';

if (isset($_POST['type']) && isset($_POST['value'])) {
    $type = htmlspecialchars($_POST['type']);
    $value = htmlspecialchars($_POST['value']);
    $column = '';

    // Determine which column to check based on the 'type' sent from JS
    if ($type == 'username') {
        $column = 'contractor_username';
    } elseif ($type == 'email') {
        $column = 'contractor_email';
    } elseif ($type == 'phone') {
        $column = 'contractor_phone_number';
    }

    // Query to check if the value exists in the specified column
    $query = "SELECT * FROM contractors WHERE $column = '$value'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        echo "exists"; // Value exists in the database
    } else {
        echo "valid"; // Value is valid
    }
}
?>
