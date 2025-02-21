<?php
//
include "connect.php";
date_default_timezone_set('Asia/Manila');

function totalStudent($conn)
{
    $faculty_id = isset($_SESSION['faculty_id']) ? $_SESSION['faculty_id'] : null;

    // Build the query with COUNT to get the total present students
    $pr_query = "
    SELECT 
        COUNT(*) AS total_present
    FROM 
        attendance_log a 
    LEFT JOIN 
        student s
    ON 
        s.user_id = a.user_id
    WHERE 
        s.faculty_id = '$faculty_id'
    ";

    $pr_result = mysqli_query($conn, $pr_query);

    if ($pr_result) {
        $row = mysqli_fetch_assoc($pr_result);
        return $row['total_present'] ? $row['total_present'] : 0;
    } else {
        // Log error for debugging
        error_log("Query failed: " . mysqli_error($conn));
        return 0; // Return 0 if the query fails
    }
}

function present($conn)
{
    $faculty_id = isset($_SESSION['faculty_id']) ? $_SESSION['faculty_id'] : null;

    // Build the query with COUNT to get the total present students
    $pr_query = "
    SELECT 
        COUNT(*) AS total_present
    FROM 
        attendance_log a 
    LEFT JOIN 
        student s
    ON 
        s.user_id = a.user_id
    WHERE 
        a.status = 'Present'
        AND s.faculty_id = '$faculty_id'
    ";

    $pr_result = mysqli_query($conn, $pr_query);

    if ($pr_result) {
        $row = mysqli_fetch_assoc($pr_result);
        return $row['total_present'] ? $row['total_present'] : 0;
    } else {
        // Log error for debugging
        error_log("Query failed: " . mysqli_error($conn));
        return 0; // Return 0 if the query fails
    }
}

function late($conn)
{
    // $section = $_SESSION['section'];
    // $group_no = $_SESSION['groupnumber'];

    // $l_query = "Select * from attendance_log where status = 'late' and yr_sec = '$section' and group_no = '$group_no'";
    // $l_result = mysqli_query($conn, $l_query);

    // if (mysqli_num_rows($l_result) > 0) {
    //     return mysqli_num_rows($l_result);
    // } else {
    //     return 0;
    // }
}

function absent($conn)
{
    $faculty_id = isset($_SESSION['faculty_id']) ? $_SESSION['faculty_id'] : null;

    // Build the query with COUNT to get the total present students
    $pr_query = "
    SELECT 
        COUNT(*) AS total_absent
    FROM 
        attendance_log a 
    LEFT JOIN 
        student s
    ON 
        s.user_id = a.user_id
    WHERE 
        a.status = 'Absent'
        AND s.faculty_id = '$faculty_id'
    ";

    $pr_result = mysqli_query($conn, $pr_query);

    if ($pr_result) {
        $row = mysqli_fetch_assoc($pr_result);
        return $row['total_absent'] ? $row['total_absent'] : 0;
    } else {
        // Log error for debugging
        error_log("Query failed: " . mysqli_error($conn));
        return 0; // Return 0 if the query fails
    }
}

function allItms($conn)
{

    $query = "SELECT id from items";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        return mysqli_num_rows($result);
    } else {
        return 0;
    }
}

function allInv($conn)
{

    $query = "
        SELECT 
            l.id AS log_id, 
            l.faculty_id, 
            l.quantity, 
            l.datetime AS log_datetime, 
            i.item_id, 
            i.item_name, 
            i.datetime AS item_datetime,
            u.name, 
            u.userid, 
            u.email
        FROM 
            faculty_inventory_logs l
        INNER JOIN 
            items i ON l.item_id = i.item_id
        INNER JOIN 
            user_acount u ON l.faculty_id = u.id
        ORDER BY 
            l.id DESC;
    ";

    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        return mysqli_num_rows($result);
    } else {
        return 0;
    }
}

function allItmsToday($conn)
{
    // $section = $_SESSION['section'];
    // $group_no = $_SESSION['groupnumber'];

    // $query = "SELECT id from items where status = 'absent' and yr_sec = '$section' and group_no = '$group_no'";
    // $result = mysqli_query($conn, $query);

    // if (mysqli_num_rows($result) > 0) {
    //     return mysqli_num_rows($result);
    // } else {
    //     return 0;
    // }
}

function presentToday($conn)
{
    // Retrieve session variables
    $class = isset($_SESSION['class']) ? $_SESSION['class'] : null;
    $program = isset($_SESSION['program']) ? $_SESSION['program'] : null;
    $semester = isset($_SESSION['semester']) ? $_SESSION['semester'] : null;
    $year_level = isset($_SESSION['year_level']) ? $_SESSION['year_level'] : null;
    $academic_year = isset($_SESSION['academic_year']) ? $_SESSION['academic_year'] : null;
    $room = isset($_SESSION['room']) ? $_SESSION['room'] : null;
    $faculty_id = isset($_SESSION['faculty_id']) ? $_SESSION['faculty_id'] : null;

    $currdate = date('Y-m-d'); // Date format for the query should be 'YYYY-MM-DD'

    // Build the query
    $pr_query = "
    SELECT 
       COUNT(*) AS total_present
    FROM 
        attendance_log
    WHERE 
        faculty_id = '$faculty_id' AND
        class = '$class' AND
        semester = '$semester' AND
        program = '$program' AND
        room = '$room' AND
        status = 'Present' AND
        DATE(attendatetime) = '$currdate' AND
        academic_year = '$academic_year'
    ";

    $pr_result = mysqli_query($conn, $pr_query);

    if ($pr_result) {
        if (mysqli_num_rows($pr_result) > 0) {
            $row = mysqli_fetch_assoc($pr_result);
            return $row['total_present'];
        } else {
            return 0;
        }
    } else {
        error_log("Query failed: " . mysqli_error($conn));
        return 0;
    }
}

function absentToday($conn)
{

    // Retrieve session variables
    $class = isset($_SESSION['class']) ? $_SESSION['class'] : null;
    $program = isset($_SESSION['program']) ? $_SESSION['program'] : null;
    $semester = isset($_SESSION['semester']) ? $_SESSION['semester'] : null;
    $year_level = isset($_SESSION['year_level']) ? $_SESSION['year_level'] : null;
    $academic_year = isset($_SESSION['academic_year']) ? $_SESSION['academic_year'] : null;
    $room = isset($_SESSION['room']) ? $_SESSION['room'] : null;
    $faculty_id = isset($_SESSION['faculty_id']) ? $_SESSION['faculty_id'] : null;

    $currdate = date('Y-m-d'); // Date format for the query should be 'YYYY-MM-DD'

    // Build the query
    $pr_query = "
    SELECT 
       COUNT(*) AS total_present
    FROM 
        attendance_log
    WHERE 
        faculty_id = '$faculty_id' AND
        class = '$class' AND
        semester = '$semester' AND
        program = '$program' AND
        room = '$room' AND
        status = 'Absent' AND
        DATE(attendatetime) = '$currdate' AND
        academic_year = '$academic_year'
    ";

    $pr_result = mysqli_query($conn, $pr_query);

    if ($pr_result) {
        if (mysqli_num_rows($pr_result) > 0) {
            $row = mysqli_fetch_assoc($pr_result);
            return $row['total_present'];
        } else {
            return 0;
        }
    } else {
        error_log("Query failed: " . mysqli_error($conn));
        return 0;
    }

    // // Retrieve session variables
    // $class = isset($_SESSION['class']) ? $_SESSION['class'] : null;
    // $program = isset($_SESSION['program']) ? $_SESSION['program'] : null;
    // $semester = isset($_SESSION['semester']) ? $_SESSION['semester'] : null;
    // // $year_level = isset($_SESSION['year_level']) ? $_SESSION['year_level'] : null;
    // $academic_year = isset($_SESSION['academic_year']) ? $_SESSION['academic_year'] : null;
    // $room = isset($_SESSION['room']) ? $_SESSION['room'] : null;
    // $faculty_id = isset($_SESSION['faculty_id']) ? $_SESSION['faculty_id'] : null;

    // $currdate = date('Y-m-d'); // Date format for the query should be 'YYYY-MM-DD'

    // // Build the query to count absent students (those not marked as 'Present')
    // $pr_query = "
    // SELECT 
    //     COUNT(*) AS total_absent
    // FROM 
    //     student
    // LEFT JOIN 
    //     attendance_log ON student.user_id = attendance_log.user_id 
    //     AND DATE(attendance_log.attendatetime) = '$currdate' 
    //     AND attendance_log.status = 'Present'
    //     AND attendance_log.room = '$room'
    //     AND attendance_log.faculty_id = '$faculty_id' 
    //     AND attendance_log.class = '$class' 
    //     AND attendance_log.semester = '$semester' 
    //     AND attendance_log.program = '$program' 
    //     AND attendance_log.academic_year = '$academic_year'
    // WHERE 
    //     student.faculty_id = '$faculty_id' 
    //     AND student.class = '$class' 
    //     AND student.semester = '$semester' 
    //     AND student.program = '$program' 
    //     AND student.academic_year = '$academic_year'
    //     AND attendance_log.user_id IS NULL  -- This checks for students who are not in attendance_log
    // ";

    // $pr_result = mysqli_query($conn, $pr_query);

    // if ($pr_result) {
    //     $row = mysqli_fetch_assoc($pr_result);
    //     return $row['total_absent']; // Return the number of absent students
    // } else {
    //     error_log("Query failed: " . mysqli_error($conn));
    //     return 0; // Return 0 if the query fails
    // }
}


function totalStudentToday($conn)
{
    // Retrieve session variables
    $class = isset($_SESSION['class']) ? $_SESSION['class'] : null;
    $program = isset($_SESSION['program']) ? $_SESSION['program'] : null;
    $semester = isset($_SESSION['semester']) ? $_SESSION['semester'] : null;
    $year_level = isset($_SESSION['year_level']) ? $_SESSION['year_level'] : null;
    $academic_year = isset($_SESSION['academic_year']) ? $_SESSION['academic_year'] : null;
    $room = isset($_SESSION['room']) ? $_SESSION['room'] : null;
    $faculty_id = isset($_SESSION['faculty_id']) ? $_SESSION['faculty_id'] : null;

    $currdate = date('Y-m-d');  // Current date for comparison

    // Build the query with COUNT to get the total present students
    $pr_query = "
    SELECT 
        COUNT(*) AS total_present
    FROM 
        student
    LEFT JOIN 
        attendance_log ON student.user_id = attendance_log.user_id 
        AND DATE(attendance_log.attendatetime) = '$currdate' 
        AND attendance_log.status = 'Present'
        AND attendance_log.room = '$room'
        AND attendance_log.faculty_id = '$faculty_id' 
        AND attendance_log.class = '$class' 
        AND attendance_log.semester = '$semester' 
        AND attendance_log.program = '$program' 
        AND attendance_log.academic_year = '$academic_year'
    WHERE 
        student.faculty_id = '$faculty_id' 
        AND student.class = '$class' 
        AND student.semester = '$semester' 
        AND student.program = '$program' 
        AND student.academic_year = '$academic_year'
    ";

    $pr_result = mysqli_query($conn, $pr_query);

    if ($pr_result) {
        $row = mysqli_fetch_assoc($pr_result);
        return $row['total_present'] ? $row['total_present'] : 0;
    } else {
        // Log error for debugging
        error_log("Query failed: " . mysqli_error($conn));
        return 0; // Return 0 if the query fails
    }
}
