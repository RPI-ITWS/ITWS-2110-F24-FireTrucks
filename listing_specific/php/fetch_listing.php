<?php
session_start();
$servername = "localhost";
$username = "phpmyadmin";  
$password = "Marketplace18";      
$dbname = "marketplace"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Login check
if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in to access this page.']);
    exit;
 }
 else {
    $userId = $_SESSION['id']; 
 }

// Id value not passed in - return with error message
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID is required']);
    exit;
}

// Get listing id from URL
$listing_id = (int)$_GET['id'];

// Fetch listing details 
$listing_sql = "SELECT * FROM listingData WHERE ListingId = ?";
$stmt = $conn->prepare($listing_sql);

if (!$stmt) {
    echo json_encode([
        'success' => false, 
        'message' => 'Failed to prepare statement: ' . $conn->error
    ]);
    exit;
}

$stmt->bind_param("i", $listing_id); // Substitute in id for ?
$stmt->execute();
$listing_result = $stmt->get_result();

if ($listing_result->num_rows > 0) {
   $listing = $listing_result->fetch_assoc();
   echo json_encode([
      'success' => true,
      'ListingId' => $listing['ListingId'],
      'Name' => $listing['Name'],
      'Seller' => $listing['Seller'],
      'Price' => $listing['Price'],
      'Description' => $listing['Description'],
      'PhoneNumber' => $listing['PhoneNumber'],
      'Email' => $listing['Email'],
      'image_url' => $listing['image_url'],
      'UserId' => $listing['UserId'],
      'isGiveaway' => $listing['isGiveaway'],
      'isAuction' => $listing['isAuction']
   ]);
} else {
   echo json_encode(['success' => false, 'message' => 'Listing with id ' . $listing_id . ' not found']);
}

$stmt->close();
$conn->close();
?>
