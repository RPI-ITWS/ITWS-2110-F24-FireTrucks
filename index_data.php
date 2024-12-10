<?php
header('Content-Type: application/json');
$servername = "localhost";
$username = "phpmyadmin";
$password = "Marketplace18";
$dbname = "marketplace";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

$data = [
    'listings' => [],
    'auctions' => [],
    'giveaways' => [],
];

// Fetch Listings
$result = $conn->query("SELECT * FROM listingData ORDER BY ListingId DESC LIMIT 5");
if ($result) $data['listings'] = $result->fetch_all(MYSQLI_ASSOC);

// Fetch Auctions
$result = $conn->query("SELECT * FROM auctionsData ORDER BY id DESC LIMIT 5");
if ($result) $data['auctions'] = $result->fetch_all(MYSQLI_ASSOC);

// Fetch Giveaways
$result = $conn->query("SELECT * FROM giveawayData ORDER BY giveaway_id DESC LIMIT 5");
if ($result) $data['giveaways'] = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode($data);
$conn->close();
?>
