<?php
$servername = "localhost";
$username = "phpmyadmin";  
$password = "Marketplace18";      
$dbname = "marketplace"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Id value not passed in - return with error message
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID is required']);
    exit;
}

// Get service id from URL
$service_id = (int)$_GET['id'];

// Fetch service details 
$service_sql = "SELECT * FROM servicesData WHERE ServiceId = ?";
$stmt = $conn->prepare($service_sql);

if (!$stmt) {
    echo json_encode([
        'success' => false, 
        'message' => 'Failed to prepare statement: ' . $conn->error
    ]);
    exit;
}

$stmt->bind_param("i", $service_id); // Substitute in id for ?
$stmt->execute();
$service_result = $stmt->get_result();

if ($service_result->num_rows > 0) {
   $service = $service_result->fetch_assoc();
   echo json_encode([
      'success' => true,
      'ServiceId' => $service['ServiceId'],
      'Name' => $service['Name'],
      'Seller' => $service['Seller'],
      'Price' => $service['Price'],
      'Description' => $service['Description'],
      'PhoneNumber' => $service['PhoneNumber'],
      'Email' => $service['Email'],
      'image_url' => $service['image_url']
   ]);
} else {
   echo json_encode(['success' => false, 'message' => 'Service with id ' . $service_id . ' not found']);
}

$stmt->close();
$conn->close();
?>
