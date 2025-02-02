<?php
include "connect.php";

// Check if the user wants to download the Excel file by ID
if (isset($_GET['file_id'])) {
    $fileId = $_GET['file_id'];

    // Fetch the file from the database using file_id
    $sql = "SELECT file_name, file_data FROM exported_files_blob WHERE id = '$fileId'";
    $result = mysqli_query($conn, $sql);

    if ($row = mysqli_fetch_assoc($result)) {
        // Set the correct headers for downloading an Excel file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $row['file_name'] . '"');

        // Output the BLOB data as the file content
        echo $row['file_data'];
        exit;
    } else {
        echo "File not found.";
    }
}
if (isset($_GET['preview_id'])) {
    if (isset($_GET['preview_id'])) {
        $id = 1; // Get the file ID from the URL

        // Query to fetch the blob data
        $query = "SELECT file_name, file_data FROM exported_files_blob WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($fileName, $fileData);
        $stmt->fetch();

        if ($fileData) {
            // Set headers for the PDF file
            header("Content-Type: application/pdf");
            header("Content-Disposition: inline; filename=\"" . $fileName . "\"");
            header("Content-Length: " . strlen($fileData));

            // Output the blob data
            echo $fileData;
        } else {
            echo "File not found.";
        }

        $stmt->close();
    } else {
        echo "No file ID provided.";
    }
}

mysqli_close($conn);
?>



// include "connect.php";

// // Check if the user wants to download or view the Excel
// if (isset($_GET['file_id'])) {
// $fileId = $_GET['file_id'];

// $sql = "SELECT file_name, file_data FROM exported_files_blob WHERE id = '$fileId'";
// $result = mysqli_query($conn, $sql);

// if ($row = mysqli_fetch_assoc($result)) {
// header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
// header('Content-Disposition: attachment; filename="' . $row['file_name'] . '"');

// // Output the BLOB data
// echo $row['file_data'];
// exit;
// } else {
// echo "File not found.";
// }
// }

// mysqli_close($conn);