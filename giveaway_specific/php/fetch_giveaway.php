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

// Id value not passed in - return with error message
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID is required']);
    exit;
}

// Check if logged in
if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in to access this page.']);
    exit;
}
else {
    $userId = $_SESSION['id']; 
}

// Get giveaway id from URL
$giveaway_id = (int)$_GET['id'];

// Fetch giveaway details 
$giveaway_sql = "SELECT * FROM giveawayData WHERE giveaway_id = ?";
$stmt = $conn->prepare($giveaway_sql); 
if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
}
$stmt->bind_param("i", $giveaway_id); 
$stmt->execute();
$giveaway_result = $stmt->get_result();

// Giveaway found
if ($giveaway_result->num_rows > 0) {
    $giveaway = $giveaway_result->fetch_assoc();
    
    // Get number of bidders
    $participants_sql = "SELECT COUNT(DISTINCT UserId) AS unique_participants FROM giveawayEntreesData WHERE giveaway_id = ?;";
    $stmt = $conn->prepare($participants_sql); 
    if ($stmt === false) {
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

    $winner_first_name = null;
    $winner_last_name = null;

    if ($time_end < $now) {
        $time_left = 'Giveaway Over!';

        // Winner not drawn already
        if (is_null($giveaway['winnerId'])) {
            // Select winner
            $random_winner_sql = "
                SELECT UserId 
                FROM giveawayEntreesData 
                WHERE giveaway_id = ? 
                ORDER BY RAND() 
                LIMIT 1;
            ";

            $stmt = $conn->prepare($random_winner_sql);
            if ($stmt === false) {
                die("Error preparing statement: " . $conn->error);
            }

            $stmt->bind_param("i", $giveaway_id); 
            $stmt->execute();
            $winner_result = $stmt->get_result();

            if ($winner_result->num_rows > 0) {
                $winner_data = $winner_result->fetch_assoc();
                $winnerId = $winner_data['UserId'];

                // Update table with the new winner
                $update_winner_sql = "
                    UPDATE giveawayData 
                    SET winnerId = ? 
                    WHERE giveaway_id = ?;
                ";

                $stmt = $conn->prepare($update_winner_sql);
                if ($stmt === false) {
                    die("Error preparing statement: " . $conn->error);
                }

                $stmt->bind_param("ii", $winnerId, $giveaway_id);
                $stmt->execute();

                // Update the giveaway array with the new winnerId
                $giveaway['winnerId'] = $winnerId;
            } else {
                // No participants found
                $giveaway['winnerId'] = null;
            }
        }

        // Fetch winner details if a winner is set
        if (!is_null($giveaway['winnerId'])) {
            $winner_sql = "SELECT first_name, last_name FROM users WHERE UserId = ?";
            $stmt = $conn->prepare($winner_sql);
            if ($stmt === false) {
                die("Error preparing statement: " . $conn->error);
            }

            $stmt->bind_param("i", $giveaway['winnerId']);
            $stmt->execute();
            $winner_details_result = $stmt->get_result();

            if ($winner_details_result->num_rows > 0) {
                $winner_details = $winner_details_result->fetch_assoc();
                $winner_first_name = $winner_details['first_name'];
                $winner_last_name = $winner_details['last_name'];
            }
        }
    } else {
        $time_left = $time_end->diff($now)->format('%d days %h hours %i minutes');
    }

    // Send back data
    echo json_encode([
        'success' => true,
        'title' => $giveaway['name'],
        'description' => $giveaway['description'],
        'image_url' => $giveaway['image_url'],
        'participantsNum' => $unique_participants,  
        'time_left' => $time_left,
        'seller' => $giveaway['seller'],
        'winnerid' => $giveaway['winnerId'],
        'winner_first_name' => $winner_first_name,
        'winner_last_name' => $winner_last_name
    ]);
} else {
    // Giveaway not found
    echo json_encode(['success' => false, 'message' => 'Giveaway with id ' . $giveaway_id . ' not found']);
}
?>
