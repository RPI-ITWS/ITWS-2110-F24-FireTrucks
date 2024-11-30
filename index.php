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
    die("Connection failed: " . $conn->connect_error);
}

// Fetch latest 5 goods
$sqlListings = "SELECT * FROM listingData ORDER BY ListingId DESC LIMIT 5";
$resultListings = $conn->query($sqlListings);
if (!$resultListings) {
    die("Error fetching listings: " . $conn->error);
}

// Fetch latest 5 auctions
$sqlAuctions = "SELECT * FROM auctionsData ORDER BY id DESC LIMIT 5";
$resultAuctions = $conn->query($sqlAuctions);
if (!$resultAuctions) {
    die("Error fetching auctions: " . $conn->error);
}

// Fetch latest 5 giveaways
$sqlGiveaways = "SELECT * FROM giveawayData ORDER BY giveaway_id DESC LIMIT 5";
$resultGiveaways = $conn->query($sqlGiveaways);
if (!$resultGiveaways) {
    die("Error fetching giveaways: " . $conn->error);
}
?>
    
<!DOCTYPE html>
<html lang="en">
    
<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Home Page</title>
   <link rel="stylesheet" href="main.css">
   <link rel="stylesheet" href="index.css">
   <style>
       .listingImg {
           width: 200px; 
           height: 200px;
           object-fit: cover; 
       }
   </style>
</head>
    
<body> 
   <!-- Website Header -->
   <div id="header">
      <img src="images/logo.png" alt="RPI Marketplace Logo" class="logo" onclick="window.location.href='./index.php'">
      <div id="header-buttons">
         <button class="headerbutton" alt="Goods Page Button" onclick="window.location.href='goods/index.html'">Goods</button>
         <button class="headerbutton" alt="Services Page Button" onclick="window.location.href='services/index.html'">Services</button>
         <button class="headerbutton" alt="Auctions Page Button" onclick="window.location.href='auctions/auctions.html'">Auctions</button>
         <button class="headerbutton" alt="Giveaways Page Button" onclick="window.location.href='giveaways/giveaways.html'">Giveaways</button>
         <button class="headerbutton" alt="Create Listing" onclick="window.location.href='listingform/index.php'">Create Listing</button>
         <div id="login-button"></div>
      </div>
      <script src="../main.js"></script>
   </div>
    
   <!-- Featured Listings -->
   <div class="listingSection">
      <div class="listingHeading">
         <h2 class="listingSectionName">Featured Listings</h2>
         <a href="" class="viewAll">View All</a>
      </div>
      <div class="featuredListings">
         <?php while ($row = $resultListings->fetch_assoc()) { ?>
         <div class="listing">
            <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="" class="listingImg">
            <div class="listingText">
               <h3 class="listingTitle"><?php echo htmlspecialchars($row['Name']); ?></h3>
               <h5 class="listingDescription"><?php echo htmlspecialchars($row['Description']); ?></h5>
               <h4 class="price">$<?php echo htmlspecialchars($row['Price']); ?></h4>
               <?php
               if (!isset($_SESSION['id'])) {
                   // User is not logged in
                   echo '<button class="CTAbutton" alt="" onclick="window.location.href=\'login/login.php\'">Buy Now</button>';
               } else {
                   // User is logged in
                   echo '<button class="CTAbutton" alt="" onclick="window.location.href=\'listingDetails.php?id=' . $row['ListingId'] . '\'">Buy Now</button>';
               }
               ?>
            </div>
         </div>
         <?php } ?>
      </div>
   </div>
    
   <!-- Active Auctions -->
   <div class="listingSection">
      <div class="listingHeading">
         <h2 class="listingSectionName">Active Auctions</h2>
         <a href="" class="viewAll">View All</a>
      </div>
      <div class="featuredListings">
         <?php while ($row = $resultAuctions->fetch_assoc()) { ?>
         <div class="listing">
            <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="" class="listingImg">
            <div class="listingText">
               <h3 class="listingTitle"><?php echo htmlspecialchars($row['title']); ?></h3>
               <h5 class="listingDescription"><?php echo htmlspecialchars($row['description']); ?></h5>
               <h4 class="price">Starting Bid: $<?php echo htmlspecialchars($row['starting_bid']); ?></h4>
               <?php
               if (!isset($_SESSION['id'])) {
                   // User is not logged in
                   echo '<button class="CTAbutton" alt="" onclick="window.location.href=\'login/login.php\'">Bid Now</button>';
               } else {
                   // User is logged in
                   echo '<button class="CTAbutton" alt="" onclick="window.location.href=\'auctionDetails.php?id=' . $row['id'] . '\'">Bid Now</button>';
               }
               ?>
            </div>
         </div>
         <?php } ?>
      </div>
   </div>
    
   <!-- Giveaways -->
   <div class="listingSection">
      <div class="listingHeading">
         <h2 class="listingSectionName">Giveaways</h2>
         <a href="" class="viewAll">View All</a>
      </div>
      <div class="featuredListings">
         <?php while ($row = $resultGiveaways->fetch_assoc()) { ?>
         <div class="listing">
            <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="" class="listingImg">
            <div class="listingText">
               <h3 class="listingTitle"><?php echo htmlspecialchars($row['name']); ?></h3>
               <h5 class="listingDescription"><?php echo htmlspecialchars($row['description']); ?></h5>
               <h4 class="price">Free</h4>
               <?php
               if (!isset($_SESSION['id'])) {
                   // User is not logged in
                   echo '<button class="CTAbutton" alt="" onclick="window.location.href=\'login/login.php\'">Enter Now</button>';
               } else {
                   // User is logged in
                   echo '<button class="CTAbutton" alt="" onclick="window.location.href=\'giveawayDetails.php?id=' . $row['giveaway_id'] . '\'">Enter Now</button>';
               }
               ?>
            </div>
         </div>
         <?php } ?>
      </div>
   </div>

   <?php $conn->close(); ?>
</body>
</html>
