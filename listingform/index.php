<?php
    session_start();
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    // Database connection settings
    $servername = "localhost";
    $username = "phpmyadmin";
    $password = "Marketplace18";
    $dbname = "marketplace";
    // Create connection
    $db = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    } 

    // Check if user is logged in
    if (!isset($_SESSION['id'])) {
        header("Location: ../login/login.php");
        echo "User is not logged in.";
        exit; 
    }

    // Retrieve userId from session
    $userID = $_SESSION['id'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <Title>Listing Form</Title>
   <link rel="stylesheet" href="listingform.css">
   <link rel="stylesheet" href="../main.css">
</head>

<body>
<div id="header">
      <img src="../images/logo.png" alt="RPI Marketplace Logo" class="logo" onclick="window.location.href='../index.php'">
      <div id="header-buttons">
         <button class="headerbutton" alt="Goods Page Button" onclick="window.location.href='../goods/index.html'">Goods</button>
         <button class="headerbutton" alt="Services Page Button" onclick="window.location.href='../services/index.html'">Services</button>
         <button class="headerbutton" alt="Create Listing Button" onclick="window.location.href='../listingform/index.php'">Create Listing</button>
         <button class="headerbutton" alt="Profile Page Button" onclick="window.location.href='../profile/profile.php'">Profile</button>
         <button class="headerbutton" alt="Logout Button" onclick="window.location.href='..login/logout.php' "> Logout</button>
      </div>
      <script src="../main.js"></script>
   </div>
   <main>
    <!--Initial form to choose type of listing-->
    <div class="box">
        <form>
            <h1> RPI Marketplace</h1>
            <p>Buy, sell, and trade with fellow RPI students.</p>
            <label for="productType">Product Type</label><br>
            <input type="radio" id="auction" name="listingType" value="auction">
            <label for="auction" id="auction">Auction</label><br>
            <input type="radio" id="giveaway" name="listingType" value="giveaway">
            <label for="giveaway" id="giveaway">Giveaway</label><br>
            <input type="radio" id="good" name="listingType" value="good">
            <label for="good" id="good">Good</label><br>
            <input type="radio" id="service" name="listingType" value="service">
            <label for="service" id="service">Service</label><br>
            <div class="buttoncontainer">
                <button type="button" id="next">Next</button>
            </div>
        </form>
    </div>
    <!--Form for Auctions-->
    <div class="box hidden" id="auctionform">
        <form action="submit.php" method="POST">
            <input type="hidden" name="formType" value="auction">
            <input type="hidden" name="userID" value="<?php echo $userID; ?>">
            <h1>RPI Marketplace</h1>
            <p>Buy, sell, and trade with fellow RPI students.</p>
            <h2>Auction</h2>
            <label for="title">Title</label><br>
            <input type="text" placeholder="Item for Auction" id="title" name="title" required><br>
            <label for="category">Category</label><br>
                <input type="checkbox" name="category[]" value="textbooks">
                <label for="textbooks">Textbooks</label>
                <input type="checkbox" name="category[]" value="electronics">
                <label for="electronics">Electronics</label>
                <input type="checkbox" name="category[]" value="clothes">
                <label for="clothes">Clothes</label>
                <input type="checkbox" name="category[]" value="vehicles">
                <label for="vehicles">Vehicles</label><br>
                <input type="checkbox" name="category[]" value="furniture">
                <label for="furniture">Furniture</label>
                <input type="checkbox" name="category[]" value="games">
                <label for="games">Games</label>
                <input type="checkbox" name="category[]" value="appliances">
                <label for="appliances">Appliances</label>
                <input type="checkbox" name="category[]" value="sportsEquip">
                <label for="sportsEquip">Sports Equipment</label><br>
                <input type="checkbox" name="category[]" value="jewelry">
                <label for="jewelry">Jewelry</label>
                <input type="checkbox" name="category[]" value="art">
                <label for="art">Art</label>
                <input type="checkbox" name="category[]" value="instruments">
                <label for="instruments">Instruments</label>
                <input type="checkbox" name="category[]" value="antiques">
                <label for="antiques">Antiques</label><br>
                <input type="checkbox" name="category[]" value="collectibles">
                <label for="collectibles">Collectibles</label>
                <input type="checkbox" name="category[]" value="tickets">
                <label for="tickets">Tickets</label>
                <input type="checkbox" name="category[]" value="toys">
                <label for="toys">Toys</label>
                <input type="checkbox" name="category[]" value="tools">
                <label for="tools">Tools</label>
                <input type="checkbox" name="category[]" value="miscellaneous">
                <label for="miscellaneous">Miscellaneous</label><br>
            <label for="description">Description</label><br>
            <textarea placeholder="Describe your item" id="description" name="description" rows="4" cols="60"></textarea><br>
            <label for="startbid">Starting Bid</label><br>
            <input type="number" step="0.01" min="0" placeholder="200" id="startbid" name="startbid" required><br>
            <label for="auctionEnd">Auction End Date and Time</label><br>
            <input type="datetime-local" id="auctionEnd" name="auctionEnd"><br>
            <label for="hostName">Auction Host</label><br>
            <input type="text" placeholder="Name of host or organization" id="hostName" name="hostName" size="50"><br>
            <label for="image">Image URl</label><br>
            <textarea id="image" name="image" rows="2" cols="70"required></textarea><br>
            <p id="contactInfo">Contact Info</p>
            <label for="email">RPI Email</label>
            <input type="email" placeholder="rcsid@rpi.edu" id="email" name="email" pattern="[a-zA-Z0-9._]+@rpi\.edu" required><br>
            <label for="phoneNum">Phone Number</label>
            <input type="tel" placeholder="use format: 518-100-001" id="phoneNum" name="phoneNum" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}"><br>
            <div class="agree">
                <input type="checkbox" id="agreement" name="agreement" required>
                <label for="agreement">By submitting, I agree that the information provided is accurate to the best of my knowledge.</label>
            </div>
            <div class="buttoncontainer">
                <button type="submit" id="post">Post Auction</button>
            </div>
        </form>
    </div>
    <!--Form for Giveaways-->
    <div class="box hidden" id="giveawayform">
        <form action="submit.php" method="POST">
            <input type="hidden" name="formType" value="giveaway">
            <input type="hidden" name="userID" value="<?php echo $userID; ?>">
            <h1>RPI Marketplace</h1>
            <p>Buy, sell, and trade with fellow RPI students.</p>
            <h2>Giveaway</h2>
            <label for="title">Title</label><br>
            <input type="text" placeholder="Giveaway Item" id="title" name="title"><br>
                <label for="category">Category</label><br>
                <input type="checkbox" name="category[]" value="textbooks">
                <label for="textbooks">Textbooks</label>
                <input type="checkbox" name="category[]" value="electronics">
                <label for="electronics">Electronics</label>
                <input type="checkbox" name="category[]" value="clothes">
                <label for="clothes">Clothes</label>
                <input type="checkbox" name="category[]" value="vehicles">
                <label for="vehicles">Vehicles</label><br>
                <input type="checkbox" name="category[]" value="furniture">
                <label for="furniture">Furniture</label>
                <input type="checkbox" name="category[]" value="games">
                <label for="games">Games</label>
                <input type="checkbox" name="category[]" value="appliances">
                <label for="appliances">Appliances</label>
                <input type="checkbox" name="category[]" value="sportsEquip">
                <label for="sportsEquip">Sports Equipment</label><br>
                <input type="checkbox" name="category[]" value="jewelry">
                <label for="jewelry">Jewelry</label>
                <input type="checkbox" name="category[]" value="art">
                <label for="art">Art</label>
                <input type="checkbox" name="category[]" value="instruments">
                <label for="instruments">Instruments</label>
                <input type="checkbox" name="category[]" value="antiques">
                <label for="antiques">Antiques</label><br>
                <input type="checkbox" name="category[]" value="collectibles">
                <label for="collectibles">Collectibles</label>
                <input type="checkbox" name="category[]" value="tickets">
                <label for="tickets">Tickets</label>
                <input type="checkbox" name="category[]" value="toys">
                <label for="toys">Toys</label>
                <input type="checkbox" name="category[]" value="tools">
                <label for="tools">Tools</label>
                <input type="checkbox" name="category[]" value="miscellaneous">
                <label for="miscellaneous">Miscellaneous</label><br>
            <label for="description">Description</label><br>
            <textarea placeholder="Describe your giveaway" id="description" name="description" rows="4" cols="60"></textarea><br>
            <label for="hostName">Giveaway Host</label><br>
            <input type="text" placeholder="Name of host or organization" id="hostName" name="hostName" size="50"><br>
            <label for="giveawayEnd">Giveaway End Date and Time</label><br>
            <input type="datetime-local" id="giveawayEnd" name="giveawayEnd"><br>
            <label for="image">Image URl</label><br>
            <textarea id="image" name="image" rows="2" cols="70"required></textarea><br>
            <p id="contactInfo">Contact Info</p>
            <label for="email">RPI Email</label>
            <input type="email" placeholder="rcsid@rpi.edu" id="email" name="email" pattern="[a-zA-Z0-9._]+@rpi\.edu" required><br>
            <label for="phoneNum">Phone Number</label>
            <input type="tel" placeholder="use format: 518-100-001" id="phoneNum" name="phoneNum" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}"><br>
            <div class="agree">
                <input type="checkbox" id="agreement" name="agreement" required>
                <label for="agreement">By submitting, I agree that the information provided is accurate to the best of my knowledge.</label>
            </div>
            <div class="buttoncontainer">
                <button type="submit" id="post">Post Giveaway</button>
            </div>
        </form>
    </div>
    <!--Form for Goods-->
    <div class="box hidden" id="goodform">
        <form action="submit.php" method="POST">
            <input type="hidden" name="formType" value="good">
            <input type="hidden" name="userID" value="<?php echo $userID; ?>">
            <h1>RPI Marketplace</h1>
            <p>Buy, sell, and trade with fellow RPI students.</p>
            <h2>Good</h2>
            <label for="title">Title</label><br>
            <input type="text" placeholder="Item for Sale" id="title" name="title"><br>
            <label for="category">Category</label><br>
                    <input type="checkbox" name="category[]" value="textbooks">
                    <label for="textbooks">Textbooks</label>
                    <input type="checkbox" name="category[]" value="electronics">
                    <label for="electronics">Electronics</label>
                    <input type="checkbox" name="category[]" value="clothes">
                    <label for="clothes">Clothes</label>
                    <input type="checkbox" name="category[]" value="vehicles">
                    <label for="vehicles">Vehicles</label><br>
                    <input type="checkbox" name="category[]" value="furniture">
                    <label for="furniture">Furniture</label>
                    <input type="checkbox" name="category[]" value="games">
                    <label for="games">Games</label>
                    <input type="checkbox" name="category[]" value="appliances">
                    <label for="appliances">Appliances</label>
                    <input type="checkbox" name="category[]" value="sportsEquip">
                    <label for="sportsEquip">Sports Equipment</label><br>
                    <input type="checkbox" name="category[]" value="jewelry">
                    <label for="jewelry">Jewelry</label>
                    <input type="checkbox" name="category[]" value="art">
                    <label for="art">Art</label>
                    <input type="checkbox" name="category[]" value="instruments">
                    <label for="instruments">Instruments</label>
                    <input type="checkbox" name="category[]" value="antiques">
                    <label for="antiques">Antiques</label><br>
                    <input type="checkbox" name="category[]" value="collectibles">
                    <label for="collectibles">Collectibles</label>
                    <input type="checkbox" name="category[]" value="tickets">
                    <label for="tickets">Tickets</label>
                    <input type="checkbox" name="category[]" value="toys">
                    <label for="toys">Toys</label>
                    <input type="checkbox" name="category[]" value="tools">
                    <label for="tools">Tools</label>
                    <input type="checkbox" name="category[]" value="miscellaneous">
                    <label for="miscellaneous">Miscellaneous</label><br>
            <label for="description">Description</label><br>
            <textarea placeholder="Describe your item" id="description" name="description" rows="4" cols="60"></textarea><br>
            <label for="price">Price</label><br>
            <input type="number" step="0.01" min="0" placeholder="200" id="price" name="price" max="1000"><br>
            <label for="seller">Seller</label><br>
            <input type="text" pattern="[A-Za-z]+ [A-Za-z]+" placeholder="first and last name" id="seller" name="seller" size="50"><br>
            <label for="image">Image URl</label><br>
            <textarea id="image" name="image" rows="2" cols="70"required></textarea><br>
            <p id="contactInfo">Contact Info</p>
            <label for="email">RPI Email</label>
            <input type="email" placeholder="rcsid@rpi.edu" id="email" name="email" pattern="[a-zA-Z0-9._]+@rpi\.edu" required><br>
            <label for="phoneNum">Phone Number</label>
            <input type="tel" placeholder="use format: 518-100-001" id="phoneNum" name="phoneNum" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}"><br>
            <div class="agree">
                <input type="checkbox" id="agreement" name="agreement" required>
                <label for="agreement">By submitting, I agree that the information provided is accurate to the best of my knowledge.</label>
            </div>
            <div class="buttoncontainer">
                <button type="submit" id="post">Post Good</button>
            </div>
        </form>
    </div>
    <!--Form for Services-->
    <div class="box hidden" id="serviceform">
        <form action="submit.php" method="POST">
            <input type="hidden" name="formType" value="service">
            <input type="hidden" name="userID" value="<?php echo $userID; ?>">
            <h1>RPI Marketplace</h1>
            <p>Buy, sell, and trade with fellow RPI students.</p>
            <h2>Service</h2>
            <label for="title">Title</label><br>
            <input type="text" placeholder="Service Name" id="title" name="title"><br>
            <label for="category">Category</label><br>
                        <input type="checkbox" name="category[]" value="tutoring">
                        <label for="tutoring">Tutoring</label>
                        <input type="checkbox" name="category[]" value="editing">
                        <label for="writing">Writing Help & Editing</label>
                        <input type="checkbox" name="category[]" value="techsupport">
                        <label for="techsupport">Tech Support</label><br>
                        <input type="checkbox" name="category[]" value="photography">
                        <label for="photography">Photography or Videography</label>
                        <input type="checkbox" name="category[]" value="moving">
                        <label for="moving">Moving Assistance</label>
                        <input type="checkbox" name="category[]" value="cleaning">
                        <label for="cleaning">Cleaning Services</label><br>
                        <input type="checkbox" name="category[]" value="pets">
                        <label for="pets">Pet Services</label>
                        <input type="checkbox" name="category[]" value="transportation">
                        <label for="transportation">Transportation Services</label>
                        <input type="checkbox" name="category[]" value="miscellaneous">
                        <label for="miscellaneous">Miscellaneous</label><br>
            <label for="description">Description</label><br>
            <textarea placeholder="Describe your item" id="description" name="description" rows="4" cols="60"></textarea><br>
            <label for="price">Price</label><br>
            <input type="number" step="0.01" min="0" placeholder="200" id="price" name="price" max="1000"><br>
            <label for="seller">Seller</label><br>
            <input type="text" pattern="[A-Za-z]+ [A-Za-z]+" placeholder="first and last name" id="seller" name="seller" size="50"><br>
            <label for="image">Image URl</label><br>
            <textarea id="image" name="image" rows="2" cols="70"required></textarea><br>
            <p id="contactInfo">Contact Info</p>
            <label for="email">RPI Email</label>
            <input type="email" placeholder="rcsid@rpi.edu" id="email" name="email" pattern="[a-zA-Z0-9._]+@rpi\.edu" required><br>
            <label for="phoneNum">Phone Number</label>
            <input type="tel" placeholder="format: 518-100-001" id="phoneNum" name="phoneNum" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}"><br>
            <div class="agree">
                <input type="checkbox" id="agreement" name="agreement" required>
                <label for="agreement">By submitting, I agree that the information provided is accurate to the best of my knowledge.</label>
            </div>
            <div class="buttoncontainer">
                <button type="submit" id="post">Post Service</button>
            </div>
        </form>
    </div>
    </main>
    <div id="footer">
      <p>2024 RPI Marketplace. All rights reserved.</p>
   </div>
    <script src="listingform.js"></script>
</body>