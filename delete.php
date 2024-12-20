<?php
session_start();

include "connect.php";


if ($_SESSION['role'] === "Admin" && isset($_GET['id'])) {
    $id = $_GET['id'];

    $d_query = "DELETE from user_acount where id = '$id' limit 1";
    $d_result = mysqli_query($conn, $d_query);

    if ($d_result) {
        $s_query = "SELECT id from user_acount where id = '$id' limit 1";
        $s_result = mysqli_query($conn, $s_query);

        if (mysqli_num_rows($s_result) === 0) {

            $_SESSION['notifChange'] = "Successfully Deleted";

            header('Location: users');
            exit;
        }
    }
}

if ($_SESSION['role'] === "Admin" && isset($_GET['itemid'])) {
    $id = $_GET['itemid'];

    $d_query = "DELETE from items where id = '$id' limit 1";
    $d_result = mysqli_query($conn, $d_query);

    if ($d_result) {
        $s_query = "DELETE from inventory where item_id = '$id' limit 1";
        $s_result = mysqli_query($conn, $s_query);

        if ($s_result) {

            $_SESSION['notifChange'] = "Successfully Deleted";

            header('Location: additem');
            exit;
        }
    }
}


if (($_SESSION['role'] === "Admin" || $_SESSION['role'] === "Faculty") && isset($_GET['studentid'])) {
    $id = $_GET['studentid'];

    $d_query = "SELECT id from student where id = '$id' limit 1";
    $d_result = mysqli_query($conn, $d_query);

    if ($d_result) {
        $s_query = "DELETE from student where id = '$id' limit 1";
        $s_result = mysqli_query($conn, $s_query);

        if ($s_result) {

            $_SESSION['notifChange'] = "Successfully Deleted";

            header('Location: students');
            exit;
        }
    }
}
