<?php

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
</head>

<body>

    <?php include "components/topbar.php"; ?>
    <?php include "components/modals/zoomImage.php"; ?>


    <div class="flex h-screen justify-around font-poppins pt-[100px] bg-gray-50">
        <div class="flex justify-center h-full w-full bg-opacity-75 pl-[200px] pr-[100px] gap-5">
            <div class=" flex flex-col items-center w-full rounded-md ">
                <div class="flex flex-col items-center bg-white rounded-md shadow-lg p-10  border text-center">
                    <span class="text-xs text-gray-600 w-90">Export and preview attendance lists effortlessly: This page allows you to view and download class attendance records in Excel format for streamlined management.</span>
                    <form action="./printexcel.php" method="POST" class="flex flex-col items-center">

                        <div class=" flex flex-col gap-4 px-10 py-4 items-center justify-center">
                            <select name="class" id="class" class="cursor-pointer px-5 border border-gray-500 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 hover:bg-gray-50 w-full py-2">
                                <option disabled <?php echo is_null($default_class) ? 'selected' : ''; ?>>Class Section</option>
                                <?php
                                $ys_query = "SELECT DISTINCT class FROM student WHERE faculty_id = '$faculty_id' AND class != '0' AND class != '' AND class IS NOT NULL ORDER BY class ASC";
                                $ys_result = mysqli_query($conn, $ys_query);
                                if ($ys_result && mysqli_num_rows($ys_result) > 0) {
                                    while ($ys_row = mysqli_fetch_assoc($ys_result)) {
                                        $selected = ($ys_row['class'] === $default_class) ? 'selected' : '';
                                        echo "<option value='" . htmlspecialchars($ys_row['class']) . "' $selected>" . htmlspecialchars($ys_row['class']) . "</option>";
                                    }
                                }
                                ?>
                            </select>

                            <!-- Semester Dropdown -->
                            <select id="semester" name="semester" class="cursor-pointer px-5 border border-gray-500 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 hover:bg-gray-50 w-full py-2">
                                <option disabled <?php echo is_null($default_semester) ? 'selected' : ''; ?>>Select Semester</option>
                                <option value="1st semester" <?php echo $default_semester === '1st semester' ? 'selected' : ''; ?>>1st Semester</option>
                                <option value="2nd semester" <?php echo $default_semester === '2nd semester' ? 'selected' : ''; ?>>2nd Semester</option>
                                <option value="summer" <?php echo $default_semester === 'summer' ? 'selected' : ''; ?>>Summer</option>
                            </select>

                            <!-- Academic Year Dropdown -->
                            <select name="academic_year" id="academic_year" class="cursor-pointer px-5 border border-gray-500 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 hover:bg-gray-50 w-full py-2">
                                <option disabled <?php echo is_null($default_academic_year) ? 'selected' : ''; ?>>Academic Year</option>
                                <?php
                                $ys_query = "SELECT DISTINCT academic_year FROM student WHERE faculty_id = '$faculty_id' AND academic_year != '0' AND academic_year != '' AND academic_year IS NOT NULL ORDER BY academic_year ASC";
                                $ys_result = mysqli_query($conn, $ys_query);
                                if ($ys_result && mysqli_num_rows($ys_result) > 0) {
                                    while ($ys_row = mysqli_fetch_assoc($ys_result)) {
                                        $selected = ($ys_row['academic_year'] === $default_academic_year) ? 'selected' : '';
                                        echo "<option value='" . htmlspecialchars($ys_row['academic_year']) . "' $selected>" . htmlspecialchars($ys_row['academic_year']) . "</option>";
                                    }
                                }
                                ?>
                            </select>

                            <!-- Room Dropdown -->
                            <select id="room" name="room" class="cursor-pointer px-5 border border-gray-500 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 hover:bg-gray-50 w-full py-2">
                                <option disabled>Select Room</option>
                                <option value="101">Room 101</option>
                                <option value="102">Room 102</option>
                                <option value="103">Room 103</option>
                                <option value="201">Room 201</option>
                                <option value="202">Room 202</option>
                                <option value="203">Room 203</option>
                            </select>

                            <!-- Program Dropdown -->
                            <select name="program" id="program" class="col-span-2 cursor-pointer px-5 border border-gray-500 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 hover:bg-gray-50 w-full py-2">
                                <option disabled <?php echo is_null($default_program) ? 'selected' : ''; ?>>Program</option>
                                <?php
                                $gn_query = "SELECT DISTINCT student.class, program.program_name, program.id FROM student LEFT JOIN program ON student.program = program.id WHERE student.faculty_id = '$faculty_id' AND student.program != '0' AND student.program != ''    ORDER BY student.program ASC";
                                $gn_result = mysqli_query($conn, $gn_query);
                                if ($gn_result && mysqli_num_rows($gn_result) > 0) {
                                    while ($gn_row = mysqli_fetch_assoc($gn_result)) {
                                        echo "<option value='" . htmlspecialchars($gn_row['id']) . "' $selected>" . htmlspecialchars($gn_row['program_name']) . "</option>";
                                    }
                                }
                                ?>
                            </select>

                            <div class="flex gap-2 items-center w-full">
                                <div class="relative flex max-w-sm gap-1 items-center text-center w-full">
                                    <input type="date" name="start" id="start-date" class="w-full border border-red-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                                </div>
                                -
                                <div class="relative flex max-w-sm gap-1 items-center text-center w-full">
                                    <input type="date" name="end" id="end-date" class="w-full border border-red-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                                </div>
                            </div>

                        </div>
                        <button
                            type="submit"
                            name="generate_excel"
                            class=" text-nowrap flex justify-between items-center gap-2 shadow-md text-white  bg-red-800 hover:bg-red-900 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2   focus:outline-none ">
                            Export
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

</body>

</html>