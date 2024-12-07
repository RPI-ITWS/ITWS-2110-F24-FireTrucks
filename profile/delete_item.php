<?php
session_start();
ini_set('display_errors',1);
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
    exit;
} 

$userId = $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'];
    $id = intval($_POST['id']); 

    if ($type === 'listing') {
        // Verify that the listing belongs to this user
        $sqlCheck = "SELECT * FROM listingData WHERE ListingId = ? AND user_id = ?";
        $stmt = $conn->prepare($sqlCheck);
        $stmt->bind_param("ii", $id, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            // Delete the listing
            $sqlDelete = "DELETE FROM listingData WHERE ListingId = ?";
            $stmtDel = $conn->prepare($sqlDelete);
            $stmtDel->bind_param("i", $id);
            $stmtDel->execute();
            echo "Listing removed successfully.";
        } else {
            echo "You do not have permission to remove this listing.";
        }
    } elseif ($type === 'auction') {
        // Verify the auction belongs to the user by checking the host_name
        $sqlCheck = "
            SELECT a.* FROM auctionsData a
            INNER JOIN users u ON u.first_name = a.host_name
            WHERE a.id = ? AND u.UserId = ?
        ";
        $stmt = $conn->prepare($sqlCheck);
        $stmt->bind_param("ii", $id, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            // Delete the auction
            $sqlDelete = "DELETE FROM auctionsData WHERE id = ?";
            $stmtDel = $conn->prepare($sqlDelete);
            $stmtDel->bind_param("i", $id);
            $stmtDel->execute();
            echo "Auction removed successfully.";
        } else {
            echo "You do not have permission to remove this auction.";
        }
    } elseif ($type === 'giveaway') {
        // Verify the giveaway belongs to the user via their name. 
        $sqlCheck = "
            SELECT gd.* 
            FROM giveawayData gd
            INNER JOIN giveawayEntreesData ge ON gd.giveaway_id = ge.giveaway_id
            INNER JOIN users u ON u.first_name = ge.Name
            WHERE gd.giveaway_id = ? AND u.UserId = ?
        ";
        $stmt = $conn->prepare($sqlCheck);
        $stmt->bind_param("ii", $id, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            // Delete the giveaway
            $sqlDelete = "DELETE FROM giveawayData WHERE giveaway_id = ?";
            $stmtDel = $conn->prepare($sqlDelete);
            $stmtDel->bind_param("i", $id);
            $stmtDel->execute();
            $sqlDelEntries = "DELETE FROM giveawayEntreesData WHERE giveaway_id = ?";
            $stmtEntries = $conn->prepare($sqlDelEntries);
            $stmtEntries->bind_param("i", $id);
            $stmtEntries->execute();

            echo "Giveaway removed successfully.";
        } else {
            echo "You do not have permission to remove this giveaway.";
        }
    } else {
        echo "Invalid type.";
    }
} else {
    echo "Invalid request method.";
}

$conn->close();
?>
