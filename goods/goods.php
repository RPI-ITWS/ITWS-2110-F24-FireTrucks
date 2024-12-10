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

// Goods data
$data = [];
$sql = "SELECT * FROM `listingData` ORDER BY ListingId DESC"; // Ensure the table name matches exactly
$result = $conn->query($sql);

if ($result === false) {
    die(json_encode(['error' => 'Query error: ' . $conn->error]));
}

if ($result->num_rows > 0) {
   while ($row = $result->fetch_assoc()) {
      $data[] = $row; // Append each row to the $data array
   }
}

// Auction data
$sql = "SELECT * FROM `auctionsData` WHERE time_end > CURRENT_TIMESTAMP() ORDER BY id DESC"; // Ensure the table name matches exactly
$result = $conn->query($sql);

if ($result === false) {
    die(json_encode(['error' => 'Query error: ' . $conn->error]));
}

if ($result->num_rows > 0) {
   while ($row = $result->fetch_assoc()) {
      $data[] = $row; // Append each row to the $data array
   }
}

// Giveaway data
$sql = "SELECT * FROM `giveawayData` WHERE time_end > CURRENT_TIMESTAMP() ORDER BY giveaway_id DESC"; // Ensure the table name matches exactly
$result = $conn->query($sql);

if ($result === false) {
    die(json_encode(['error' => 'Query error: ' . $conn->error]));
}

if ($result->num_rows > 0) {
   while ($row = $result->fetch_assoc()) {
      $data[] = $row; // Append each row to the $data array
   }
}

// Convert the data to JSON format
echo json_encode($data);
$conn->close();
?>
