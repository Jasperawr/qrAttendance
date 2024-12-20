<?php
include "connect.php";

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
        $section = trim($_POST['section']);
        $groupnumber = trim($_POST['groupnumber']);
        $student_id = intval($_POST['student_id']); // Convert to integer for safety

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
                    yr_sec = '$section', 
                    group_no = '$groupnumber', 
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
    }
}


mysqli_close($conn);
