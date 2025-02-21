<?php
include "connect.php";
include "sendmail.php";

if (isset($_GET['unique_id']) && isset($_GET['quantity'])) {
    $unique_id = mysqli_real_escape_string($conn, $_GET['unique_id']);
    $quantity = (int) $_GET['quantity'];

    // Direct query without prepared statement
    $query = "UPDATE faculty_inventory_logs SET quantity = $quantity WHERE id = '$unique_id'";

    // Execute the query
    if (mysqli_query($conn, $query)) {
        header("Location: " . $_SERVER['HTTP_REFERER']);
    } else {
        echo "Error updating quantity: " . mysqli_error($conn);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['updateStudent'])) {

        // Get and sanitize input
        $name = ucwords(trim($_POST['name']));
        $student_number = trim($_POST['idnumber']);
        $email = trim($_POST['email']);
        $student_id = intval($_POST['student_id']);

        $class = trim($_POST['class']);
        $program = trim($_POST['program']);
        $academic_year = trim($_POST['academic_year']);
        $year_level = trim($_POST['year_level']);
        $semester = trim($_POST['semester']);


        // Check for empty fields
        if (empty($name) || empty($student_number) || empty($email)) {
            $_SESSION['errorMessage'] = "Name, ID Number, and Email are required.";
            header("Location: editstudent.php?id=$student_id");
            exit;
        }

        // Check for duplicate records, excluding the current one
        $query = "SELECT name, email 
              FROM student 
              WHERE (name = '$name' AND email = '$email') 
              AND id != '$student_id' 
              LIMIT 1";

        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) == 0) {
            $avatarBlob = null;

            // Handle avatar upload
            if (!empty($_FILES['studentavatar']['name'])) {
                $tmp_name = $_FILES['studentavatar']['tmp_name'];

                if ($_FILES['studentavatar']['error'] === UPLOAD_ERR_OK && is_uploaded_file($tmp_name)) {
                    $avatarBlob = addslashes(file_get_contents($tmp_name));
                } else {
                    $_SESSION['errorMessage'] = "Error uploading avatar.";
                    header("Location: editstudent.php?id=$student_id");
                    exit;
                }
            }

            // Prepare the update query
            $sql = "UPDATE `student` 
                SET name = '$name', 
                    email = '$email', 
                    student_number = '$student_number', 
                    class = '$class', 
                    academic_year = '$academic_year', 
                    year_level = '$year_level', 
                    semester = '$semester', 
                    program = '$program', 
                    datetime = NOW()";

            // Add avatar only if uploaded
            if ($avatarBlob !== null) {
                $sql .= ", avatar = '$avatarBlob'";
            }

            $sql .= " WHERE id = '$student_id'";

            // Execute the update query
            $sql_result = mysqli_query($conn, $sql);

            if ($sql_result) {
                $_SESSION['successMessage'] = "Student details updated successfully.";
                header("Location: students");
                exit;
            } else {
                $_SESSION['errorMessage'] = "Error updating student: " . mysqli_error($conn);
                header("Location: editstudent.php?id=$student_id");
                exit;
            }
        } else {
            $_SESSION['errorMessage'] = "A student with this name and email already exists.";
            header("Location: editstudent.php?id=$student_id");
            exit;
        }
    } else if (isset($_POST['updateItem'])) {
        $tempname = $_POST['name'];
        $name = htmlspecialchars(ucwords($tempname), ENT_QUOTES, 'UTF-8');
        $origid = htmlspecialchars($_POST['item_id'], ENT_QUOTES, 'UTF-8');
        $currentDate = date('Y-m-d H:i:s');

        $checkQuery = "SELECT * FROM `items` WHERE `item_id` = '$origid' LIMIT 1";
        $checkResult = mysqli_query($conn, $checkQuery);

        if ($checkResult && mysqli_num_rows($checkResult) > 0) {
            // If an image is uploaded, process the file
            if (!empty($_FILES['itempic']['name'])) {
                $filename = $_FILES['itempic']['name'];
                $type = $_FILES['itempic']['type'];
                $tmp_name = $_FILES['itempic']['tmp_name'];

                // Validate file type
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                if (!in_array($type, $allowedTypes)) {
                    echo "Error: Only JPEG, PNG, and GIF files are allowed.";
                    exit;
                }

                // Encode image as base64
                $imageData = file_get_contents($tmp_name);
                $imageBase64 = base64_encode($imageData);

                // Update both name, QR, and image
                $updateQuery = "UPDATE `items` 
                                SET item_name = '$name', image = '$imageBase64' 
                                WHERE item_id = '$origid'";
            } else {
                // Update only name and QR
                $updateQuery = "UPDATE `items` 
                                SET item_name = '$name'
                                WHERE item_id = '$origid'";
            }

            $updateResult = mysqli_query($conn, $updateQuery);

            if ($updateResult) {
                header("Location: inventoryadmin");
                exit;
            } else {
                echo "Error updating item: " . mysqli_error($conn);
            }
        } else {
            echo "Error: Item not found.";
        }
    } else if (isset($_POST['updateUser'])) {

        $userid = htmlspecialchars($_POST['user_id'], ENT_QUOTES, 'UTF-8');
        $currentDate = date('Y-m-d H:i:s');

        $query = "SELECT userid FROM user_acount WHERE userid = '$userid' LIMIT 1";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $updateFields = [];

            if (!empty($_POST['name'])) {
                $tempname = $_POST['name'];
                $name = ucwords($tempname);
                $updateFields[] = "name = '$name'";
            }

            if (!empty($_POST['email'])) {
                $email = $_POST['email'];
                $updateFields[] = "email = '$email'";
            }

            if (!empty($_POST['password'])) {
                $password = $_POST['password'];
                $hashedpwd = password_hash($password, PASSWORD_DEFAULT);
                $updateFields[] = "password = '$hashedpwd'";
            }

            if (!empty($updateFields)) {

                // Combine the fields into the query
                $updateQuery = "UPDATE `user_acount` SET " . implode(', ', $updateFields) . " WHERE userid = '$userid'";

                $updateResult = mysqli_query($conn, $updateQuery);

                if ($updateResult) {
                    if (!empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['name'])) {
                        sendCredentials($email, $password, $name);
                    }

                    if (isset($_SESSION['userExist'])) {
                        unset($_SESSION['userExist']);
                    }

                    header("Location: changepassword");
                    exit;
                } else {
                    echo "Error updating user: " . mysqli_error($conn);
                }
            } else {
                echo "No valid fields to update.";
            }
        } else {
            // If the user doesn't exist, set session error and redirect
            $_SESSION['userExist'] = "User does not exist. Please add a new user.";
            header("Location: changepassword");
            exit;
        }
    } else if (isset($_POST['updateAdminUser'])) {

        $userid = htmlspecialchars($_POST['user_id'], ENT_QUOTES, 'UTF-8');
        $currentDate = date('Y-m-d H:i:s');

        $query = "SELECT userid FROM user_acount WHERE userid = '$userid' LIMIT 1";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $updateFields = [];

            if (!empty($_POST['name'])) {
                $tempname = $_POST['name'];
                $name = ucwords($tempname);
                $updateFields[] = "name = '$name'";
            }

            if (!empty($_POST['email'])) {
                $email = $_POST['email'];
                $updateFields[] = "email = '$email'";
            }

            if (!empty($_POST['password'])) {
                $password = $_POST['password'];
                $hashedpwd = password_hash($password, PASSWORD_DEFAULT);
                $updateFields[] = "password = '$hashedpwd'";
            }

            if (!empty($updateFields)) {

                // Combine the fields into the query
                $updateQuery = "UPDATE `user_acount` SET " . implode(', ', $updateFields) . " WHERE userid = '$userid'";

                $updateResult = mysqli_query($conn, $updateQuery);

                if ($updateResult) {
                    if (!empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['name'])) {
                        sendCredentials($email, $password, $name);
                    }

                    if (isset($_SESSION['userExist'])) {
                        unset($_SESSION['userExist']);
                    }

                    header("Location: users");
                    exit;
                } else {
                    echo "Error updating user: " . mysqli_error($conn);
                }
            } else {
                echo "No valid fields to update.";
            }
        } else {
            // If the user doesn't exist, set session error and redirect
            $_SESSION['userExist'] = "User does not exist. Please add a new user.";
            header("Location: users");
            exit;
        }
    } else if (isset($_POST['updatePassword'])) {

        $userid = htmlspecialchars($_POST['user_id'], ENT_QUOTES, 'UTF-8'); // Sanitize the user ID
        $currentDate = date('Y-m-d H:i:s'); // Current timestamp

        // Check if the user exists
        $query = "SELECT userid FROM user_acount WHERE userid = '$userid' LIMIT 1";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            // Verify passwords are set and match
            if (!empty($_POST['password']) && !empty($_POST['confirmPassword'])) {
                $password = $_POST['password'];
                $confirmPassword = $_POST['confirmPassword'];

                if ($password === $confirmPassword) {
                    // Hash the new password
                    $hashedpwd = password_hash($password, PASSWORD_DEFAULT);

                    // Update the password in the database
                    $updateQuery = "UPDATE `user_acount` 
                                    SET password = '$hashedpwd', datetime = '$currentDate' 
                                    WHERE userid = '$userid'";
                    $updateResult = mysqli_query($conn, $updateQuery);

                    if ($updateResult) {
                        // Successfully updated password
                        if (isset($_SESSION['passwordError'])) {
                            unset($_SESSION['passwordError']);
                        }

                        $_SESSION['passwordSuccess'] = "Password updated successfully.";
                        header("Location: changepassword");
                        exit;
                    } else {
                        // Handle query error
                        echo "Error updating password: " . mysqli_error($conn);
                    }
                } else {
                    // Passwords do not match
                    $_SESSION['passwordError'] = "Passwords do not match.";
                    header("Location: changepassword");
                    exit;
                }
            } else {
                // Password fields are empty
                $_SESSION['passwordError'] = "Password fields cannot be empty.";
                header("Location: users");
                exit;
            }
        } else {
            // User does not exist
            $_SESSION['passwordError'] = "User does not exist. Please check the user ID.";
            header("Location: users");
            exit;
        }
    }
}


mysqli_close($conn);
