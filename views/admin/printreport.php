<?php
session_start();

if (!isset($_SESSION['loggedin']) && !$_SESSION['loggedin'] == "true") {
    header("Location: login.php");
    exit;
}

$faculty_id = $_SESSION['faculty_id'];
$default_class = $_SESSION['class'] ?? null;
$default_semester = $_SESSION['semester'] ?? null;
$default_academic_year = $_SESSION['academic_year'] ?? null;
$default_room = $_SESSION['room'] ?? null;
$default_program = $_SESSION['program'] ?? null;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Attendance Management with Inventory System</title>
    <link rel="icon" href="assets/img/bulsuhag.png" type="image/x-icon">
    <link rel="shortcut icon" href="assets/img/bulsuhag.png" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <script src="assets/script.js"></script>

    <style>
        #excelPreview table {
            border-collapse: collapse;
            width: 100%;
            margin: 20px 0;
        }

        #excelPreview table,
        th,
        td {
            border: 1px solid #000;
        }

        #excelPreview th,
        td {
            padding: 10px;
            text-align: left;
        }

        #excelPreview th {
            background-color: #f2f2f2;
            font-weight: bold;

        }
    </style>
</head>

<body>

    <?php include "components/topbar.php"; ?>
    <?php include "components/modals/zoomImage.php"; ?>


    <div class="flex h-screen justify-around font-poppins pt-[100px] bg-gray-50">
        <div class="flex justify-center h-full w-full bg-opacity-75 pl-[200px] pr-[100px] gap-5">
            <div class="flex flex-col w-full gap-5">
                <div class="flex justify-end gap-5">
                    <form method="POST" action="./printingreport.php">
                        <button type="submit" name="generate_pdf" class="text-xs hover:bg-gray-300 text-center p-4 transition-all duration-200 ease-in-out">Refresh General Report</button>
                    </form>

                    <a href="excelpreview.php?file_id=1" class="text-xs hover:bg-gray-300 text-center p-4 transition-all duration-200 ease-in-out">Download Excel File</a>
                </div>

                <div id="excelPreview">
                    <!-- Alternatively, embed the PDF inline -->
                    <iframe src="excelpreview.php?preview_id=1" width="100%" height="600px"></iframe>
                </div>
            </div>

        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.1/xlsx.full.min.js"></script>
    <script>
        // fetch('excelpreview.php?file_id=1') // Replace with the correct file_id
        //     .then(response => response.blob()) // Get the response as a Blob
        //     .then(blob => {
        //         // Read the Blob as an ArrayBuffer
        //         const reader = new FileReader();
        //         reader.onload = function(e) {
        //             // Parse the Excel file using SheetJS
        //             const data = e.target.result;
        //             const workbook = XLSX.read(data, {
        //                 type: 'array'
        //             });

        //             // Convert the first sheet to HTML
        //             const html = XLSX.utils.sheet_to_html(workbook.Sheets[workbook.SheetNames[0]]);

        //             // Display the HTML in the page
        //             document.getElementById('excelPreview').innerHTML = html;
        //         };
        //         reader.readAsArrayBuffer(blob); // Read the Blob as ArrayBuffer
        //     })
        //     .catch(error => {
        //         console.error('Error loading the Excel file:', error);
        //         document.getElementById('excelPreview').innerHTML = 'Failed to load the Excel file.';
        //     });
    </script>

</body>

</html>