<?php
session_start();
require('connect.php');

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM user_acount WHERE email = '$email' limit 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row['password']) && $email === $row['email']) {

                $_SESSION['name'] = $row['name'];
                $_SESSION['faculty_id'] = $row['id'];
                $_SESSION['loggedin'] = "true";

                if ($row['admin'] === 'Y') {
                    $_SESSION['role'] = "Admin";
                } else {
                    $_SESSION['role'] = "Faculty";
                }

                if (isset($_SESSION['faculty_id'])) {
                    $faculty_id = $_SESSION['faculty_id'];
                    $ip_address = $_SERVER['REMOTE_ADDR'];
                    $session_id = session_id();

                    $query = "INSERT INTO faculty_logins (faculty_id, ip_address, session_id) 
                              VALUES ('$faculty_id', '$ip_address', '$session_id')";

                    if (mysqli_query($conn, $query)) {
                        header("Location: home");
                        exit;
                    } else {
                        echo "Error logging login: " . mysqli_error($conn);
                    }
                }

                header("Location: home");
                exit;
            } else {
                header('Location: login.php?error=2');
                exit;
            }
        }
    } else {
        header('Location: login.php?error=1');
        exit;
    }
}

$conn->close();
