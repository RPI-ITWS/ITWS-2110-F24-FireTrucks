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

// Get giveaway id from URL
$giveaway_id = (int)$_GET['id'];

// Fetch giveaway details 
$giveaway_sql = "SELECT * FROM giveawayData WHERE giveaway_id = ?";
$stmt = $conn->prepare($giveaway_sql); 
if ($stmt === false) {
    // Output the error and stop execution
    die("Error preparing statement: " . $conn->error);
}
$stmt->bind_param("i", $giveaway_id); // Substitute in id for ?
$stmt->execute();
$giveaway_result = $stmt->get_result();

// Giveaway found
if ($giveaway_result->num_rows > 0) {
    $giveaway = $giveaway_result->fetch_assoc();

    // Get number bidders
    $participants_sql = "SELECT COUNT(DISTINCT name) AS unique_participants FROM giveawayEntreesData WHERE giveaway_id = ?;";
    $stmt = $conn->prepare($participants_sql); 
    if ($stmt === false) {
        // Output the error and stop execution
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("i", $giveaway_id); 
    $stmt->execute();
    $participants_result = $stmt->get_result();

    // Fetch the unique participants count
    $participants_data = $participants_result->fetch_assoc();
    $unique_participants = $participants_data['unique_participants'];

    // Calculate time left
    $time_end = new DateTime($giveaway['time_end']);
    $now = new DateTime();
    $time_left = $time_end->diff($now)->format('%d days %h hours %i minutes');

    // Send back data
    echo json_encode([
        'success' => true,
        'title' => $giveaway['name'],
        'description' => $giveaway['description'],
        'image_url' => $giveaway['image_url'],
        'participantsNum' => $unique_participants,  
        'time_left' => $time_left,
        'seller' => $giveaway['seller']
    ]);
} else {
    // Giveaway
    echo json_encode(['success' => false, 'message' => 'Giveaway with id ' . $giveaway_id . ' not found']);
}
?>
