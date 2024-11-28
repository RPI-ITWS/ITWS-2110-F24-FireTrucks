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

// Get auction id from URL
$auction_id = (int)$_GET['id'];

// Fetch auction details 
$auction_sql = "SELECT * FROM auctionsData WHERE id = ?";
$stmt = $conn->prepare($auction_sql); 
$stmt->bind_param("i", $auction_id); // Substitute in id for ?
$stmt->execute();
$auction_result = $stmt->get_result();

// Auction found
if ($auction_result->num_rows > 0) {
    $auction = $auction_result->fetch_assoc();

    // Get current highest bid
    $current_bid_sql = "SELECT bidder_name, bid_amount, bid_time FROM bidsData WHERE auction_id = ? ORDER BY bid_amount DESC, bid_time ASC LIMIT 1";
    $stmt = $conn->prepare($current_bid_sql);
    $stmt->bind_param("i", $auction_id);
    $stmt->execute();
    $current_bid_result = $stmt->get_result();
    $current_bid_row = $current_bid_result->fetch_assoc();
    $highest_bidder = $current_bid_row['bidder_name'] ?? null;
    $highest_bid = $current_bid_row['bid_amount'] ?? null;

    // If no bids, show starting bid 
    if ($highest_bid === null) {
        $highest_bid = $auction['starting_bid'];
        $highest_bidder = null; // No winner if no bids
    }

    // Get previous bidders
    $bids_sql = "SELECT bidder_name, bid_amount, bid_time, `anonymous` FROM bidsData WHERE auction_id = ? ORDER BY bid_time DESC";
    $stmt = $conn->prepare($bids_sql); 
    $stmt->bind_param("i", $auction_id); 
    $stmt->execute();
    $bids_result = $stmt->get_result();
    $bidders = $bids_result->fetch_all(MYSQLI_ASSOC); // MYSQLI_ASSOC = keys correspond to column names

    // Calculate time left
    $time_end = new DateTime($auction['time_end']);
    $now = new DateTime();

    if ($time_end < $now) {
        $time_left = 'Auction Over!';
        $winner = $highest_bidder;
    } else {
        $time_left = $time_end->diff($now)->format('%d days %h hours %i minutes');
        $winner = null; 
    }

    // Send back data
    echo json_encode([
        'success' => true,
        'title' => $auction['title'],
        'description' => $auction['description'],
        'image_url' => $auction['image_url'],
        'current_bid' => $highest_bid,  
        'time_left' => $time_left,
        'bidders' => $bidders,
        'winner' => $winner
    ]);
} else {
    // Auction not found
    echo json_encode(['success' => false, 'message' => 'Auction with id ' . $auction_id . ' not found']);
}
?>
