<?php
date_default_timezone_set('Asia/Manila');
$currentDateTime = date('Y-m-d H:i:s');

session_start();

include 'connect.php';

if (isset($_POST['scannedData'])) {

    $scannedData = mysqli_real_escape_string($conn, $_POST['scannedData']);
    $data = explode('|', $scannedData);
    $type = $data[0];
    $unique_id = $data[1];
    $name = isset($data[2]) ? $data[2] : null;
    $faculty_id = isset($_SESSION['faculty_id']) ? $_SESSION['faculty_id'] : null;

    // Dropdown sessions


    if ($type === 'inventory') {
        $query = "INSERT INTO faculty_inventory_logs (faculty_id, item_id, datetime) VALUES ( '$faculty_id' , '$unique_id', '$currentDateTime')";

        if (mysqli_query($conn, $query)) {
            echo "True";
        } else {
            echo "Error storing data: " . mysqli_error($conn);
        }
    }

    if ($type === "attendance") {

        // Check if the student exists
        $checkid = "SELECT * FROM student WHERE user_id = '$unique_id'";
        $result = mysqli_query($conn, $checkid);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);

            $class = $row['class'];
            $program = $row['program'];
            $semester = $row['semester'];
            $academic_year = $row['academic_year'];
            $year_level = $row['year_level'];
            $room = isset($_SESSION['room']) ? $_SESSION['room'] : null;

            $attendanceCheckQuery = "SELECT * FROM attendance_log WHERE user_id = '$unique_id' AND DATE(attendatetime) = CURDATE()";
            $attendanceCheckResult = mysqli_query($conn, $attendanceCheckQuery);

            if (mysqli_num_rows($attendanceCheckResult) == 0) {
                $query = "INSERT INTO attendance_log (user_id, status, class, program, academic_year, year_level, semester, room, faculty_id, attendatetime) 
                     VALUES ('$unique_id', 'Present', '$class', '$program', '$academic_year', '$year_level', '$semester', '$room', '$faculty_id', '$currentDateTime')";

                if (mysqli_query($conn, $query)) {
                    echo "QR code scanned succesfully";
                } else {
                    echo "Error" . mysqli_error($conn);
                }
            } else {
                echo "This student is scanned already";
            }
        } else {
            echo "No student found with the given unique_id.";
        }
    }
} else {
    echo "No data received.";
}
