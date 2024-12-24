<?php
require 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['token'])) {
    $token = $_GET['token'];

    // Validate token
    $query = "SELECT userid, expiry FROM password_resets WHERE token = '$token' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $reset = mysqli_fetch_assoc($result);
        $expiry = $reset['expiry'];

        if (strtotime($expiry) > time()) {
            // Token is valid and not expired
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $newPassword = $_POST['password'];
                $confirmPassword = $_POST['confirm_password'];

                if ($newPassword === $confirmPassword) {
                    $hashedPwd = password_hash($newPassword, PASSWORD_DEFAULT);
                    $userid = $reset['userid'];

                    // Update password
                    $updatePassword = "UPDATE user_acount SET password = '$hashedPwd' WHERE id = '$userid'";
                    if (mysqli_query($conn, $updatePassword)) {
                        // Delete the token after successful reset
                        $deleteToken = "DELETE FROM password_resets WHERE token = '$token'";
                        mysqli_query($conn, $deleteToken);

                        echo "Password has been successfully updated.";
                        header("Location: login.php");
                        exit;
                    } else {
                        echo "Error updating password.";
                    }
                } else {
                    echo "Passwords do not match.";
                }
            }
        } else {
            echo "This reset link has expired.";
        }
    } else {
        echo "Invalid token.";
    }
} else {
    echo "No token provided.";
}
