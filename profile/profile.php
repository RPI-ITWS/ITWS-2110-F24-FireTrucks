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

if (!isset($_SESSION['id'])) {
    echo "User is not logged in.";
    $userId=1;
}
else {
    $userId = $_SESSION['id']; // Assuming user ID is stored in session
}


// Fetch user details
$sqlUser = "SELECT Email FROM users WHERE UserId = ?";
$stmtUser = $conn->prepare($sqlUser);
$stmtUser->bind_param("i", $userId);
$stmtUser->execute();
$resultUser = $stmtUser->get_result();
$user = $resultUser->fetch_assoc();

// Fetch listings for the user
$sqlListings = "SELECT * FROM listingData WHERE ListingId = ?";
$stmtListings = $conn->prepare($sqlListings);
$stmtListings->bind_param("i", $userId);
$stmtListings->execute();
$listings = $stmtListings->get_result();

// Fetch auctions for the user
$sqlAuctions = "SELECT * FROM auctionData WHERE Name = (SELECT Name AS n FROM users WHERE UserId = ?)";
$stmtAuctions = $conn->prepare($sqlAuctions);
$stmtAuctions->bind_param("i", $userId);
$stmtAuctions->execute();
$auctions = $stmtAuctions->get_result();

// Fetch giveaways for the user
$sqlGiveaways = "SELECT * FROM giveawayData WHERE giveaway_id = (SELECT giveaway_id AS gid FROM giveawayEntreesData WHERE Name = (SELECT Name AS n FROM users WHERE UserId = ?)";
$stmtGiveaways = $conn->prepare($sqlGiveaways);
$stmtGiveaways->bind_param("i", $userId);
$stmtGiveaways->execute();
$giveaways = $stmtGiveaways->get_result();

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($user['Email']); ?>'s Profile</title>
    <link rel="stylesheet" href="profile.css"> 
</head>
<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($user['Email']); ?>'s Profile</h1>

        <section>
            <h2>Your Listings</h2>
            <div class="scrollable-row">
                <?php foreach ($listings as $listing): ?>
                    <div class="card">
                        <strong><?php echo htmlspecialchars($listing['Name']); ?></strong>
                        <p><?php echo htmlspecialchars($listing['Description']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section>
            <h2>Your Giveaways</h2>
            <div class="scrollable-row">
                <?php foreach ($giveaways as $giveaway): ?>
                    <div class="card">
                        <strong><?php echo htmlspecialchars($giveaway['name']); ?></strong>
                        <p><?php echo htmlspecialchars($giveaway['description']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section>
            <h2>Your Auctions</h2>
            <div class="scrollable-row">
                <?php foreach ($auctions as $auction): ?>
                    <div class="card">
                        <strong><?php echo htmlspecialchars($auction['title']); ?></strong>
                        <p>Starting Bid: $<?php echo htmlspecialchars($auction['description']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </div>
</body>
</html>
