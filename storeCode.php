<?php
date_default_timezone_set('Asia/Manila');
$currentDateTime = date('Y-m-d H:i:s');

session_start();

include 'connect.php';

if (isset($_POST['scannedData'])) {

    $scannedData = mysqli_real_escape_string($conn, $_POST['scannedData']);
    list($type, $unique_id, $name,) = explode('|', $scannedData);
    $userid = isset($_SESSION['faculty_id']) ? $_SESSION['faculty_id'] : null;

    if ($type === 'inventory') {
        $query = "INSERT INTO faculty_inventory_logs (faculty_id, item_id, datetime) VALUES ( '$userid' , '$unique_id', '$currentDateTime')";

        if (mysqli_query($conn, $query)) {
            echo "True";
        } else {
            echo "Error storing data: " . mysqli_error($conn);
        }
    }

    if ($type === "attendance") {

        $checkid = "SELECT * FROM student WHERE user_id = '$unique_id'";
        $result = mysqli_query($conn, $checkid);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $yr_sec = $row['yr_sec'];
            $group_no = $row['group_no'];

            $query = "INSERT INTO attendance_log (user_id, status, attendatetime, yr_sec, group_no) 
              VALUES ('$unique_id', 'Present', '$currentDateTime', '$yr_sec', '$group_no')";

            if (mysqli_query($conn, $query)) {
                echo "Attendance recorded successfully!";
            } else {
                echo "Error storing data: " . mysqli_error($conn);
            }
        } else {
            echo "No student found with the given unique_id.";
        }
    }
} else {
    echo "No data received.";
}
