<?php
    // Database connection settings
    $servername = "localhost";
    $username = "phpmyadmin";  // Default XAMPP username
    $password = "Marketplace18";      // Default XAMPP password (usually empty)
    $dbname = "marketplace";    // Replace with your database name, or use 'test'
    // Create connection
    @ $db = new mysqli($servername, $username, $password, $dbname, $port);

    // Check connection
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    } 
     
    $formType = $_POST['formType'];

   if ($formType == 'auction') {
        $title = $_POST['title'];
        $category = $_POST['category'];
        $description = $_POST['description'];
        $startbid = $_POST['startbid'];
        $auctionEnd = $_POST['auctionEnd'];
        $hostName = $_POST['hostName'];
        $email = $_POST['email'];
        $phoneNum = $_POST['phoneNum'];
        $imageUrl = $_POST['image'];

        $insQuery = "INSERT INTO auctionsData (`title`, `category`, `description`, `starting_bid`, `time_end`, `host name`, `email`, `phone`, `image_url`) VALUES(?,?,?,?,?,?,?,?,?)";
        $statement = $db->prepare($insQuery);
        $statement->bind_param("sssdsssss",$title, $category, $description, $startbid, $auctionEnd, $hostName, $email, $phoneNum, $imageUrl);
        if($statement->execute()) {
            echo "<!DOCTYPE html> <html lang='en'>
                <head> <meta charset='utf-8'> <meta name='viewport' content='width=device-width, initial-scale=1.0'> 
                <Title>Success!</Title>
                    <script> setTimeout(function() {
                        window.location.href = '/index.html';}, 2000);
                    </script>
                </head>
                <body>
                    <h1>Posted Sucessfully!</h1>
                </body>
                </html>";
            exit();
        } else {
            echo "Error" . $statement->error;
        }
        $statement->close();
    } elseif ($formType == 'giveaway') {
        $title = $_POST['title'];
        $category = $_POST['category'];
        $description = $_POST['description'];
        $hostName = $_POST['hostName'];
        $giveawayEnd = $_POST['giveawayEnd'];
        $email = $_POST['email'];
        $phoneNum = $_POST['phoneNum'];
        $imageUrl = $_POST['image'];

        $insQuery = "INSERT INTO giveawayData (`name`, `category`, `description`, `giveaway host`, `time_end`, `email`, `phone`, `image_url`) VALUES(?,?,?,?,?,?,?,?)";
        $statement = $db->prepare($insQuery);
        $statement->bind_param("ssssssss",$title, $category, $description, $hostName, $giveawayEnd, $email, $phoneNum, $imageUrl);
        if($statement->execute()) {
            echo "<!DOCTYPE html> <html lang='en'>
            <head> <meta charset='utf-8'> <meta name='viewport' content='width=device-width, initial-scale=1.0'> 
            <Title>Success!</Title>
                <script> setTimeout(function() {
                    window.location.href = '/index.html';}, 2000);
                </script>
            </head>
            <body>
                <h1>Posted Sucessfully!</h1>
            </body>
            </html>";
        exit();
        } else {
            echo "Error" . $statement->error;
        }
        $statement->close();
    } elseif ($formType == 'good') {
        $title = $_POST['title'];
        $category = $_POST['category'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $seller = $_POST['seller'];
        $email = $_POST['email'];
        $phoneNum = $_POST['phoneNum'];
        $imageUrl = $_POST['image'];

        $insQuery = "INSERT INTO listingData (`Name`, `category`, `Description`, `Price`, `Seller`, `Email`, `PhoneNumber`, `image_url`) VALUES(?,?,?,?,?,?,?,?)";
        $statement = $db->prepare($insQuery);
        $statement->bind_param("sssdssss",$title, $category, $description, $price, $seller, $email, $phoneNum, $imageUrl);
        if($statement->execute()) {
            echo "<!DOCTYPE html> <html lang='en'>
            <head> <meta charset='utf-8'> <meta name='viewport' content='width=device-width, initial-scale=1.0'> 
            <Title>Success!</Title>
                <script> setTimeout(function() {
                    window.location.href = '/index.html';}, 2000);
                </script>
            </head>
            <body>
                <h1>Posted Sucessfully!</h1>
            </body>
            </html>";
        exit();
        } else {
            echo "Error" . $statement->error;
        }
        $statement->close();
    } elseif ($formType == 'good') {
        $title = $_POST['title'];
        $category = $_POST['category'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $seller = $_POST['seller'];
        $email = $_POST['email'];
        $phoneNum = $_POST['phoneNum'];
        $imageUrl = $_POST['image'];

        $insQuery = "INSERT INTO servicesData (Name, category, Description, Price, Seller, Email, PhoneNumber, image_url) VALUES(?,?,?,?,?,?,?,?)";
        $statement = $db->prepare($insQuery);
        $statement->bind_param("sssdssss",$title, $category, $description, $price, $seller, $email, $phoneNum, $imageUrl);
        if($statement->execute()) {
            echo "<!DOCTYPE html> <html lang='en'>
            <head> <meta charset='utf-8'> <meta name='viewport' content='width=device-width, initial-scale=1.0'> 
            <Title>Success!</Title>
                <script> setTimeout(function() {
                    window.location.href = '/index.html';}, 2000);
                </script>
            </head>
            <body>
                <h1>Posted Sucessfully!</h1>
            </body>
            </html>";
        exit();
        } else {
            echo "Error" . $statement->error;
        }
        $statement->close();
    }
   $db->close();