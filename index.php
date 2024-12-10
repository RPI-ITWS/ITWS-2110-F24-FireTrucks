<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Home Page</title>
   <link rel="stylesheet" href="./main.css">
   <link rel="stylesheet" href="./index.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
   <script>
       // Define a global JavaScript variable `loggedIn` based on PHP session
       const loggedIn = <?php echo isset($_SESSION['id']) ? 'true' : 'false'; ?>;
   </script>
   <script src="./index.js" defer></script>
</head>
<body>
   <!-- Website Header -->
   <div id="header">
      <img src="./images/logo.png" alt="RPI Marketplace Logo" class="logo" onclick="window.location.href='./index.php'">
      <div id="header-buttons">
         <?php if (isset($_SESSION['id'])): ?>
            <button class="headerbutton" onclick="window.location.href='./goods/index.html'">Goods</button>
            <button class="headerbutton" onclick="window.location.href='./services/index.html'">Services</button>
            <button class="headerbutton" onclick="window.location.href='./listingform/index.php'">Create Listing</button>
            <button class="headerbutton" onclick="window.location.href='./profile/profile.php'">Profile</button>
            <button class="headerbutton" onclick="window.location.href='./login/logout.php'">Logout</button>
         <?php else: ?>
            <button class="headerbutton" onclick="window.location.href='./login/login.php'">Login</button>
         <?php endif; ?>
      </div>
   </div>

   <!-- Main Content -->
   <main>
      <!-- Featured Listings -->
      <div class="listingSection">
         <div class="listingHeading">
            <h2 class="listingSectionName">Featured Listings</h2>
         </div>
         <div class="featuredListings" id="featuredListingsContainer">
            <!-- LISTINGS WILL BE ADDED HERE -->
         </div>
      </div>

      <!-- Active Auctions -->
      <div class="listingSection">
         <div class="listingHeading">
            <h2 class="listingSectionName">Active Auctions</h2>
         </div>
         <div class="featuredListings" id="activeAuctionsContainer">
            <!-- AUCTIONS WILL BE ADDED HERE -->
         </div>
      </div>

      <!-- Giveaways -->
      <div class="listingSection">
         <div class="listingHeading">
            <h2 class="listingSectionName">Giveaways</h2>
         </div>
         <div class="featuredListings" id="giveawaysContainer">
            <!-- GIVEAWAYS WILL BE ADDED HERE -->
         </div>
      </div>
   </main>

   <!-- Footer -->
   <div id="footer">
      <p>2024 RPI Marketplace. All rights reserved.</p>
   </div>
</body>
</html>
