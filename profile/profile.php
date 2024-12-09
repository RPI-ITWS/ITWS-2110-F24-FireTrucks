<?php
session_start();
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
} else {
    $userId = $_SESSION['id']; 
}

// Fetch user details
$sqlUser = "SELECT * FROM users WHERE UserId = ?";
$stmtUser = $conn->prepare($sqlUser);
$stmtUser->bind_param("i", $userId);
$stmtUser->execute();
$resultUser = $stmtUser->get_result();
$user = $resultUser->fetch_assoc();

// Fetch listings for the user
$sqlListings = "SELECT * FROM listingData WHERE UserId = ?";
$stmtListings = $conn->prepare($sqlListings);
$stmtListings->bind_param("i", $userId);
$stmtListings->execute();
$listings = $stmtListings->get_result();

// Fetch services for the user
$sqlServices = "SELECT * FROM servicesData WHERE UserId = ?";
$stmtServices = $conn->prepare($sqlServices);
$stmtServices->bind_param("i", $userId);
$stmtServices->execute();
$services = $stmtServices->get_result();

// Fetch auctions for the user
$sqlAuctions = "
    SELECT * 
    FROM auctionsData 
    WHERE host_name = (SELECT first_name FROM users WHERE UserId = ?)
";
$stmtAuctions = $conn->prepare($sqlAuctions);
$stmtAuctions->bind_param("i", $userId);
$stmtAuctions->execute();
$auctions = $stmtAuctions->get_result();

// Fetch giveaways for the user
$sqlGiveaways = "
    SELECT gd.* 
    FROM giveawayData gd
    INNER JOIN giveawayEntreesData ge ON gd.giveaway_id = ge.giveaway_id
    WHERE ge.name = (
        SELECT first_name 
        FROM users 
        WHERE UserId = ?
    )
";
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
    <title><?php echo htmlspecialchars($user['first_name']); ?> <?php echo htmlspecialchars($user['last_name']); ?>'s Profile</title>
    <h3> Email: <?php echo htmlspecialchars($user['email']); ?></h3>
    <link rel="stylesheet" href="profile.css"> 
    <link rel="stylesheet" href="../main.css">
</head>
<body>
    <!-- Website Header -->
   <div id="header">
      <img src="../images/logo.png" alt="RPI Marketplace Logo" class="logo" onclick="window.location.href='../index.php'">
      <div id="header-buttons">
        <button class="headerbutton" alt="Goods Page Button" onclick="window.location.href='../goods/index.html'">Goods</button>
        <button class="headerbutton" alt="Services Page Button" onclick="window.location.href='../services/index.html'">Services</button>
        <button class="headerbutton" alt="Giveaways Page Button" onclick="window.location.href='../giveaways/giveaways.html'">Giveaways</button>
        <button class="headerbutton" alt="Auctions Page Button" onclick="window.location.href='../auctions/auctions.html'">Auctions</button>
        <button class="headerbutton" alt="Create Listing Button" onclick="window.location.href='../listingform/index.php'">Create Listing</button>
      </div>
      <script src="../main.js"></script>
   </div>
    <div class="head-container">
        <h1><?php echo htmlspecialchars($user['email']); ?>'s Profile</h1>
    </div>
    <div class="container">
        <section>
            <h2>Your Listings</h2>
            <div class="scrollable-row">
                <?php foreach ($listings as $listing): ?>
                    <div class="card">
                        <img src="<?php echo htmlspecialchars($listing['image_url']); ?>" class="cardImg">
                        <strong><?php echo htmlspecialchars($listing['Name']); ?></strong>
                        <p><?php echo htmlspecialchars($listing['Description']); ?></p>
                        <form method="POST" action="delete_item.php" onsubmit="return confirm('Are you sure you want to remove this listing?');">
                            <input type="hidden" name="type" value="listing">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($listing['ListingId']); ?>">
                            <button type="submit">Remove</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section>
            <h2>Your Services</h2>
            <div class="scrollable-row">
                <?php foreach ($services as $service): ?>
                    <div class="card">
                        <img src="<?php echo htmlspecialchars($service['image_url']); ?>" class="cardImg">
                        <strong><?php echo htmlspecialchars($service['Name']); ?></strong>
                        <p><?php echo htmlspecialchars($service['Description']); ?></p>
                        <form method="POST" action="delete_item.php" onsubmit="return confirm('Are you sure you want to remove this service?');">
                            <input type="hidden" name="type" value="service">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($service['ServiceId']); ?>">
                            <button type="submit">Remove</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section>
            <h2>Your Giveaways</h2>
            <div class="scrollable-row">
                <?php foreach ($giveaways as $giveaway): ?>
                    <div class="card">
                        <img src="<?php echo htmlspecialchars($giveaway['image_url']); ?>" class="cardImg">
                        <strong><?php echo htmlspecialchars($giveaway['name']); ?></strong>
                        <p><?php echo htmlspecialchars($giveaway['description']); ?></p>
                        <form method="POST" action="delete_item.php" onsubmit="return confirm('Are you sure you want to remove this giveaway?');">
                            <input type="hidden" name="type" value="giveaway">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($giveaway['giveaway_id']); ?>">
                            <button type="submit">Remove</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section>
            <h2>Your Auctions</h2>
            <div class="scrollable-row">
                <?php foreach ($auctions as $auction): ?>
                    <div class="card">
                        <img src="<?php echo htmlspecialchars($auction['image_url']); ?>" class="cardImg">
                        <strong><?php echo htmlspecialchars($auction['title']); ?></strong>
                        <p><?php echo htmlspecialchars($auction['description']); ?></p>
                        <form method="POST" action="delete_item.php" onsubmit="return confirm('Are you sure you want to remove this auction?');">
                            <input type="hidden" name="type" value="auction">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($auction['id']); ?>">
                            <button type="submit">Remove</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </div>
    <div id="footer">
      <p>2024 RPI Marketplace. All rights reserved.</p>
   </div>
</body>
</html>
