CREATE TABLE listingData (
   `ListingId` smallint unsigned NOT NULL AUTO_INCREMENT,
   `Name` varchar(100) NOT NULL,
   `Seller` varchar(100) NOT NULL,
   `Price` float NOT NULL,
   `Description` varchar(300) NOT NULL,
   `PhoneNumber` varchar(100) NOT NULL,
   `Email` varchar(100) NOT NULL,
   `image_url` varchar(300) NOT NULL,
   `UserId` smallint unsigned NOT NULL,
   `isGiveaway` tinyint(1) NOT NULL DEFAULT 0,
   `isAuction` tinyint(1) NOT NULL DEFAULT 0,
   PRIMARY KEY (ListingId)
);

CREATE TABLE auctionsData (
   `id` INT AUTO_INCREMENT PRIMARY KEY,
   `title` VARCHAR(255) NOT NULL,
   `description` TEXT NOT NULL,
   `image_url` VARCHAR(255) NOT NULL,
   `starting_bid` DECIMAL(10, 2) NOT NULL,
   `time_posted` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   `time_end` DATETIME NOT NULL
);

CREATE TABLE bidsData (
   `id` INT AUTO_INCREMENT PRIMARY KEY,
   `auction_id` INT NOT NULL,
   `bidder_id` INT NOT NULL,
   `bidder_name` VARCHAR(255),
   `bid_amount` DECIMAL(10, 2) NOT NULL,
   `bid_time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   `anonymous` BOOLEAN DEFAULT 0,
   FOREIGN KEY (auction_id) REFERENCES auctionsData(id)
);

CREATE TABLE `loginData` (
    `UserId` smallint unsigned NOT NULL AUTO_INCREMENT,
    `Username` varchar(100) NOT NULL,
    `Password` varchar(100) NOT NULL,
    `Name` varchar(100) NOT NULL,
    `PhoneNumber` varchar(100) NOT NULL,
    `Email` varchar(100) NOT NULL,
    PRIMARY KEY (`UserId`)
);

CREATE TABLE `servicesData` (
   `ServiceId` smallint unsigned NOT NULL AUTO_INCREMENT,
   `Name` varchar(100) NOT NULL,
   `Seller` varchar(100) NOT NULL,
   `Price` float NOT NULL,
   `Description` varchar(300) NOT NULL,
   `PhoneNumber` varchar(100) NOT NULL,
   `Email` varchar(100) NOT NULL,
   `category` varchar(100) NOT NULL,
   `image_url` varchar(300) NOT NULL,
   PRIMARY KEY (`ServiceId`)
);

CREATE TABLE `giveawayData` (
    `giveaway_id` smallint unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NOT NULL,
    `image_url` VARCHAR(255) NOT NULL,
    `seller` varchar(100) NOT NULL,
    `description` varchar(300) NOT NULL,
    `time_posted` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `time_end` DATETIME NOT NULL,
    `winnerId` INT,
    PRIMARY KEY (`giveaway_id`)
);

CREATE TABLE giveawayEntreesData (
   `id` INT AUTO_INCREMENT PRIMARY KEY,
   `giveaway_id` SMALLINT UNSIGNED NOT NULL,
   `name` VARCHAR(255),
   `user_id` INT NOT NULL,
   `entree_time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   FOREIGN KEY (giveaway_id) REFERENCES giveawayData(giveaway_id)
);


