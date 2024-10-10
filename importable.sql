CREATE TABLE `listingData` (
   `ListingId` smallint unsigned NOT NULL AUTO_INCREMENT,
   `Name` varchar(100) NOT NULL,
   `Seller` varchar(100) NOT NULL,
   `Price` float NOT NULL,
   `PhoneNumber` varchar(100) NOT NULL,
   `Email` varchar(100) NOT NULL,
   PRIMARY KEY (`ListingId`)
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
    `Description` varchar(300) NOT NULL,
    `PhoneNumber` varchar(100) NOT NULL,
    `Email` varchar(100) NOT NULL,
    PRIMARY KEY (`ServiceId`)
);

CREATE TABLE `giveawayData` (
    `GiveawayId` smallint unsigned NOT NULL AUTO_INCREMENT,
    `Name` varchar(100) NOT NULL,
    `Seller` varchar(100) NOT NULL,
    `TimePosted` datetime NOT NULL,
    `TimeEnd` datetime NOT NULL,
    `Entries` JSON NOT NULL,
    PRIMARY KEY (`ServiceId`)
);

