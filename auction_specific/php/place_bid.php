<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection details
$servername = "localhost";
$username = "phpmyadmin";  
$password = "Marketplace18";      
$dbname = "marketplace"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get POST request data
$auction_id = isset($_POST['auction_id']) ? (int)$_POST['auction_id'] : 0;
$bid_amount = isset($_POST['bid_amount']) ? (float)$_POST['bid_amount'] : 0.0;
$anonymous = isset($_POST['anonymous']) ? (int)$_POST['anonymous'] : 0;
$bidder_name = isset($_POST['bidder_name']) ? $_POST['bidder_name'] : "";

// Validate 
if ($auction_id > 0 && $bid_amount > 0 && !empty($bidder_name) && $bidder_name != "") {
   // Get the starting bid
   $auction_sql = "SELECT starting_bid FROM auctionsData WHERE id = ?";
   $stmt = $conn->prepare($auction_sql);
   $stmt->bind_param("i", $auction_id);
   $stmt->execute();
   $auction_result = $stmt->get_result();
   $auction = $auction_result->fetch_assoc();
   $starting_bid = $auction['starting_bid'] ?? 0;

   // Get current highest bid
   $current_bid_sql = "SELECT MAX(bid_amount) AS current_bid FROM bidsData WHERE auction_id = ?";
   $stmt = $conn->prepare($current_bid_sql);
   $stmt->bind_param("i", $auction_id);
   $stmt->execute();
   $result = $stmt->get_result();
   $row = $result->fetch_assoc();
   $current_bid = $row['current_bid'] ?? 0;  

   // Check if the new bid is higher than the current highest bid
   if ($bid_amount > $current_bid && $bid_amount > $starting_bid) {
      // Add bid
      $insert_sql = "INSERT INTO bidsData (auction_id, bidder_name, bid_amount, bid_time, anonymous) VALUES (?, ?, ?, NOW(), ?)";
      $stmt = $conn->prepare($insert_sql);
      $stmt->bind_param("isdi", $auction_id, $bidder_name, $bid_amount, $anonymous);
      
      if ($stmt->execute()) {
         echo json_encode(['success' => true, 'message' => 'Bid placed successfully']);
      } else {
         echo json_encode(['success' => false, 'message' => 'Error placing bid: ' . $stmt->error]);
      }

   } else {
      if ($current_bid > $starting_bid) {
         echo json_encode(['success' => false, 'message' => 'Your bid must be higher than the current bid of $' . $current_bid]);
      }
      else {
         echo json_encode(['success' => false, 'message' => 'Your bid must be higher than the starting bid of $' . $starting_bid]);
      }
   }

} else {
   echo json_encode(['success' => false, 'message' => 'Invalid input data']);
}

$conn->close();
?>
