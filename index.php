    <?php

    // echo print_r($_SESSION);

    if (!isset($_SESSION['loggedin']) && !$_SESSION['loggedin'] == "true") {
        header("Location: login.php");
        exit;
    }

    $checkRoute = str_replace('/Qr_Attendance/', '', $_SERVER["REQUEST_URI"]);
    if ($checkRoute === '' || $checkRoute === '/') {
        header("Location: home");
    }

    include "totals.php";
    include "connect.php";
    include "components/loader.php";

    // echo print_r($_SESSION);

    $faculty_id = $_SESSION['faculty_id'];
    $default_class = $_SESSION['class'] ?? null;
    $default_semester = $_SESSION['semester'] ?? null;
    $default_academic_year = $_SESSION['academic_year'] ?? null;
    $default_room = $_SESSION['room'] ?? null;
    $default_program = $_SESSION['program'] ?? null;
    ?>

    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Attendance And Inventory</title>
        <link rel="icon" href="assets/img/favicon.ico" type="image/x-icon">
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="assets/style.css">
        <script src="assets/script.js"></script>
        <script src="src/scanner.js"></script>
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

        <div class="flex justify-around h-auto font-poppins pt-[120px] bg-gray-50">
            <form action="./add.php" method="POST">
                <div class="flex justify-center h-full w-full bg-opacity-75 px-40 pb-40">

                    <!-- scanned student -->
                    <div class=" flex flex-col items-center w-full">

                        <div class="grid grid-rows-2 grid-cols-3 w-full mb-5 gap-3">
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
                                <option disabled <?= is_null($default_program) ? 'selected' : ''; ?>>Program</option>
                                <?php
                                $p_query = "
                                    SELECT DISTINCT program.program_name, program.id 
                                    FROM student 
                                    JOIN program ON student.program = program.id 
                                    WHERE student.faculty_id = '$faculty_id' 
                                    AND student.program NOT IN ('0', '') 
                                    ORDER BY program.program_name ASC
                                ";
                                $p_result = mysqli_query($conn, $p_query);
                                
                                $programs = [];
                                while ($p_row = mysqli_fetch_assoc($p_result)) {
                                    if (!isset($programs[$p_row['program_name']])) {
                                        $selected = ($p_row['id'] === $default_program) ? 'selected' : '';
                                        echo "<option value='{$p_row['id']}' $selected>{$p_row['program_name']}</option>";
                                        $programs[$p_row['program_name']] = true;
                                    }
                                }
                                ?>
                            </select>



                        </div>

                        <!-- <div class="w-full">
                            <p class="rounded-lg w-full text-gray-700 py-3 px-4 bg-white shadow-md shadow-gray-200 text-nowrap">
                                <span class="font-medium text-green-500">Success!</span>
                                Student QR Code Scan completed!
                            </p>
                        </div> -->

                        <div class="flex gap-5 my-5">
                            <?php include "scannedStudent.php"; ?>
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
                                <div id="datepicker"></div>
                            </div>
                        </div>

                        <p class="rounded-lg w-full text-gray-700 py-3 px-4 bg-white shadow-md shadow-gray-200 text-nowrap">
                            <span class="font-medium text-yellow-500">Attention!</span>
                            Scanned QR Code information will show here.
                        </p>
                    </div>

                    <!-- line between -->
                    <div class="border border-gray-500 border-opacity-50 content-none h-[600px] mx-5"></div>

                    <!-- read -->
                    <div class="`flex flex-col gap-9 h-full w-full">

                        <div class="grid grid-flow-col gap-5 justify-arround items-center mb-7">
                            <div class="grid grid-row-3 w-full gap-3 ">
                                <div class="py-3 px-4  bg-white rounded-lg flex items-center justify-between shadow-md shadow-gray-200 gap-2">
                                    <p class="font-bold text-sm tracking-wide text-gray-950 text-nowrap">Total Students</p>
                                    <p class=" text-[20px] tracking-wide font-outfit text-gray-950"><?php echo totalStudentToday($conn); ?></p>
                                </div>
                                <div class="py-3 px-4  bg-white rounded-lg flex items-center justify-between shadow-md shadow-gray-200 gap-2">
                                    <p class="font-bold text-sm tracking-wide text-gray-950">Present</p>
                                    <p class=" text-[20px] tracking-wide font-outfit text-gray-950"><?php echo presentToday($conn); ?></p>
                                </div>
                                <div class="py-3 px-4  bg-white rounded-lg flex items-center justify-between shadow-md shadow-gray-200 gap-2">
                                    <p class="font-bold text-sm tracking-wide text-gray-950">Absent</p>
                                    <p class=" text-[20px] tracking-wide font-outfit text-gray-950"><?php echo absentToday($conn); ?></p>
                                </div>
                            </div>

                            <!-- Button for marking all selected student as absent -->
                            <button type="submit" name="markAbsent" class="flex flex-col items-center bg-white w-full h-full items-center justify-center shadow-md rounded-lg shadow-gray-400 hover:shadow-red-400 hover:bg-red-50 transition-all duration-200 text-red-700">
                                <span class="text-[40px]" id="markedStudent">0</span>
                                <span class=""> Mark Student as Absent</span>
                                <span class="text-gray-500 text-xs px-4">Use this to mark the selected or pending student as absent</span>
                            </button>
                        </div>

                        <div class="relative overflow-x-auto shadow-md shadow-gray-200 rounded-lg w-full">
                            <table class="w-full text-sm text-left text-gray-500" id="attendancetoday">
                                <caption class="relative p-5 text-md font-semibold text-left text-gray-900 bg-white">
                                    Attendance Today
                                    <a class="absolute right-3 text-[10px] font-medium text-gray-800 hover:drop-shadow-md hover:drop-shadow-gray-900" href="attendanceoverview">View All ></a>
                                </caption>

                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-2 text-center">
                                            <div class="flex items-center space-x-1">
                                                <input
                                                    type="checkbox"
                                                    id="checkabsentAll"
                                                    class="w-4 h-4 text-red-500 bg-gray-100 border-gray-300 rounded focus:ring-red-400 focus:ring-2" />
                                                <label for="checkabsentAll" class="">All</label>
                                            </div>
                                        </th>
                                        <th scope="col" class="px-2 py-2 text-[10px]">
                                            Student Name
                                        </th>
                                        <th scope="col" class="px-2 py-2 text-[10px]">
                                            ID Number
                                        </th>
                                        <th scope="col" class="px-2 py-2 text-[10px]">
                                            Section
                                        </th>
                                        <th scope="col" class="px-2 py-2 text-[10px]">
                                            Semester
                                        </th>
                                        <th scope="col" class="px-2 py-2 text-[10px]">
                                            Status
                                        </th>
                                        <th scope="col" class="px-2 py-2 text-[10px]">
                                            Date
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="tableBody">
                                    <?php include "./attendanceToday.php"; ?>
                                </tbody>
                            </table>
                        </div>

                    </div>

                </div>
            </form>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script>
            function updateTable() {
                var section = document.getElementById('section').value;
                var program = document.getElementById('program').value;

                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'attendanceToday.php?section=' + section + '&program=' + program, true);
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        document.getElementById('attendanceTable').innerHTML = xhr.responseText;
                    }
                };
                xhr.send();
            }

            function updateSession(selectedElementId, newValue) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'session.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

                // Ensure key=value format is correct
                var postData = encodeURIComponent(selectedElementId) + '=' + encodeURIComponent(newValue);
                
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        console.log("Session updated:", xhr.responseText); // Debugging
                        setTimeout(reloadTable, 500); // Delay to ensure session is updated before reloading
                    } else {
                        console.error('Error setting session variable.');
                    }
                };

                xhr.onerror = function() {
                    console.error("Network error occurred while updating session.");
                };

                xhr.send(postData);
            }

            function updateSession(selectedElementId, newValue) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'session.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

                // Ensure key=value format is correct
                var postData = encodeURIComponent(selectedElementId) + '=' + encodeURIComponent(newValue);
                
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        console.log("Session updated:", xhr.responseText); // Debugging
                        setTimeout(reloadTable, 500); // Delay to ensure session is updated before reloading
                    } else {
                        console.error('Error setting session variable.');
                    }
                };

                xhr.onerror = function() {
                    console.error("Network error occurred while updating session.");
                };

                xhr.send(postData);
            }


            // Dropdown elements
            var selectedClass = document.getElementById('class');
            var selectedSemester = document.getElementById('semester');
            var selectedRoom = document.getElementById('room');
            var selectedProgram = document.getElementById('program');
            var selectedAcadYear = document.getElementById('academic_year');
            var selectedYearLvl = document.getElementById('year_level');

            // Add change event listeners for dropdowns
            selectedClass.addEventListener('change', function() {
                var selectedOption = selectedClass.value;
                updateSession('class', selectedOption);
                setTimeout(() => {
                    location.reload(); // Refresh the page after session update
                }, 300);
            });

            selectedSemester.addEventListener('change', function() {
                var selectedOption = selectedSemester.value;
                updateSession('semester', selectedOption);
                setTimeout(() => {
                    location.reload(); // Refresh the page after session update
                }, 300);
            });

            selectedRoom.addEventListener('change', function() {
                var selectedOption = selectedRoom.value;
                updateSession('room', selectedOption);
                setTimeout(() => {
                    location.reload(); // Refresh the page after session update
                }, 300);
            });

            selectedProgram.addEventListener('change', function() {
                var selectedOption = selectedProgram.value;
                updateSession('program', selectedOption);
                setTimeout(() => {
                    location.reload(); // Refresh the page after session update
                }, 300);
            });

            selectedAcadYear.addEventListener('change', function() {
                var selectedOption = selectedAcadYear.value;
                updateSession('academic_year', selectedOption);
                setTimeout(() => {
                    location.reload(); // Refresh the page after session update
                }, 300);
            });

            // selectedYearLvl.addEventListener('change', function() {
            //     var selectedOption = selectedYearLvl.value;
            //     updateSession('year_level', selectedOption);
            //     reloadTable();
            // });

            // For checkbox
            const checkedboxLength = document.getElementById('markedStudent');
            const checkboxesAll = document.getElementById('checkabsentAll');
            const checkboxes = document.querySelectorAll('.checkabsent');

            // Count checked checkboxes
            function updateCheckboxCount() {
                const checkedCount = document.querySelectorAll('.checkabsent:checked').length;
                checkedboxLength.textContent = `${checkedCount}`;
            }

            // Select/Deselect all checkboxes
            checkboxesAll.addEventListener('change', function() {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateCheckboxCount();
            });

            // Update count whenever individual checkbox changes
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateCheckboxCount);
            });

            updateCheckboxCount();

            flatpickr("#datepicker", {
                inline: true,
                dateFormat: "Y-m-d",
                onDayCreate: function(dObj, dStr, fp, dayElem) {
                    fetch('dates.php') // Ensure this path is correct for your file
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
                                }); // Formats as "YYYY-MM-DD"

                                // Check if the current day is in the list of fetched dates
                                if (dates.includes(dateString)) {
                                    dayElem.classList.add("marked-date"); // Add a custom class to style it
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
        </script>
    </body>

    </html>