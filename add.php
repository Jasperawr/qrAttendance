<?php

session_start();

include "connect.php";
include "sendmail.php";
include "qrGenerator.php";


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
    }


    mysqli_close($conn);
}
