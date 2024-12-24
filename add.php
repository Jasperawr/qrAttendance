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
        $section = $_POST['section'];
        $groupnumber = $_POST['groupnumber'];

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

            $sql = "INSERT INTO `student` (user_id, name, email, student_number, yr_sec, group_no, faculty_id, avatar, datetime) 
                        VALUES ('$origid', '$name', '$email', '$student_number', '$section', '$groupnumber', '$facultyId', '$avatarBlob', '$currentDate')";
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
                    header("Location: addstudent");
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
    } else if (isset($_POST['uploadExcel'])) {
        // Handle Excel or CSV upload
        if (isset($_FILES['excelFile'])) {
            $fileName = $_FILES['excelFile']['tmp_name'];
            $facultyId = $_SESSION['faculty_id'];

            try {
                // Load the uploaded file
                $spreadsheet = IOFactory::load($fileName);
                $sheet = $spreadsheet->getActiveSheet();
                $rows = $sheet->toArray(); // Convert sheet to an array

                $isHeader = true; // To skip the header row
                foreach ($rows as $row) {
                    if ($isHeader) {
                        $isHeader = false;
                        continue;
                    }

                    // Extract data from each row
                    $name = ucwords($row[0]); // First column: Name
                    $email = $row[1];        // Second column: Email
                    $student_number = $row[2]; // Third column: Student Number
                    $sectionName = $row[3];  // Fourth column: Section (e.g., "1B")
                    $groupNumber = $row[4];  // Fifth column: Group (e.g., "G1")
                    $imagePath = $row[5];    // Sixth column: Image Path (e.g., images/student1.jpg)

                    // Generate unique ID
                    $origid = uniqid();

                    // Lookup section ID
                    $sectionQuery = "SELECT id FROM yr_sec WHERE year_and_sec = '$sectionName' LIMIT 1";
                    $sectionResult = mysqli_query($conn, $sectionQuery);
                    if ($sectionResult && mysqli_num_rows($sectionResult) > 0) {
                        $sectionId = mysqli_fetch_assoc($sectionResult)['id'];
                    } else {
                        echo "Error: Section '$sectionName' not found.<br>";
                        continue;
                    }

                    // Lookup group ID
                    $groupQuery = "SELECT id FROM group_no WHERE group_number = '$groupNumber' LIMIT 1";
                    $groupResult = mysqli_query($conn, $groupQuery);
                    if ($groupResult && mysqli_num_rows($groupResult) > 0) {
                        $groupId = mysqli_fetch_assoc($groupResult)['id'];
                    } else {
                        echo "Error: Group '$groupNumber' not found.<br>";
                        continue;
                    }

                    // Check if student exists
                    $checkQuery = "SELECT name, email FROM student WHERE name = '$name' AND email = '$email' LIMIT 1";
                    $checkResult = mysqli_query($conn, $checkQuery);
                    if (mysqli_num_rows($checkResult) == 0) {
                        // Read image file and encode as base64 if it exists
                        $imageBase64 = null; // Default to null
                        if (!empty($imagePath) && file_exists($imagePath)) {
                            $imageData = file_get_contents($imagePath);
                            $imageBase64 = base64_encode($imageData);
                        }

                        // Insert student
                        $query = "INSERT INTO student (user_id, name, email, student_number, yr_sec, group_no, avatar, faculty_id, datetime) 
                                  VALUES ('$origid', '$name', '$email', '$student_number', '$sectionId', '$groupId', '$imageBase64', '$facultyId', '$currentDate')";
                        if (!mysqli_query($conn, $query)) {
                            echo "Error inserting student: " . mysqli_error($conn) . "<br>";
                        }
                    } else {
                        echo "Student '$name' with email '$email' already exists.<br>";
                    }
                }

                echo "File uploaded and processed successfully.";
                header("Location: addstudent");
                exit;
            } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
                echo "Error reading Excel file: " . $e->getMessage();
            }
        } else {
            echo "No file uploaded.";
        }
    }


    mysqli_close($conn);
}
