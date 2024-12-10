<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost"; 
$username = "phpmyadmin"; 
$password = "Marketplace18"; 
$dbname = "marketplace"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

// Fetch latest 5 listings
$listingsData = [];
$sqlListings = "SELECT * FROM listingData ORDER BY ListingId DESC LIMIT 5";
$resultListings = $conn->query($sqlListings);
if ($resultListings && $resultListings->num_rows > 0) {
    while ($row = $resultListings->fetch_assoc()) {
        $listingsData[] = $row;
    }
}

// Fetch latest 5 auctions
$auctionsData = [];
$sqlAuctions = "SELECT * FROM auctionsData ORDER BY id DESC LIMIT 5";
$resultAuctions = $conn->query($sqlAuctions);
if ($resultAuctions && $resultAuctions->num_rows > 0) {
    while ($row = $resultAuctions->fetch_assoc()) {
        $auctionsData[] = $row;
    }
}

// Fetch latest 5 giveaways
$giveawaysData = [];
$sqlGiveaways = "SELECT * FROM giveawayData ORDER BY giveaway_id DESC LIMIT 5";
$resultGiveaways = $conn->query($sqlGiveaways);
if ($resultGiveaways && $resultGiveaways->num_rows > 0) {
    while ($row = $resultGiveaways->fetch_assoc()) {
        $giveawaysData[] = $row;
    }
}

$conn->close();

// Combine the data into a single JSON object
echo json_encode([
    'listings' => $listingsData,
    'auctions' => $auctionsData,
    'giveaways' => $giveawaysData
]);
