<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
require 'configmailer.php';
require 'connect.php'; // Include your database connection script

// Start the session
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    if (!empty($email)) {
        // Check if email exists
        $query = "SELECT id, name FROM user_acount WHERE email = '$email' LIMIT 1";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            $userid = $user['id'];
            $name = $user['name'];

            // Generate a unique token
            $token = bin2hex(random_bytes(32));
            $expiry = date('Y-m-d H:i:s', strtotime('+1 hour')); // Token expires in 1 hour

            // Store the token in the database
            $insertToken = "INSERT INTO password_resets (userid, token, expiry) VALUES ('$userid', '$token', '$expiry')";
            if (mysqli_query($conn, $insertToken)) {
                // Send email
                $resetLink = "http://localhost/qrAttendance/resetPassword.php?token=$token";

                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host       = MAILHOST;
                    $mail->SMTPAuth   = true;
                    $mail->Username   = USERNAME;
                    $mail->Password   = PASSWORD;
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                    $mail->Port       = 465;

                    // Recipients
                    $mail->setFrom(SEND_FROM, SEND_FROM_NAME);
                    $mail->addAddress($email, $name);

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = 'Password Reset Request';
                    $mail->Body    = "
                        <p>Hi $name,</p>
                        <p>We received a request to reset your password. Click the link below to reset it:</p>
                        <p><a href='$resetLink'>Reset Password</a></p>
                        <p>If you didn't request this, you can ignore this email.</p>
                        <p>Thank you!</p>";

                    $mail->send();

                    $_SESSION['notifMessage'] = "success|Email has been sent. Please check your email. Thank you";
                    echo "A reset link has been sent to your email.";

                    header('Location: forgotPassword.php');
                    exit;
                } catch (Exception $e) {
                    echo "Error sending email: {$mail->ErrorInfo}";
                    $_SESSION['notifMessage'] = "error|Problem on email";
                }
            } else {
                echo "Failed to store reset token.";
            }
        } else {
            $_SESSION['notifMessage'] = "error|No user found with this email address.";
            header('Location: forgotPassword.php');
            exit;
        }
    } else {
        $_SESSION['notifMessage'] = "error|Please provide a valid email address.";
        header('Location: forgotPassword.php');
        exit;
    }
}
