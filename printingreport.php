<?php

include "./connect.php";
require 'vendor/autoload.php';
if (isset($_POST['generate_pdf'])) {
    // / Query 1: Fetch data from user_acount
    $query1 = "SELECT * FROM user_acount";
    $result1 = mysqli_query($conn, $query1);

    $query2 = "SELECT 
                    user_acount.*, faculty_logins.*
                FROM 
                    user_acount
                INNER JOIN 
                    faculty_logins ON user_acount.id = faculty_logins.faculty_id
            ";
    $result2 = mysqli_query($conn, $query2);

    // Query 2: Fetch student data with program name
    $query3 = "SELECT student.*, program.program_name 
           FROM student 
           LEFT JOIN program ON student.program = program.id";
    $result3 = mysqli_query($conn, $query3);



    // Create new PDF document
    $pdf = new TCPDF();

    // Add a page to the document
    $pdf->AddPage();

    // Set font
    $pdf->SetFont('helvetica', '', 12);

    // Table 1: User Account Data
    $table1 = '<h3 style="font-family: Arial, sans-serif; color: #333;">User Accounts</h3>';
    $table1 .= '<table border="1" cellpadding="5" cellspacing="0" style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; font-size: 12px; color: #333;">
                <thead>
                    <tr style="background-color: #f3f3f3; text-align: left;">
                        <th style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">No.</th>
                        <th style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">User ID</th>
                        <th style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">User Name</th>
                        <th style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">Email</th>
                        <th style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">Date Registered</th>
                        <th style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">Date Modified</th>
                    </tr>
                </thead>
                <tbody>';

    while ($row1 = mysqli_fetch_assoc($result1)) {
        ++$i1;
        $table1 .= '<tr style="border-bottom: 1px solid #ddd;">
                        <td style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">' . $i1 . '</td>
                        <td style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">' . $row1['userid'] . '</td>
                        <td style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">' . $row1['name'] . '</td>
                        <td style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">' . $row1['email'] . '</td>
                        <td style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">' . $row1['datetime'] . '</td>
                        <td style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">' . $row1['modified'] . '</td>
                    </tr>';
    }

    $table1 .= '</tbody></table>';


    // Table 2: Student Data
    $table2 = '<h3 style="font-family: Arial, sans-serif; color: #333;">Faculty Logs</h3>';
    $table2 .= '<table border="1" cellpadding="5" cellspacing="0" style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; font-size: 12px; color: #333;">
                <thead>
                    <tr style="background-color: #f3f3f3; text-align: left;">
                        <th style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">No.</th>
                        <th style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">Faculty Number</th>
                        <th style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">Login</th>
                        <th style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">Logout</th>
                    </tr>
                </thead>
                <tbody>';

    while ($row2 = mysqli_fetch_assoc($result2)) {
        ++$i2;
        $table2 .= '<tr style="border-bottom: 1px solid #ddd;">
                        <td style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">' . $i2 . '</td>
                        <td style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">' . $row2['name'] . '</td>
                        <td style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">' . $row2['login_time'] . '</td>
                        <td style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">' . $row2['logout_time'] . '</td>
                    </tr>';
    }

    $table2 .= '</tbody></table>';

    // Table 2: Student Data
    $table3 = '<h3 style="font-family: Arial, sans-serif; color: #333;">Student Information</h3>';
    $table3 .= '<table border="1" cellpadding="5" cellspacing="0" style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; font-size: 12px; color: #333;">
                <thead>
                    <tr style="background-color: #f3f3f3; text-align: left;">
                        <th style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">No.</th>
                        <th style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">Student Number</th>
                        <th style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">Name</th>
                        <th style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">Email</th>
                        <th style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">Class</th>
                        <th style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">Semester</th>
                        <th style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">Year Level</th>
                        <th style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">Academic Year</th>
                        <th style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">Program</th>
                        <th style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">Date Registered</th>
                        <th style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">Date Modified</th>
                    </tr>
                </thead>
                <tbody>';

    while ($row3 = mysqli_fetch_assoc($result3)) {
        ++$i3;
        $table3 .= '<tr style="border-bottom: 1px solid #ddd;">
                        <td style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">' . $i3 . '</td>
                        <td style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">' . $row3['student_number'] . '</td>
                        <td style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">' . $row3['name'] . '</td>
                        <td style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">' . $row3['email'] . '</td>
                        <td style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">' . $row3['class'] . '</td>
                        <td style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">' . $row3['semester'] . '</td>
                        <td style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">' . $row3['year_level'] . '</td>
                        <td style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">' . $row3['academic_year'] . '</td>
                        <td style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">' . $row3['program_name'] . '</td>
                        <td style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">' . $row3['datetime'] . '</td>
                        <td style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">' . $row3['modified'] . '</td>
                    </tr>';
    }

    $table3 .= '</tbody></table>';


    // // Table 2: Student Data
    // $table3 = '<h3 style="font-family: Arial, sans-serif; color: #333;">Student Information</h3>';
    // $table3 .= '<table border="1" cellpadding="5" cellspacing="0" style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; font-size: 12px; color: #333;">
    //             <thead>
    //                 <tr style="background-color: #f3f3f3; text-align: left;">
    //                     <th style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">No.</th>
    //                     <th style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">Student Number</th>
    //                     <th style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">Name</th>
    //                     <th style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">Email</th>
    //                     <th style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">Class</th>
    //                     <th style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">Semester</th>
    //                     <th style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">Year Level</th>
    //                     <th style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">Academic Year</th>
    //                     <th style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">Program</th>
    //                     <th style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">Date Registered</th>
    //                     <th style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">Date Modified</th>
    //                 </tr>
    //             </thead>
    //             <tbody>';

    // while ($row2 = mysqli_fetch_assoc($result2)) {
    //     ++$i1;
    //     $table3 .= '<tr style="border-bottom: 1px solid #ddd;">
    //                     <td style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">' . $i1 . '</td>
    //                     <td style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">' . $row2['student_number'] . '</td>
    //                     <td style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">' . $row2['name'] . '</td>
    //                     <td style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">' . $row2['email'] . '</td>
    //                     <td style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">' . $row2['class'] . '</td>
    //                     <td style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">' . $row2['semester'] . '</td>
    //                     <td style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">' . $row2['year_level'] . '</td>
    //                     <td style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">' . $row2['academic_year'] . '</td>
    //                     <td style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">' . $row2['program_name'] . '</td>
    //                     <td style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">' . $row2['datetime'] . '</td>
    //                     <td style="font-size: 6px; padding: 8px; border: 1px solid #ddd;">' . $row2['modified'] . '</td>
    //                 </tr>';
    // }

    // $table3 .= '</tbody></table>';

    // Write tables to the PDF
    $pdf->writeHTML($table1, true, false, false, false, '');
    $pdf->writeHTML($table2, true, false, false, false, '');
    $pdf->AddPage('L');
    $pdf->writeHTML($table3, true, false, false, false, '');

    // Output the PDF to the browser
    // Assuming $pdf is a TCPDF or FPDF instance
    $pdfContent = $pdf->Output('', 'S'); // 'S' returns PDF as a string

    // Escape the binary data to ensure safe insertion into the database
    $pdfContentEscaped = mysqli_real_escape_string($conn, $pdfContent);

    $check_query = "SELECT id from exported_files_blob where id = 1";
    $check_result = mysqli_query($conn, $check_query);

    if ($check_result && mysqli_num_rows($check_result) > 0) {
        $sql = "UPDATE exported_files_blob SET file_name = 'general_report.pdf', file_data = '$pdfContentEscaped' where id = 1";
        if (mysqli_query($conn, $sql)) {
            header('Location: generalreport');
            exit;
            echo "PDF successfully saved to database!";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        $query = "INSERT INTO exported_files_blob (file_name, file_data) VALUES ('general_report.pdf', '$pdfContentEscaped')";

        if (mysqli_query($conn, $query)) {
            header('Location: generalreport');
            exit;
            echo "PDF successfully saved to database!";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
