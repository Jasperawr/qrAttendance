<?php
session_start();

include "connect.php";

echo "delete";


if ($_SESSION['role'] === "Admin" && isset($_GET['id'])) {
    $id = $_GET['id'];

    $d_query = "DELETE from user_acount where id = '$id' limit 1";
    $d_result = mysqli_query($conn, $d_query);

    if ($d_result) {
        $s_query = "SELECT id from user_acount where id = '$id' limit 1";
        $s_result = mysqli_query($conn, $s_query);

        if (mysqli_num_rows($s_result) === 0) {

            $_SESSION['notifChange'] = "success|Successfully Deleted";

            header('Location: users');
            exit;
        }
    } else {
        echo "No shit";
    }
}

if (isset($_GET['id']) && $_SESSION['role'] != "Admin") {
    $_SESSION['notifChange'] = "error|You are not authorized to use this feature";
    header('Location: users');
    exit;
}

if ($_SESSION['role'] === "Admin" && isset($_GET['itemid'])) {
    $id = $_GET['itemid'];

    $d_query = "DELETE from items where id = '$id' limit 1";
    $d_result = mysqli_query($conn, $d_query);

    if ($d_result) {
        $s_query = "DELETE from inventory where item_id = '$id' limit 1";
        $s_result = mysqli_query($conn, $s_query);

        if ($s_result) {

            $_SESSION['notifChange'] = "success|Successfully Deleted";

            header('Location: additem');
            exit;
        }
    }
}

if (isset($_GET['itemid']) && $_SESSION['role'] != "Admin") {

    $_SESSION['notifChange'] = "error|You are not authorized to use this feature";
    header('Location: additem');
    exit;
}


if (($_SESSION['role'] === "Admin" || $_SESSION['role'] === "Faculty") && isset($_GET['studentid'])) {
    $id = $_GET['studentid'];

    $d_query = "SELECT id from student where id = '$id' limit 1";
    $d_result = mysqli_query($conn, $d_query);

    if ($d_result) {
        $s_query = "DELETE from student where id = '$id' limit 1";
        $s_result = mysqli_query($conn, $s_query);

        if ($s_result) {

            $_SESSION['notifChange'] = "success|Successfully Deleted";

            header('Location: students');
            exit;
        }
    }
}

// This is for deleting student selected
if (($_SESSION['role'] === "Admin" || $_SESSION['role'] === "Faculty") && isset($_POST['deleteAllSelected'])) {
    $delete_ids = isset($_POST['delete_ids']) ? explode(',', $_POST['delete_ids']) : [];

    if (!empty($delete_ids)) {
        // Convert array to a comma-separated string while ensuring only integers are used
        $ids = implode(',', array_map('intval', $delete_ids));

        // Delete query
        $query = "DELETE FROM student WHERE id IN ($ids)";
        if (mysqli_query($conn, $query)) {
            header('Location: students.php'); // Ensure it redirects properly
            exit;
        } else {
            echo "Error deleting records: " . mysqli_error($conn);
        }
    } else {
        echo "No records selected.";
    }
}

