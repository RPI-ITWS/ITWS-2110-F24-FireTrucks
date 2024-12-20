<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

$servername = "localhost";
$username = "phpmyadmin";  
$password = "Marketplace18";      
$dbname = "marketplace"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
   die("Connection failed: " . $conn->connect_error);
}

// Check if logged in
if (!isset($_SESSION['id'])) {
   echo json_encode(['success' => false, 'message' => 'You must be logged in to access this page.']);
   exit;
}
else {
   $userId = $_SESSION['id']; 
}

// Get POST request data
$giveaway_id = isset($_POST['giveaway_id']) ? (int)$_POST['giveaway_id'] : null;
$name = isset($_POST['user']) ? trim($_POST['user']) : null;

// making sure both giveaway_id and name were passed in and are not empty
if ($giveaway_id !== null && $giveaway_id > 0 && !empty($name)) {
   // Giveaway ended?
   $time_check_sql = "SELECT time_end FROM giveawayData WHERE giveaway_id = ?";
   $time_check_stmt = $conn->prepare($time_check_sql);
   $time_check_stmt->bind_param("i", $giveaway_id);
   $time_check_stmt->execute();
   $time_check_result = $time_check_stmt->get_result();
   $time_check = $time_check_result->fetch_assoc();

   if ($time_check) {
      $time_end = new DateTime($time_check['time_end']);
      $now = new DateTime();

      if ($now > $time_end) {
         // Giveaway has ended
         echo json_encode(['success' => false, 'message' => 'The giveaway has already ended and cannot be entered.']);
         exit;
      }
   } else {
      // Giveaway ID not found
      echo json_encode(['success' => false, 'message' => 'Giveaway not found.']);
         exit;
   }

   // Check if the person is already entered in the giveaway
   $check_sql = "SELECT COUNT(*) AS entry_count FROM giveawayEntreesData WHERE giveaway_id = ? AND `UserId` = ?";
   $check_stmt = $conn->prepare($check_sql);
   $check_stmt->bind_param("is", $giveaway_id, $userId);  
   $check_stmt->execute();
   $check_result = $check_stmt->get_result();
   $check = $check_result->fetch_assoc();
   
   // User already in giveaway
   if ($check['entry_count'] > 0) {
      echo json_encode(['success' => false, 'message' => 'You have already been entered!']);
   } else {
      $insert_sql = "INSERT INTO giveawayEntreesData (giveaway_id, `UserId`, entree_time) VALUES (?, ?, NOW())";
      $stmt = $conn->prepare($insert_sql);
      $stmt->bind_param("is", $giveaway_id, $userId);
      
      if ($stmt->execute()) {
         echo json_encode(['success' => true, 'message' => 'You\'ve been entered!']);
      } else {
         echo json_encode(['success' => false, 'message' => 'Error entering giveaway: ' . $stmt->error]);
      }
   }
} else {
   echo json_encode(['success' => false, 'message' => 'Invalid input data - id: ' . $giveaway_id . " name: " . $name]);
}

$conn->close();
?>
