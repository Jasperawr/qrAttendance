    <?php
    session_start();

    if (!isset($_SESSION['loggedin']) && !$_SESSION['loggedin'] == "true") {
        header("Location: login.php");
        exit;
    }

    include "totals.php";
    include "connect.php";

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
    </head>

    <body>

        <?php include "components/topbar.php"; ?>

        <div class="flex justify-around font-poppins pt-[100px] bg-gray-50 h-screen">
            <div class="flex justify-center h-full w-full bg-opacity-75">

                <div class="mr-[30px] w-full px-[200px]">

                    <div class="grid grid-cols-4  gap-9 mb-7">
                        <div class="p-6  bg-white rounded-lg flex justify-between shadow-md shadow-gray-200">
                            <div class="flex flex-col justify-between align-center">
                                <p class="font-bold text-[35px] tracking-wide font-outfit text-gray-950"><?php echo totalStudent($conn); ?></p>
                                <p class="font-bold text-[16px] tracking-wide text-gray-950 text-nowrap">Total Students</p>
                            </div>
                            <svg class="ml-8 mt-2 text-red-600" xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" viewBox="0 0 16 16">
                                <path fill="currentColor" d="M15 14s1 0 1-1s-1-4-5-4s-5 3-5 4s1 1 1 1zm-7.978-1L7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276c.593.69.758 1.457.76 1.72l-.008.002l-.014.002zM11 7a2 2 0 1 0 0-4a2 2 0 0 0 0 4m3-2a3 3 0 1 1-6 0a3 3 0 0 1 6 0M6.936 9.28a6 6 0 0 0-1.23-.247A7 7 0 0 0 5 9c-4 0-5 3-5 4q0 1 1 1h4.216A2.24 2.24 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904c.243-.294.526-.569.846-.816M4.92 10A5.5 5.5 0 0 0 4 13H1c0-.26.164-1.03.76-1.724c.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0a3 3 0 0 1-6 0m3-2a2 2 0 1 0 0 4a2 2 0 0 0 0-4" />
                            </svg>
                        </div>
                        <div class="p-6  bg-white rounded-lg flex justify-between shadow-md shadow-gray-200">
                            <div class="flex flex-col justify-between align-center">
                                <p class="font-bold text-[35px] tracking-wide font-outfit text-gray-950"><?php echo present($conn); ?></p>
                                <p class="font-bold text-[16px] tracking-wide text-gray-950">Present</p>
                            </div>
                            <svg class="ml-8 mt-2 text-red-600" xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M21 21V5H3v8H1V5q0-.825.588-1.412T3 3h18q.825 0 1.413.588T23 5v14q0 .825-.587 1.413T21 21M9 14q-1.65 0-2.825-1.175T5 10t1.175-2.825T9 6t2.825 1.175T13 10t-1.175 2.825T9 14m0-2q.825 0 1.413-.587T11 10t-.587-1.412T9 8t-1.412.588T7 10t.588 1.413T9 12M1 22v-2.8q0-.85.438-1.562T2.6 16.55q1.55-.775 3.15-1.162T9 15t3.25.388t3.15 1.162q.725.375 1.163 1.088T17 19.2V22zm2-2h12v-.8q0-.275-.137-.5t-.363-.35q-1.35-.675-2.725-1.012T9 17t-2.775.338T3.5 18.35q-.225.125-.363.35T3 19.2zm6 0" />
                            </svg>
                        </div>
                        <div class="p-6  bg-white rounded-lg flex justify-between shadow-md shadow-gray-200">
                            <div class="flex flex-col justify-between align-center">
                                <p class="font-bold text-[35px] tracking-wide font-outfit text-gray-950"><?php echo late($conn); ?></p>
                                <p class="font-bold text-[16px] tracking-wide text-gray-950">Absent</p>
                            </div>
                            <svg class="ml-8 mt-2 text-red-600" xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M23 15.5c0-.71-.16-1.36-.45-1.96a7 7 0 0 0-3.69-3.92a6.55 6.55 0 0 0-1.9-3.58C15.6 4.68 13.95 4 12 4c-1.58 0-3 .47-4.25 1.43s-2.08 2.19-2.5 3.72c-1.25.28-2.29.93-3.08 1.95S1 13.28 1 14.58c0 1.51.54 2.8 1.61 3.85C3.69 19.5 5 20 6.5 20h3.76c1.27 1.81 3.36 3 5.74 3c3.87 0 7-3.13 7-7zM6.5 18c-.97 0-1.79-.34-2.47-1C3.34 16.29 3 15.47 3 14.5s.34-1.79 1.03-2.47C4.71 11.34 5.53 11 6.5 11H7c0-1.38.5-2.56 1.46-3.54C9.44 6.5 10.62 6 12 6s2.56.5 3.54 1.46c.46.47.81 1 1.05 1.57C16.4 9 16.2 9 16 9c-3.87 0-7 3.13-7 7c0 .7.11 1.37.29 2zm9.5 3c-2.76 0-5-2.24-5-5s2.24-5 5-5s5 2.24 5 5s-2.24 5-5 5m.5-4.75l2.86 1.69l-.75 1.22L15 17v-5h1.5z" />
                            </svg>
                        </div>

                        <div class="p-6  bg-white rounded-lg flex justify-between shadow-md shadow-gray-200">
                            <div class="flex flex-col justify-between align-center">
                                <p class="font-bold text-[35px] tracking-wide font-outfit text-gray-950"><?php echo absent($conn); ?></p>
                                <p class="font-bold text-[16px] tracking-wide text-gray-950">Late</p>
                            </div>
                            <svg class="ml-8 mt-2 text-red-600" xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M23 15.5c0-.71-.16-1.36-.45-1.96a7 7 0 0 0-3.69-3.92a6.55 6.55 0 0 0-1.9-3.58C15.6 4.68 13.95 4 12 4c-1.58 0-3 .47-4.25 1.43s-2.08 2.19-2.5 3.72c-1.25.28-2.29.93-3.08 1.95S1 13.28 1 14.58c0 1.51.54 2.8 1.61 3.85C3.69 19.5 5 20 6.5 20h3.76c1.27 1.81 3.36 3 5.74 3c3.87 0 7-3.13 7-7zM6.5 18c-.97 0-1.79-.34-2.47-1C3.34 16.29 3 15.47 3 14.5s.34-1.79 1.03-2.47C4.71 11.34 5.53 11 6.5 11H7c0-1.38.5-2.56 1.46-3.54C9.44 6.5 10.62 6 12 6s2.56.5 3.54 1.46c.46.47.81 1 1.05 1.57C16.4 9 16.2 9 16 9c-3.87 0-7 3.13-7 7c0 .7.11 1.37.29 2zm9.5 3c-2.76 0-5-2.24-5-5s2.24-5 5-5s5 2.24 5 5s-2.24 5-5 5m.5-4.75l2.86 1.69l-.75 1.22L15 17v-5h1.5z" />
                            </svg>
                        </div>
                    </div>

                    <div class=" w-full mb-5 flex gap-5">
                        <div class="relative w-full h-full flex justify-start items-center">
                            <input onkeyup="searchTable('allAttendance', 'search-dropdown')" type="search" id="search-dropdown" class="block pl-9 p-2.5 w-full  text-sm text-gray-900 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Search..." required />
                            <svg class="w-5 h-5 absolute opacity-40 left-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                            </svg>
                        </div>

                        <select id="statusFilter" class="px-5 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-[170px] py-2">
                            <option value="all">All</option>
                            <option value="present">Present</option>
                            <option value="absent">Absent</option>
                            <option value="late">Late</option>
                        </select>

                        <div class="relative max-w-sm w-[170px] ">
                            <input type="date" id="dateFilter" class=" border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                        </div>

                    </div>



                    <div class="relative overflow-x-auto shadow-md shadow-gray-200 rounded-lg">

                        <table class="w-full text-sm text-left text-gray-500" id="allAttendance">
                            <caption class="p-5 text-lg font-semibold text-left text-gray-900 bg-white ">
                                Attendance Overview
                            </caption>
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Student Name
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        ID Number
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Section
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Group
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3">
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

            </div>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // attendance
                setupTableFilter("statusFilter", "allAttendance", "statusAttendance");
                setupDateFilter("dateFilter", "allAttendance", "dateAttendance");

            });
        </script>

    </body>

    </html>