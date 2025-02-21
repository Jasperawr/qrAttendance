    <?php

    if (!isset($_SESSION['loggedin']) && !$_SESSION['loggedin'] == "true") {
        header("Location: login.php");
        exit;
    }

    include "totals.php";
    include "connect.php";

    $faculty_id = $_SESSION['faculty_id'];
    $default_class = $_SESSION['class'] ?? null;
    $default_semester = null;
    $default_academic_year = null;
    $default_room = null;
    $default_program = null;



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
        <script src="./src/scanner.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    </head>

    <style>
        .marked-date {
            background-color: #9c2525;
            /* Or any other styling you want */
            color: white;
        }
    </style>

    <body>

        <?php include "components/topbar.php"; ?>

        <div class="flex justify-around font-poppins pt-[100px] bg-gray-50 h-screen">

            <div class="flex gap-5 justify-center h-full w-full overflow-y-auto px-[200px]">

                <div class="w-full ">

                    <div class="grid grid-cols-3  gap-9 mb-7">
                        <div class="p-6  bg-white rounded-lg flex justify-between shadow-md shadow-gray-200 border">
                            <div class="flex flex-col justify-between align-center">
                                <p class="font-bold text-[35px] tracking-wide font-outfit text-gray-950"><?php echo totalStudent($conn); ?></p>
                                <p class="font-bold text-[16px] tracking-wide text-gray-950 text-nowrap">Total Students</p>
                            </div>
                            <svg class="ml-8 mt-2 text-red-600" xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" viewBox="0 0 16 16">
                                <path fill="currentColor" d="M15 14s1 0 1-1s-1-4-5-4s-5 3-5 4s1 1 1 1zm-7.978-1L7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276c.593.69.758 1.457.76 1.72l-.008.002l-.014.002zM11 7a2 2 0 1 0 0-4a2 2 0 0 0 0 4m3-2a3 3 0 1 1-6 0a3 3 0 0 1 6 0M6.936 9.28a6 6 0 0 0-1.23-.247A7 7 0 0 0 5 9c-4 0-5 3-5 4q0 1 1 1h4.216A2.24 2.24 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904c.243-.294.526-.569.846-.816M4.92 10A5.5 5.5 0 0 0 4 13H1c0-.26.164-1.03.76-1.724c.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0a3 3 0 0 1-6 0m3-2a2 2 0 1 0 0 4a2 2 0 0 0 0-4" />
                            </svg>
                        </div>
                        <div class="p-6  bg-white rounded-lg flex justify-between shadow-md shadow-gray-200 border">
                            <div class="flex flex-col justify-between align-center">
                                <p class="font-bold text-[35px] tracking-wide font-outfit text-gray-950"><?php echo present($conn); ?></p>
                                <p class="font-bold text-[16px] tracking-wide text-gray-950">Present</p>
                            </div>
                            <svg class="ml-8 mt-2 text-red-600" xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M21 21V5H3v8H1V5q0-.825.588-1.412T3 3h18q.825 0 1.413.588T23 5v14q0 .825-.587 1.413T21 21M9 14q-1.65 0-2.825-1.175T5 10t1.175-2.825T9 6t2.825 1.175T13 10t-1.175 2.825T9 14m0-2q.825 0 1.413-.587T11 10t-.587-1.412T9 8t-1.412.588T7 10t.588 1.413T9 12M1 22v-2.8q0-.85.438-1.562T2.6 16.55q1.55-.775 3.15-1.162T9 15t3.25.388t3.15 1.162q.725.375 1.163 1.088T17 19.2V22zm2-2h12v-.8q0-.275-.137-.5t-.363-.35q-1.35-.675-2.725-1.012T9 17t-2.775.338T3.5 18.35q-.225.125-.363.35T3 19.2zm6 0" />
                            </svg>
                        </div>
                        <div class="p-6  bg-white rounded-lg flex justify-between shadow-md shadow-gray-200 border">
                            <div class="flex flex-col justify-between align-center">
                                <p class="font-bold text-[35px] tracking-wide font-outfit text-gray-950"><?php echo absent($conn); ?></p>
                                <p class="font-bold text-[16px] tracking-wide text-gray-950">Absent</p>
                            </div>
                            <svg class="ml-8 mt-2 text-red-600" xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M23 15.5c0-.71-.16-1.36-.45-1.96a7 7 0 0 0-3.69-3.92a6.55 6.55 0 0 0-1.9-3.58C15.6 4.68 13.95 4 12 4c-1.58 0-3 .47-4.25 1.43s-2.08 2.19-2.5 3.72c-1.25.28-2.29.93-3.08 1.95S1 13.28 1 14.58c0 1.51.54 2.8 1.61 3.85C3.69 19.5 5 20 6.5 20h3.76c1.27 1.81 3.36 3 5.74 3c3.87 0 7-3.13 7-7zM6.5 18c-.97 0-1.79-.34-2.47-1C3.34 16.29 3 15.47 3 14.5s.34-1.79 1.03-2.47C4.71 11.34 5.53 11 6.5 11H7c0-1.38.5-2.56 1.46-3.54C9.44 6.5 10.62 6 12 6s2.56.5 3.54 1.46c.46.47.81 1 1.05 1.57C16.4 9 16.2 9 16 9c-3.87 0-7 3.13-7 7c0 .7.11 1.37.29 2zm9.5 3c-2.76 0-5-2.24-5-5s2.24-5 5-5s5 2.24 5 5s-2.24 5-5 5m.5-4.75l2.86 1.69l-.75 1.22L15 17v-5h1.5z" />
                            </svg>
                        </div>


                        <!-- <div class="p-6  bg-white rounded-lg flex justify-between shadow-md shadow-gray-200">
                            <div class="flex flex-col justify-between align-center">
                                <p class="font-bold text-[35px] tracking-wide font-outfit text-gray-950"><?php echo absent($conn); ?></p>
                                <p class="font-bold text-[16px] tracking-wide text-gray-950">Late</p>
                            </div>
                            <svg class="ml-8 mt-2 text-red-600" xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M23 15.5c0-.71-.16-1.36-.45-1.96a7 7 0 0 0-3.69-3.92a6.55 6.55 0 0 0-1.9-3.58C15.6 4.68 13.95 4 12 4c-1.58 0-3 .47-4.25 1.43s-2.08 2.19-2.5 3.72c-1.25.28-2.29.93-3.08 1.95S1 13.28 1 14.58c0 1.51.54 2.8 1.61 3.85C3.69 19.5 5 20 6.5 20h3.76c1.27 1.81 3.36 3 5.74 3c3.87 0 7-3.13 7-7zM6.5 18c-.97 0-1.79-.34-2.47-1C3.34 16.29 3 15.47 3 14.5s.34-1.79 1.03-2.47C4.71 11.34 5.53 11 6.5 11H7c0-1.38.5-2.56 1.46-3.54C9.44 6.5 10.62 6 12 6s2.56.5 3.54 1.46c.46.47.81 1 1.05 1.57C16.4 9 16.2 9 16 9c-3.87 0-7 3.13-7 7c0 .7.11 1.37.29 2zm9.5 3c-2.76 0-5-2.24-5-5s2.24-5 5-5s5 2.24 5 5s-2.24 5-5 5m.5-4.75l2.86 1.69l-.75 1.22L15 17v-5h1.5z" />
                            </svg>
                        </div> -->
                    </div>

                    <div class=" w-full mb-5 flex flex-col gap-5">
                        <div class="flex w-full gap-5">
                            <div class="relative w-full h-full flex justify-start items-center">
                                <input onkeyup="searchTable('allAttendance', 'search-dropdown')" type="search" id="search-dropdown" class="block pl-9 p-2.5 w-full  text-sm text-gray-900 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Search..." required />
                                <svg class="w-5 h-5 absolute opacity-40 left-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                </svg>
                            </div>


                        </div>

                        <div class="flex w-full gap-2">
                            <select id="statusFilter" class="px-5 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  py-2">
                                <option value="all">All</option>
                                <option value="present">Present</option>
                                <option value="absent">Absent</option>
                                <option value="late">Late</option>
                            </select>

                            <select name="class" id="classFilter" class="cursor-pointer px-5 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 hover:bg-gray-50 w-full py-2">
                                <option disabled <?php echo is_null($default_class) ? 'selected' : ''; ?>>Class Section</option>
                                <option value="all">All</option>
                                <?php
                                $ys_query = "
                                    SELECT DISTINCT class 
                                    FROM student 
                                    WHERE faculty_id = '$faculty_id' 
                                    AND class != '0' 
                                    AND class != '' 
                                    AND class IS NOT NULL 
                                    ORDER BY class ASC
                                ";
                                $ys_result = mysqli_query($conn, $ys_query);
                                if ($ys_result && mysqli_num_rows($ys_result) > 0) {
                                    while ($ys_row = mysqli_fetch_assoc($ys_result)) {
                                        $selected = ($ys_row['class'] === $default_class) ? 'selected' : '';
                                        echo "<option value='" . htmlspecialchars($ys_row['class']) . "' $selected>" . htmlspecialchars($ys_row['class']) . "</option>";
                                    }
                                }
                                ?>
                            </select>

                            <div class="flex gap-2 items-center">
                                <div class="relative flex max-w-sm gap-1 items-center text-center">
                                    <input type="date" id="start-date" class=" border border-red-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                                </div>
                                -
                                <div class="relative flex max-w-sm gap-1 items-center text-center">
                                    <input type="date" id="end-date" class=" border border-red-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                                </div>
                            </div>

                            <a
                                type="button"
                                href="printattendance"
                                class=" text-nowrap flex justify-between items-center gap-2 shadow-md text-white  bg-red-800 hover:bg-red-900 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-3 py-2   focus:outline-none ">
                                Print
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                    <path fill="currentColor" d="M8 21q-.825 0-1.412-.587T6 19v-2H4q-.825 0-1.412-.587T2 15v-4q0-1.275.875-2.137T5 8h14q1.275 0 2.138.863T22 11v4q0 .825-.587 1.413T20 17h-2v2q0 .825-.587 1.413T16 21zM18 7H6V5q0-.825.588-1.412T8 3h8q.825 0 1.413.588T18 5zm0 5.5q.425 0 .713-.288T19 11.5t-.288-.712T18 10.5t-.712.288T17 11.5t.288.713t.712.287M8 19h8v-4H8z" />
                                </svg>
                            </a>
                        </div>


                        <div class="flex w-full gap-5 hidden">
                            <select name="class" id="class" class="cursor-pointer px-5 border border-gray-500 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 hover:bg-gray-50 w-full py-2">
                                <option disabled <?php echo is_null($default_class) ? 'selected' : ''; ?>>Class Section</option>
                                <?php
                                $ys_query = "
                                    SELECT DISTINCT class 
                                    FROM student 
                                    WHERE faculty_id = '$faculty_id' 
                                    AND class != '0' 
                                    AND class != '' 
                                    AND class IS NOT NULL 
                                    ORDER BY class ASC
                                ";
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
                                $ys_query = "
                                    SELECT DISTINCT academic_year 
                                    FROM student 
                                    WHERE faculty_id = '$faculty_id' 
                                    AND academic_year != '0' 
                                    AND academic_year != '' 
                                    AND academic_year IS NOT NULL 
                                    ORDER BY academic_year ASC
                                ";
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
                                <option disabled <?php echo is_null($default_room) ? 'selected' : ''; ?>>Select Room</option>
                                <option value="101" <?php echo $default_room === '101' ? 'selected' : ''; ?>>Room 101</option>
                                <option value="102" <?php echo $default_room === '102' ? 'selected' : ''; ?>>Room 102</option>
                                <option value="103" <?php echo $default_room === '103' ? 'selected' : ''; ?>>Room 103</option>
                                <option value="201" <?php echo $default_room === '201' ? 'selected' : ''; ?>>Room 201</option>
                                <option value="202" <?php echo $default_room === '202' ? 'selected' : ''; ?>>Room 202</option>
                                <option value="203" <?php echo $default_room === '203' ? 'selected' : ''; ?>>Room 203</option>
                            </select>

                            <!-- Program Dropdown -->
                            <select name="program" id="program" class="col-span-2 cursor-pointer px-5 border border-gray-500 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 hover:bg-gray-50 w-full py-2">
                                <option disabled <?php echo is_null($default_program) ? 'selected' : ''; ?>>Program</option>
                                <?php
                                $gn_query = "
                                    SELECT DISTINCT student.class, program.program_name, program.id 
                                    FROM student 
                                    LEFT JOIN program ON student.program = program.id 
                                    WHERE student.faculty_id = '$faculty_id' 
                                    AND student.program != '0' AND student.program != ''    
                                    ORDER BY student.program ASC
                                ";
                                $gn_result = mysqli_query($conn, $gn_query);
                                if ($gn_result && mysqli_num_rows($gn_result) > 0) {
                                    while ($gn_row = mysqli_fetch_assoc($gn_result)) {
                                        $selected = ($gn_row['id'] === $default_program) ? 'selected' : '';
                                        echo "<option value='" . htmlspecialchars($gn_row['id']) . "' $selected>" . htmlspecialchars($gn_row['program_name']) . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>

                    </div>



                    <div class="relative overflow-x-auto shadow-md shadow-gray-200 rounded-lg border">

                        <table class="w-full text-sm text-left text-gray-500 " id="allAttendance">
                            <caption class="p-5 text-md font-semibold text-left text-gray-900 bg-white ">
                                Attendance Overview
                            </caption>
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-3 py-2 text-[10px]">
                                        Student Name
                                    </th>
                                    <th scope="col" class="px-3 py-2 text-[10px]">
                                        ID Number
                                    </th>
                                    <th scope="col" class="px-3 py-2 text-[10px]">
                                        Class Section
                                    </th>
                                    <th scope="col" class="px-3 py-2 text-[10px]">
                                        Semester
                                    </th>
                                    <th scope="col" class="px-3 py-2 text-[10px]">
                                        Status
                                    </th>
                                    <th scope="col" class="px-3 py-2 text-[10px]">
                                        Room
                                    </th>
                                    <th scope="col" class="px-3 py-2 text-[10px]">
                                        Date
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php include "./attendanceLogs.php"; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class=" w-full flex flex-col gap-4 ">
                    <select id="monthSelect" class="p-2 bg-white rounded-md">
                    </select>
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <div class="flex justify-evenly w-full">
                                <span class="tracking-wide text-[20px] ">C</span>
                                <span class="tracking-wide text-[20px] ">A</span>
                                <span class="tracking-wide text-[20px] ">L</span>
                                <span class="tracking-wide text-[20px] ">E</span>
                                <span class="tracking-wide text-[20px] ">N</span>
                                <span class="tracking-wide text-[20px] ">D</span>
                                <span class="tracking-wide text-[20px] ">A</span>
                                <span class="tracking-wide text-[20px] ">R</span>
                            </div>
                            <span class="text-xs text-gray-500">This is you time in on each day you log in</span>
                            <div id="datepicker"></div>
                        </div>
                        <div class="">
                            <canvas id="pie" class="border bg-white shadow-lg rounded-md p-4"></canvas>
                        </div>
                    </div>
                    <div>
                        <canvas id="bar" height="400" class="border bg-white shadow-lg rounded-md p-4"></canvas>
                    </div>
                    <div>
                        <canvas id="line" height="400" class="border bg-white shadow-lg rounded-md p-4 "></canvas>
                    </div>
                </div>


            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // attendance
                setupTableFilter("statusFilter", "allAttendance", "statusAttendance");
                setupClassFilter("classFilter", "allAttendance", "classAttendance");
                // setupDateFilter("dateFilter", "allAttendance", "dateAttendance");
                setupDateRangeFilter("start-date", "end-date", "allAttendance", "dateAttendance");

                document.getElementById('start-date').valueAsDate = new Date();
                document.getElementById('end-date').valueAsDate = new Date();

            });

            flatpickr("#datepicker", {
                inline: true,
                dateFormat: "Y-m-d",
                onDayCreate: function(dObj, dStr, fp, dayElem) {
                    fetch('date_log.php')
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(dates => {
                            if (Array.isArray(dates)) {
                                const localDate = new Date(dayElem.dateObj);
                                // Convert the date to Philippine Standard Time (GMT+8)
                                const dateString = localDate.toLocaleDateString('en-CA', {
                                    timeZone: 'Asia/Manila'
                                });

                                // Check if the current day is in the list of fetched dates
                                if (dates.includes(dateString)) {
                                    dayElem.classList.add("marked-date");
                                }
                            } else {
                                console.warn('Expected an array of dates');
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching dates:', error);
                        });
                }
            });

            let pieChart;

            function createPieChart(monthData) {
                const pie = document.getElementById('pie').getContext('2d');

                // Destroy the existing Pie chart if it exists
                if (pieChart) {
                    pieChart.destroy();
                }

                // Prepare the data for the Pie chart: [Present, Absent]
                const data = [
                    monthData.Present || 0,
                    monthData.Absent || 0
                ];

                // Labels for the pie chart
                const labels = ['Present', 'Absent'];

                // Create the Pie chart
                pieChart = new Chart(pie, {
                    type: 'pie',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Student Attendance',
                            data: data,
                            backgroundColor: [
                                'rgb(54, 235, 105)',
                                'rgb(255, 99, 132)'
                            ],
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            datalabels: {
                                color: '#fff',
                                formatter: (value, context) => {
                                    const total = context.chart.data.datasets[0].data.reduce((acc, val) => acc + val, 0);
                                    const percentage = ((value / total) * 100).toFixed(2);
                                    return `${percentage}%`;
                                },
                                font: {
                                    weight: 'bold',
                                    size: 14
                                }
                            }
                        }
                    },
                    plugins: [ChartDataLabels]
                });
            }



            // Function to fetch months and room data from the PHP script
            async function fetchRoomUsageData() {
                const response = await fetch('getRoomUsage.php'); // Assuming your PHP script is named getRoomUsage.php
                const data = await response.json();
                return data;
            }

            // Function to populate the month dropdown dynamically
            function populateMonthDropdown(months) {
                const monthSelect = document.getElementById('monthSelect');
                monthSelect.innerHTML = ''; // Clear existing options

                // Add a default "Select a month" option
                const defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.textContent = 'Select a month';
                monthSelect.appendChild(defaultOption);

                // Add the months to the dropdown
                months.forEach(month => {
                    const option = document.createElement('option');
                    option.value = month;
                    option.textContent = formatMonth(month); // Format it to something like "January 2025"
                    monthSelect.appendChild(option);
                });
            }

            // Function to format the month for display
            function formatMonth(month) {
                const date = new Date(month + '-01'); // Create a date object (first day of the month)
                const options = {
                    year: 'numeric',
                    month: 'long'
                }; // Format options
                return date.toLocaleDateString('en-US', options); // Format as "January 2025"
            }

            // Function to create the bar chart
            function createBarChart(roomData) {
                const ctx = document.getElementById('bar').getContext('2d');

                // Prepare the data for the chart
                const roomLabels = [];
                const usageData = [];

                roomData.forEach(item => {
                    roomLabels.push(item.label); // Room labels (e.g., "Room 101")
                    usageData.push(item.usage_count); // Usage count for the room
                });

                // Destroy the previous chart if it exists
                if (window.barChart) {
                    window.barChart.destroy();
                }

                // Create the bar chart
                window.barChart = new Chart(ctx, {
                    type: 'bar', // Bar chart
                    data: {
                        labels: roomLabels, // Room numbers as labels
                        datasets: [{
                            label: 'Room Usage for Selected Month', // Legend label
                            data: usageData, // Data for the bars
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Usage Count'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Rooms'
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: true
                            }
                        }
                    }
                });
            }


            // Event listener for when the page is loaded to populate the dropdown and create chart
            document.addEventListener('DOMContentLoaded', function() {
                fetchRoomUsageData().then((roomData) => {
                    const months = roomData.months; 
                    const roomDataForChart = roomData.roomData; 

                    // Populate the dropdown with available months
                    populateMonthDropdown(months);

                    // Handle the initial chart rendering (using the first available month, e.g., the most recent one)
                    const selectedMonth = months[0]; 
                    const selectedMonthData = roomDataForChart.filter(item => item.month === selectedMonth);

                    // Pass the filtered data to the createBarChart function
                    createBarChart(selectedMonthData);

                    // Also create the Pie chart for the first month selected
                    const selectedMonthSummary = roomDataForChart.find(item => item.month === selectedMonth);
                    const presentCount = selectedMonthSummary ? selectedMonthSummary.Present : 0;
                    const absentCount = selectedMonthSummary ? selectedMonthSummary.Absent : 0;
                    createPieChart({
                        Present: presentCount,
                        Absent: absentCount
                    });
                });
            });

            // Event listener for the month selection change
            document.getElementById('monthSelect').addEventListener('change', function() {
                const selectedMonth = this.value;

                // Fetch room usage data and update the chart based on selected month
                fetchRoomUsageData().then((roomData) => {
                    if (selectedMonth) {
                        // Filter data for the selected month
                        const selectedMonthData = roomData.roomData.filter(item => item.month === selectedMonth);

                        if (selectedMonthData.length > 0) {
                            createBarChart(selectedMonthData); // Pass filtered data to createBarChart
                        } else {
                            alert('No data available for the selected month.');
                        }

                        // Update the Pie chart based on the selected month
                        const selectedMonthSummary = roomData.roomData.find(item => item.month === selectedMonth);
                        const presentCount = selectedMonthSummary ? selectedMonthSummary.Present : 0;
                        const absentCount = selectedMonthSummary ? selectedMonthSummary.Absent : 0;
                        createPieChart({
                            Present: presentCount,
                            Absent: absentCount
                        });
                    }
                });
            });

            fetch('./timein.php')
                .then(response => response.json())
                .then(data => {
                    // Extract months and login counts from the data
                    const months = data.map(item => item.month);
                    const loginCounts = data.map(item => item.count);

                    // Create the line chart
                    const ctx = document.getElementById('line').getContext('2d');
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: months,
                            datasets: [{
                                label: 'Faculty Logins per Month',
                                data: loginCounts,
                                borderColor: 'rgba(75, 192, 192, 1)',
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                tension: 0.3, // Smooth curves
                                fill: true, // Fill the area under the curve
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'top'
                                }
                            },
                            scales: {
                                x: {
                                    title: {
                                        display: true,
                                        text: 'Month'
                                    }
                                },
                                y: {
                                    title: {
                                        display: true,
                                        text: 'Logins'
                                    },
                                    beginAtZero: true // Start y-axis from 0
                                }
                            }
                        }
                    });
                })
                .catch(error => console.error('Error fetching data:', error));
        </script>

    </body>

    </html>