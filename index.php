<?php
session_start();
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

// No direct queries here for card display, as index_data.php handles it

?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Home Page</title>
   <link rel="stylesheet" href="./main.css">
   <link rel="stylesheet" href="./index.css">
   <script>
      var isUserLoggedIn = <?php echo isset($_SESSION['id']) ? 'true' : 'false'; ?>;
   </script>

</head>
<body> 
   <!-- Website Header -->
   <div id="header">
      <img src="./images/logo.png" alt="RPI Marketplace Logo" class="logo" onclick="window.location.href='./index.php'">
      <div id="header-buttons">
         <?php
            if (isset($_SESSION['id'])) {
               echo '<button class="headerbutton" alt="Goods Page Button" onclick="window.location.href=\'./goods/index.html\'">Goods</button>';
               echo '<button class="headerbutton" alt="Services Page Button" onclick="window.location.href=\'./services/index.html\'">Services</button>';
               echo '<button class="headerbutton" alt="Create Listing Button" onclick="window.location.href=\'./listingform/index.php\'">Create Listing</button>';
               echo '<button class="headerbutton" alt="Profile Page Button" onclick="window.location.href=\'./profile/profile.php\'">Profile</button>';
               echo '<button class="headerbutton" alt="Logout Button" onclick="window.location.href=\'./login/logout.php\' ">Logout</button>';
            } else {
               echo '<button class="headerbutton" alt="Login Button" onclick="window.location.href=\'./login/login.php\' ">Login</button>';
            }
         ?>
      </div>
      <script src="../main.js"></script>
   </div>
   <main>
      <!-- Featured Listings -->
      <div class="listingSection">
         <div class="listingHeading">
            <h2 class="listingSectionName">Featured Listings</h2>
         </div>
         <div class="featuredListings" id="featuredListingsContainer"></div>
      </div>
      
      <!-- Active Auctions -->
      <div class="listingSection">
         <div class="listingHeading">
            <h2 class="listingSectionName">Active Auctions</h2>
         </div>
         <div class="featuredListings" id="activeAuctionsContainer"></div>
      </div>
      
      <!-- Giveaways -->
      <div class="listingSection">
         <div class="listingHeading">
            <h2 class="listingSectionName">Giveaways</h2>
         </div>
         <div class="featuredListings" id="giveawaysContainer"></div>
      </div>
   </main>
   <div id="footer">
      <p>2024 RPI Marketplace. All rights reserved.</p>
   </div>
   <script src="./index.js"></script>
</body>
</html>
