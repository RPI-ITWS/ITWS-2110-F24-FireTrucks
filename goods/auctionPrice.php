<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection settings
$servername = "localhost";
$username = "phpmyadmin";  
$password = "Marketplace18";      
$dbname = "marketplace"; 

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
   die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

$auction_id = (int)$_GET['auction_id']; // Make sure to sanitize this input
// Get current highest bid
$current_bid_sql = "SELECT MAX(bid_amount) AS current_bid FROM bidsData WHERE auction_id = ?";
$stmt = $conn->prepare($current_bid_sql);
$stmt->bind_param("i", $auction_id); // Substitute in id for ?
$stmt->execute();
$current_bid_result = $stmt->get_result();
$current_bid_row = $current_bid_result->fetch_assoc();
$current_bid = $current_bid_row['current_bid'] ?? null; // Null if no bids

// If no bids, show starting bid 
if ($current_bid === null) {
   $current_bid = -1;
}

// Convert the data to JSON format
echo json_encode($current_bid);
$conn->close();
?>
