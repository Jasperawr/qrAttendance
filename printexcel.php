<?php
require 'vendor/autoload.php';
include "connect.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Prevent output before headers
ob_start();

// Retrieve filter values from POST
$class = $_POST['class'] ?? '';
$semester = $_POST['semester'] ?? '';
$academic_year = $_POST['academic_year'] ?? '';
$room = $_POST['room'] ?? '';
$program = $_POST['program'] ?? '';
$startDate = $_POST['start'] ?? '';
$endDate = $_POST['end'] ?? '';

// Ensure the request is valid
if (!isset($_POST['generate_excel']) || empty($startDate) || empty($endDate)) {
    die("Error: Please provide valid parameters.");
}

// Construct query with filters
$query = "SELECT student.name, student.student_number, student.email, attendance_log.status 
          FROM attendance_log 
          INNER JOIN student ON attendance_log.user_id = student.user_id 
          WHERE attendance_log.attendatetime BETWEEN '$startDate' AND '$endDate'";

if (!empty($class)) {
    $query .= " AND student.class = '$class'";
}
if (!empty($semester)) {
    $query .= " AND student.semester = '$semester'";
}
if (!empty($academic_year)) {
    $query .= " AND student.academic_year = '$academic_year'";
}
if (!empty($room)) {
    $query .= " AND attendance_log.room = '$room'";
}
if (!empty($program)) {
    $query .= " AND student.program = '$program'";
}

$result = mysqli_query($conn, $query);
if (!$result) {
    die("Error executing query: " . mysqli_error($conn));
}

// Create a new spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Attendance Report');

// Set headers
$sheet->setCellValue('A1', 'No.');
$sheet->setCellValue('B1', 'Student Name');
$sheet->setCellValue('C1', 'Student Number');
$sheet->setCellValue('D1', 'Email');
$sheet->setCellValue('E1', 'Present');
$sheet->setCellValue('F1', 'Absent');

$rowNum = 2;
$index = 1;
$attendanceCounts = [];

while ($row = mysqli_fetch_assoc($result)) {
    $studentKey = $row['student_number'];

    if (!isset($attendanceCounts[$studentKey])) {
        $attendanceCounts[$studentKey] = [
            'name' => $row['name'],
            'student_number' => $row['student_number'],
            'email' => $row['email'],
            'Present' => 0,
            'Absent' => 0
        ];
    }

    if ($row['status'] == 'Present') {
        $attendanceCounts[$studentKey]['Present']++;
    } else {
        $attendanceCounts[$studentKey]['Absent']++;
    }
}

// Populate Excel with attendance summary
foreach ($attendanceCounts as $student) {
    $sheet->setCellValue('A' . $rowNum, $index++);
    $sheet->setCellValue('B' . $rowNum, $student['name']);
    $sheet->setCellValue('C' . $rowNum, $student['student_number']);
    $sheet->setCellValue('D' . $rowNum, $student['email']);
    $sheet->setCellValue('E' . $rowNum, $student['Present']);
    $sheet->setCellValue('F' . $rowNum, $student['Absent']);
    $rowNum++;
}

// Close database connection
mysqli_close($conn);

// Clean output buffer before setting headers
ob_end_clean();

// Set HTTP headers for file download
$fileName = 'attendance_report_' . date('Ymd_His') . '.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $fileName . '"');
header('Cache-Control: max-age=0');

// Save and output the file to the browser
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
