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

// Query to fetch and group logins by month
$query = "
    SELECT DATE_FORMAT(login_time, '%Y-%m') AS month, COUNT(*) AS login_count
    FROM faculty_logins
    WHERE faculty_id = '$faculty_id'
    GROUP BY month
    ORDER BY month ASC
";
$result = mysqli_query($conn, $query);

// Initialize an array to store monthly login data
$monthlyData = [];

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $monthlyData[] = [
            'month' => $row['month'],
            'count' => (int)$row['login_count']
        ];
    }
}

// Output the monthly data in JSON format
header('Content-Type: application/json');
echo json_encode($monthlyData);
