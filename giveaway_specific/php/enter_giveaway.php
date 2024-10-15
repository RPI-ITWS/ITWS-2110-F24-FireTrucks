<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "phpmyadmin";  
$password = "Marketplace18";      
$dbname = "marketplace"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
   die("Connection failed: " . $conn->connect_error);
}

// Get POST request data
$giveaway_id = isset($_POST['giveaway_id']) ? (int)$_POST['giveaway_id'] : null;
$name = isset($_POST['user']) ? trim($_POST['user']) : null;

// Validate that both giveaway_id and name were passed in and are not empty
if ($giveaway_id !== null && $giveaway_id > 0 && !empty($name)) {
   // Check if the person is already entered in the giveaway
   $check_sql = "SELECT COUNT(*) AS entry_count FROM giveawayEntreesData WHERE giveaway_id = ? AND `name` = ?";
   $check_stmt = $conn->prepare($check_sql);
   $check_stmt->bind_param("is", $giveaway_id, $name);  
   $check_stmt->execute();
   $check_result = $check_stmt->get_result();
   $check = $check_result->fetch_assoc();
   
   // User already in giveaway
   if ($check['entry_count'] > 0) {
      echo json_encode(['success' => false, 'message' => 'You have already been entered!']);
   }
   else {
      $insert_sql = "INSERT INTO giveawayEntreesData (giveaway_id, `name`, entree_time) VALUES (?, ?, NOW())";
      $stmt = $conn->prepare($insert_sql);
      $stmt->bind_param("is", $giveaway_id, $name);
      
      if ($stmt->execute()) {
         echo json_encode(['success' => true, 'message' => 'You\'ve been entered!']);
      } else {
         echo json_encode(['success' => false, 'message' => 'Error entering auction: ' . $stmt->error]);
      }

   }
} else {
   echo json_encode(['success' => false, 'message' => 'Invalid input data - id: ' . $giveaway_id . " name: " . $name]);
}

$conn->close();
?>
