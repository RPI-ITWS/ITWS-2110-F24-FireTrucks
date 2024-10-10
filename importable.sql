CREATE TABLE `listingData` (
   `ListingId` smallint unsigned NOT NULL AUTO_INCREMENT,
   `Name` varchar(100) NOT NULL,
   `Seller` varchar(100) NOT NULL,
   `Price` float NOT NULL,
   `Description` varchar(300) NOT NULL,
   `PhoneNumber` varchar(100) NOT NULL,
   `Email` varchar(100) NOT NULL,
   PRIMARY KEY (`ListingId`)
);

CREATE TABLE `auctionsData` (
   `AuctionId` smallint unsigned NOT NULL AUTO_INCREMENT,
   `Name` varchar(100) NOT NULL,
   `Seller` varchar(100) NOT NULL,
   `Description` varchar(300) NOT NULL,
   `TopBid` float NOT NULL,
   `TopName` varchar(100) NOT NULL,
   `TopPhoneNumber` varchar(100) NOT NULL,
   `NextBid` float NOT NULL,
   `NextName` varchar(100) NOT NULL,
   `NextPhoneNumber` varchar(100) NOT NULL,
   `TimePosted` datetime NOT NULL,
   `TimeEnd` datetime NOT NULL,
   PRIMARY KEY (`AuctionId`)
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
    PRIMARY KEY (`ServiceId`)
);

CREATE TABLE `giveawayData` (
    `GiveawayId` smallint unsigned NOT NULL AUTO_INCREMENT,
    `Name` varchar(100) NOT NULL,
    `Seller` varchar(100) NOT NULL,
    `Description` varchar(300) NOT NULL,
    `TimePosted` datetime NOT NULL,
    `TimeEnd` datetime NOT NULL,
    `Entries` JSON NOT NULL,
    PRIMARY KEY (`GiveawayId`)
);

