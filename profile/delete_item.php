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
    header("location: ../login/login.php");
    exit;
} 

$userId = $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'];
    $id = intval($_POST['id']); 

    if ($type === 'listing') {
        $sqlCheck = "SELECT * FROM listingData WHERE ListingId = ? AND UserId = ?";
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
            header("location: profile.php");
            exit;
        } else {
            echo "You do not have permission to remove this listing.";
            header("location: profile.php");
            exit;
        }
    } elseif ($type === 'service') {
        $sqlCheck = "SELECT * FROM servicesData WHERE ServiceId = ? AND UserId = ?";
        $stmt = $conn->prepare($sqlCheck);
        $stmt->bind_param("ii", $id, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            // Delete the service
            $sqlDelete = "DELETE FROM servicesData WHERE ServiceId = ?";
            $stmtDel = $conn->prepare($sqlDelete);
            $stmtDel->bind_param("i", $id);
            $stmtDel->execute();
            echo "Service removed successfully.";
            header("location: profile.php");
            exit;
        } else {
            echo "You do not have permission to remove this service.";
            header("location: profile.php");
            exit;
        }
    } elseif ($type === 'auction') {
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
            header("location: profile.php");
            exit;
        } else {
            echo "You do not have permission to remove this auction.";
            header("location: profile.php");
            exit;
        }
    } elseif ($type === 'giveaway') {    
        $sqlCheck = "
            SELECT gd.* 
            FROM giveawayData gd
            INNER JOIN users u ON u.UserId = gd.userID
            WHERE gd.giveaway_id = ? AND u.UserId = ?
        ";
        $stmt = $conn->prepare($sqlCheck);
        $stmt->bind_param("ii", $id, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            // Delete the giveaway and its entries
            $sqlDelete = "DELETE FROM giveawayData WHERE giveaway_id = ?";
            $stmtDel = $conn->prepare($sqlDelete);
            $stmtDel->bind_param("i", $id);
            $stmtDel->execute();

            $sqlDelEntries = "DELETE FROM giveawayEntreesData WHERE giveaway_id = ?";
            $stmtEntries = $conn->prepare($sqlDelEntries);
            $stmtEntries->bind_param("i", $id);
            $stmtEntries->execute();

            echo "Giveaway removed successfully.";
            header("location: profile.php");
            exit;
        } else {
            echo "You do not have permission to remove this giveaway.";
            header("location: profile.php");
            exit;
        }
    } else {
        echo "Invalid type.";
        header("location: profile.php");
        exit;
    }
} else {
    echo "Invalid request method.";
    header("location: profile.php");
    exit;
}

$conn->close();
?>
