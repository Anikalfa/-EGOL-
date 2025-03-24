<?php
session_start();
include 'config/db.php'; // Make sure the path is correct

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to view this page.";
    exit();
}

$user_id = $_SESSION['user_id'];

// Debugging: Print user ID
echo "User ID: " . $user_id . "<br>";

// Retrieve balance from the database
$sql = "SELECT balance FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    echo "Error: Could not prepare the SQL statement. " . $conn->error;
    exit();
}

$stmt->bind_param("i", $user_id);

// Execute the query and check for errors
if (!$stmt->execute()) {
    echo "Execute failed: " . $stmt->error;
    exit();
}

$result = $stmt->get_result();

// Check if the result is valid
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($row) {
        $balance = $row['balance'];
    } else {
        echo "Error: Could not retrieve balance.";
        exit();
    }
} else {
    echo "Error: Could not execute the query. SQL Error: " . $stmt->error;
    exit();
}

// Check if 'user_name' is set in the session
$user_name = isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : "User";

// Display welcome message and balance
echo "<h2>Welcome, " . $user_name . "!</h2>";
echo "<p>Your current balance is: $" . number_format($balance, 2) . "</p>";
?>
