<?php

include 'connect.php';

$faculty_id = isset($_SESSION['faculty_id']) ? $_SESSION['faculty_id'] : null;

$query = "SELECT user_id FROM attendance_log where status = 'Present' AND faculty_id = '$faculty_id' ORDER BY attendatetime DESC LIMIT 1";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $user_id = $row['user_id'];

    // Query to get all attendance dates for the user
    $ui_query = "SELECT attendatetime FROM attendance_log WHERE user_id = '$user_id'  AND faculty_id = '$faculty_id'";
    $ui_result = mysqli_query($conn, $ui_query);

    $dates = [];

    if ($ui_result && mysqli_num_rows($ui_result) > 0) {
        while ($ui_row = mysqli_fetch_assoc($ui_result)) {
            $dates[] = date('Y-m-d', strtotime($ui_row['attendatetime']));
        }
    }

    // Output the dates in JSON format for use in Flatpickr
    header('Content-Type: application/json');
    echo json_encode($dates);
} else {
    // If no results, return an empty JSON array
    header('Content-Type: application/json');
    echo json_encode([]);
}
