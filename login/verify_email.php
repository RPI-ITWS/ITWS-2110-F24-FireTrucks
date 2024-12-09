<?php
require_once('config.php');

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if the token exists in the database
    $sql = "SELECT UserId FROM users WHERE verification_token = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $token);

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) == 1) {
                // Token is valid, update email_verified
                $update_sql = "UPDATE users SET email_verified = 1, verification_token = NULL WHERE verification_token = ?";
                if ($update_stmt = mysqli_prepare($conn, $update_sql)) {
                    mysqli_stmt_bind_param($update_stmt, "s", $token);
                    if (mysqli_stmt_execute($update_stmt)) {
                        echo "Your email has been verified successfully!";
                    } else {
                        echo "Error updating verification status. Please try again later.";
                    }
                }
            } else {
                echo "Invalid or expired verification token.";
            }
        } else {
            echo "Database error. Please try again later.";
        }

        mysqli_stmt_close($stmt);
    }
} else {
    echo "No verification token provided.";
}
?>