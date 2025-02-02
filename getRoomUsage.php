<?php
session_start();
include "connect.php";

// Retrieve the faculty ID from the session
$faculty_id = isset($_SESSION['faculty_id']) ? $_SESSION['faculty_id'] : null;

if ($faculty_id === null) {
    die('Faculty ID is not set in the session!');
}

// Query to get room usage by month, and Present/Absent counts
$query = "
    SELECT 
        room,
        DATE_FORMAT(attendatetime, '%Y-%m') AS month,
        status,
        COUNT(*) AS status_count
    FROM 
        attendance_log
    WHERE 
        faculty_id = '$faculty_id'
    GROUP BY 
        room, month, status
    ORDER BY 
        month, room, status
";

$result = mysqli_query($conn, $query);

if (!$result) {
    die('Query failed: ' . mysqli_error($conn));
}

// Prepare arrays for months and room usage
$months = [];
$roomUsage = [];

// Process the query results
while ($row = mysqli_fetch_assoc($result)) {
    $room = $row['room'];
    $month = $row['month'];
    $status = $row['status']; // Either 'Present' or 'Absent'
    $status_count = (int) $row['status_count'];

    // Store unique months
    if (!in_array($month, $months)) {
        $months[] = $month;
    }

    // Initialize the room data for the month if not set
    if (!isset($roomUsage[$room][$month])) {
        $roomUsage[$room][$month] = [
            'Present' => 0,
            'Absent' => 0,
            'usage_count' => 0 // Total usage
        ];
    }

    // Add counts to Present/Absent
    $roomUsage[$room][$month][$status] = $status_count;

    // Update the total usage count (Present + Absent)
    $roomUsage[$room][$month]['usage_count'] += $status_count;
}

// Sort months in descending order
rsort($months);

// Prepare the data for the chart
$roomDataForChart = [];
foreach ($roomUsage as $room => $data) {
    foreach ($months as $month) {
        $roomDataForChart[] = [
            'label' => 'Room ' . $room,
            'month' => $month,
            'Present' => isset($data[$month]['Present']) ? $data[$month]['Present'] : 0,
            'Absent' => isset($data[$month]['Absent']) ? $data[$month]['Absent'] : 0,
            'usage_count' => isset($data[$month]['usage_count']) ? $data[$month]['usage_count'] : 0,
            'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
            'borderColor' => 'rgba(75, 192, 192, 1)',
            'borderWidth' => 1
        ];
    }
}

// Return the data as JSON
$response = [
    'months' => $months,         // Available months
    'roomData' => $roomDataForChart  // Room usage data
];

header('Content-Type: application/json');
echo json_encode($response);
exit();
