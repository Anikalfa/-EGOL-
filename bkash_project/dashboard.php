<?php
// Start the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include database connection file
include 'config/db.php'; // Ensure the path is correct

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to view this page.";
    exit();
}

$user_id = $_SESSION['user_id'];

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

// Check if the result contains data
if ($result && $result->num_rows > 0) {
    // Fetch balance from the result
    $row = $result->fetch_assoc();
    $balance = $row['balance'];
} else {
    // If no rows are returned
    echo "No user found with the provided user ID or balance is not set.";
    exit();
}

// Check if 'user_name' is set in the session
$user_name = isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : "User";

// Display welcome message and balance
echo "<h2>Welcome, " . $user_name . "!</h2>";
echo "<p>Your current balance is: ৳" . number_format($balance, 2) . "</p>";
?>

<!-- Dashboard Options -->
<!-- Dashboard Options -->
<div class="dashboard-options">
    <a href="send_money.php" class="btn">Send Money</a>
    <a href="transaction_history.php" class="btn">Transaction History</a>
    <a href="add_money_button.php" class="btn">Add Money</a> <!-- Changed add_money_button.php to add_money.php -->
    <a href="mobile_recharge.php" class="btn">Mobile Recharge</a> <!-- Changed link if necessary -->
    <a href="payment.php" class="btn">Payment</a>
    <a href="pay_bill.php" class="btn">Pay Bill</a>
    <a href="logout.php" class="btn">Logout</a>
</div>
