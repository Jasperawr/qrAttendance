<?php

session_start();

include "connect.php";
include "sendmail.php";
include "qrGenerator.php";

require 'vendor/autoload.php';

// Load PhpSpreadsheet library
use PhpOffice\PhpSpreadsheet\IOFactory;


date_default_timezone_set('Asia/Manila');

// Generate userid for student
$origid = uniqid();
$hashedid = md5($origid); //hashed id
$currentDate = date("y-m-d h:i:s");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['addstudent']) || isset($_POST['addanother'])) {
        $tempname = $_POST['fname'] . " " . $_POST['mname'] . " " . $_POST['lname'];

        if (!empty($_POST['suffix'])) {
            $tempname .= " " . $_POST['suffix'];
        }

        $name = ucwords($tempname);
        $student_number = $_POST['idnumber'];
        $email = $_POST['email'];

        $year_level = $_POST['year_level'];
        $semester = $_POST['semester'];
        $academic_year = $_POST['academic_year'];
        $program = $_POST['program'];
        $class = $_POST['class'];

        // Check if the user exists
        $query = "SELECT name, email FROM student WHERE name = '$name' AND email = '$email' LIMIT 1";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) == 0) {
            $avatarBlob = null; // Initialize with null

            if (!empty($_FILES['studentavatar']['name'])) {
                $tmp_name = $_FILES['studentavatar']['tmp_name'];
                $avatarBlob = addslashes(file_get_contents($tmp_name)); // Read file content as BLOB
            }

            $facultyId = $_SESSION['faculty_id'];

            $sql = "INSERT INTO `student` (user_id, name, email, student_number, class, semester, year_level, academic_year, program, faculty_id, avatar, datetime) 
                        VALUES ('$origid', '$name', '$email', '$student_number', '$class', '$semester', '$year_level', '$academic_year', '$program', '$facultyId', '$avatarBlob', '$currentDate')";
            $sql_result = mysqli_query($conn, $sql);

            if ($sql_result) {
                sendMail($email, $origid, $name);

                if (isset($_POST['addstudent'])) {
                    if (isset($_SESSION['studentExist'])) {
                        unset($_SESSION['studentExist']);
                    }
                    header("Location: students");
                    exit;
                } elseif (isset($_POST['addanother'])) {
                    if (isset($_SESSION['studentExist'])) {
                        unset($_SESSION['studentExist']);
                    }
                    header("Location: addstudent?typeUpload=single");
                    exit;
                }
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        } else {
            $_SESSION['studentExist'] = "Student already exists.";
            header("Location: addstudent");
            exit;
        }
    } else if (isset($_POST['adduser'])) {

        $tempname = $_POST['fname'] . " " . $_POST['lname'];
        $name = ucwords($tempname);
        $email = $_POST['email'];
        $password = $_POST['password'];
        $hashedpwd = password_hash($password, PASSWORD_DEFAULT);

        // Check if the user is exist 
        $query = "SELECT name, email FROM user_acount WHERE name = '$name' AND email = '$email' limit 1";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) == 0) {

            $sql = "INSERT INTO `user_acount`(userid, name, email, password, admin, datetime) VALUES ('$origid','$name ','$email','$hashedpwd','N','$currentDate')";
            $sql_result = mysqli_query($conn, $sql);

            if ($sql_result) {

                sendCredentials($email, $password, $name);

                if (isset($_SESSION['userExist'])) {
                    unset($_SESSION['userExist']);
                }

                header("Location: users");
                exit;
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        } else {
            $_SESSION['userExist'] = "Faculty is already exist. Please submit New Faculty";

            header("Location: users");
            exit;
        }
    } else if (isset($_POST['additem'])) {

        if (!empty($_FILES['itempic']['name'])) {

            $filename = $_FILES['itempic']['name'];
            $type = $_FILES['itempic']['type'];
            $tmp_name = $_FILES['itempic']['tmp_name'];

            $imageData = file_get_contents($tmp_name);
            $imageBase64 = base64_encode($imageData);

            $tempname = $_POST['name'];
            $name = ucwords($tempname);
            // $quantity = $_POST['quantity'];
            // $facultyId = $_POST['facultyId'];

            $data = "inventory|" . $origid . "|" . $name;

            $qr = qrGenerate($data);

            $insertQuery = "INSERT INTO `items` (item_id, item_name, image, qr, datetime) 
                    VALUES ('$origid', '$name', '$imageBase64', '$qr', '$currentDate')";
            $sql_result = mysqli_query($conn, $insertQuery);

            if ($sql_result) {

                $directory = 'avatar/faculty/qrs/' . $origid . '/';
                $imageName = $name . '.png';

                $addqr = "INSERT INTO `qr_code`( item_id, path, name, datetime) VALUES ('$origid','$directory','$imageName','$currentDate')";
                $res = mysqli_query($conn, $addqr);

                if ($res) {
                    // sendCredentials($email, $password, $name);
                    if (isset($_SESSION['userExist'])) {
                        unset($_SESSION['userExist']);
                    }

                    header("Location: inventoryadmin");
                    exit;
                }
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        }
    }

    else if (isset($_POST['uploadExcel'])) {
        if (isset($_FILES['excelFile'])) {
            $fileName = $_FILES['excelFile']['tmp_name'];
            $facultyId = $_SESSION['faculty_id'];

            $year_level = $_POST['year_level1'];
            $semester = $_POST['semester1'];
            $academic_year = $_POST['academic_year1'];
            $program = $_POST['program1'];
            $class = $_POST['class1'];

            $studentNos = $_POST['student_no'] ?? [];
            $fullNames = $_POST['full_name'] ?? [];
            $emails = $_POST['email'] ?? [];

            $totalStudents = count($studentNos);
            if ($totalStudents === 0) {
                echo json_encode(["error" => "No students found."]);
                exit;
            }

            // Set response as stream
            header('Content-Type: text/event-stream');
            header('Cache-Control: no-cache');
            header('Connection: keep-alive');
            ob_implicit_flush(true);
            ob_end_flush();

            $recipients = [];

            // Process students and collect recipients
            for ($i = 0; $i < $totalStudents; $i++) {
                $studentNo = $conn->real_escape_string($studentNos[$i]);
                $fullName = $conn->real_escape_string($fullNames[$i]);
                $email = $conn->real_escape_string($emails[$i]);

                if (empty($studentNo) || empty($fullName) || empty($email)) {
                    continue;
                }

                $origid = uniqid();
                $sql = "INSERT INTO `student` (user_id, name, student_number, email, class, semester, year_level, academic_year, program, faculty_id, datetime)
                        VALUES ('$origid', '$fullName', '$studentNo', '$email', '$class', '$semester', '$year_level', '$academic_year', '$program', '$facultyId', NOW())";

                if (!mysqli_query($conn, $sql)) {
                    echo "data: error: " . mysqli_error($conn) . "\n\n";
                    flush();
                    exit;
                }

                $recipients[] = [
                    'email' => $email, // Change if needed
                    'name' => $fullName,
                    'hashedid' => $origid
                ];

                $progress = round(($i + 1) / ($totalStudents * 2) * 100);
                echo "data: " . $progress . "|Processing Students: " . ($i + 1) . "/" . $totalStudents . "\n\n";
                flush();
            }

            if (!empty($recipients)) {
                sendBulkEmails($recipients, $totalStudents);
            }

            echo "data: 100|Complete! All Students Processed & Emails Sent.\n\n";
            flush();
            exit;
        } else {
            echo json_encode(["error" => "No file uploaded."]);
        }
    }
    
    else if (isset($_POST['markAbsent'])) {
        date_default_timezone_set('Asia/Manila');
        $currentDateTime = date('Y-m-d H:i:s');
        $faculty_id = isset($_SESSION['faculty_id']) ? $_SESSION['faculty_id'] : null;
        $mark_ids = $_POST['mark_ids'] ?? [];

        // Check if there are user IDs in the array
        if (!empty($mark_ids)) {
            foreach ($mark_ids as $unique_id) {
                // Fetch student details
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

                    // Check if attendance exists for the current date
                    $attendanceCheckQuery = "SELECT * FROM attendance_log WHERE user_id = '$unique_id' AND DATE(attendatetime) = CURDATE()";
                    $attendanceCheckResult = mysqli_query($conn, $attendanceCheckQuery);

                    if (mysqli_num_rows($attendanceCheckResult) > 0) {
                        // If attendance exists and the status is 'Present', update it to 'Absent' and set modified_attendatetime
                        $attendance = mysqli_fetch_assoc($attendanceCheckResult);
                        if ($attendance['status'] == 'Present') {
                            $updateQuery = "UPDATE attendance_log 
                                            SET status = 'Absent', modified_attendatetime = '$currentDateTime' 
                                            WHERE user_id = '$unique_id' AND DATE(attendatetime) = CURDATE()";

                            if (mysqli_query($conn, $updateQuery)) {
                                echo "Attendance for user_id $unique_id updated to Absent.<br>";
                                header("Location: home");
                            } else {
                                echo "Error updating attendance for user_id $unique_id: " . mysqli_error($conn) . "<br>";
                            }
                        } else {
                            echo "User $unique_id is already marked as Absent.<br>";
                            header("Location: home");
                        }
                    } else {
                        // If attendance doesn't exist for today, insert a new entry as Absent
                        $query = "INSERT INTO attendance_log (user_id, status, class, program, academic_year, year_level, semester, room, faculty_id, attendatetime) 
                                 VALUES ('$unique_id', 'Absent', '$class', '$program', '$academic_year', '$year_level', '$semester', '$room', '$faculty_id', '$currentDateTime')";

                        if (mysqli_query($conn, $query)) {
                            echo "Attendance for user_id $unique_id marked as Absent.<br>";
                            header("Location: home");
                        } else {
                            echo "Error inserting attendance for user_id $unique_id: " . mysqli_error($conn) . "<br>";
                        }
                    }
                } else {
                    echo "No student found with the given unique_id $unique_id.<br>";
                }
            }
        } else {
            echo "No user IDs provided.";
        }
    }





    mysqli_close($conn);
}
