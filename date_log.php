<?php

session_start();

include 'connect.php';

// Get faculty_id from the session
$faculty_id = isset($_SESSION['faculty_id']) ? $_SESSION['faculty_id'] : null;

// Check if faculty_id exists in the session
if ($faculty_id === null) {
    header('Content-Type: application/json');
    echo json_encode([]);
    exit();
}

// Query to fetch login times for the faculty
$query = "SELECT login_time FROM faculty_logins WHERE faculty_id = '$faculty_id' ORDER BY login_time DESC";
$result = mysqli_query($conn, $query);

// Initialize an array to store dates
$dates = [];

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Convert login_time to 'Y-m-d' format and add it to the dates array
        $dates[] = date('Y-m-d', strtotime($row['login_time']));
    }
}

// Output the dates in JSON format for use in Flatpickr
header('Content-Type: application/json');
echo json_encode($dates);
