    <?php
    // session_start();

    if (!isset($_SESSION['loggedin']) && !$_SESSION['loggedin'] == "true") {
        header("Location: login.php");
        exit;
    }

    $faculty_id = $_SESSION['faculty_id'];
    $role = $_SESSION['role'];
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
        <script src="./assets/script.js"></script>
    </head>

    <body>

        <?php include "components/topbar.php"; ?>
        <?php include "./components/modals/confirmDeleteItem.php"; ?>

        <form action="./delete.php" method="POST">
            <div class="flex h-screen justify-around font-poppins pt-[100px] bg-gray-50">
                <div class="flex justify-center h-full w-full bg-opacity-75">

                    <div class="mr-[30px] w-full pl-[10%] pr-[5%]">



                        <div class=" w-full mb-5 flex flex-col gap-5">
                            <div class="flex gap-5 w-full">
                                <div class="relative w-full h-full flex justify-start items-center">
                                    <input onkeyup="searchTable('allStudents', 'search-dropdown')" type="search" id="search-dropdown" class="block pl-9 p-2.5 w-full  text-sm text-gray-900 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Search..." />
                                    <svg class="w-5 h-5 absolute opacity-40 left-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                    </svg>
                                </div>


                                <button type="submit" name="deleteAllSelected" class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-center text-white text-nowrap bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300">
                                    Delete Multiple
                                    <span id="checkedboxLength" class="inline-flex items-center justify-center  h-4 ms-2 text-xs font-semibold text-blue-800 bg-blue-200 rounded-full px-1">
                                        0
                                    </span>
                                </button>

                                <div class="relative max-w-sm w-[170px] ">
                                    <input type="date" id="dateFilter" class=" border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                                </div>

                                <a
                                    type="button"
                                    href="addstudent?typeUpload=single"
                                    class="w-40 text-nowrap shadow-md text-white text-center bg-red-800 hover:bg-red-900 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5   focus:outline-none ">
                                    Add Student
                                </a>
                            </div>

                            <div class="flex gap-5 w-full">
                                <select name="class" id="class" class="cursor-pointer px-5 border border-gray-500 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 hover:bg-gray-50 w-full py-2">
                                    <option selected disabled>Select Class Section</option>
                                    <?php
                                    
                                    $ys_query = "";
                                    // Query to get unique class data, excluding class '0', NULL, or empty values

                                    if($role === "Admin"){
                                        $ys_query = "
                                        SELECT DISTINCT class 
                                        FROM student 
                                        WHERE class != '0' 
                                        AND class != '' 
                                        AND class IS NOT NULL 
                                        ORDER BY class ASC
                                    ";
                                    }else{
                                            $ys_query = "
                                            SELECT DISTINCT class 
                                            FROM student 
                                            WHERE faculty_id = '$faculty_id'
                                            AND class != '0' 
                                            AND class != '' 
                                            AND class IS NOT NULL 
                                            ORDER BY class ASC
                                        ";
                                    }
                                    
                                    $ys_result = mysqli_query($conn, $ys_query);

                                    if ($ys_result && mysqli_num_rows($ys_result) > 0) {
                                        while ($ys_row = mysqli_fetch_assoc($ys_result)) {
                                    ?>
                                            <option value="<?php echo $ys_row['class']; ?>">
                                                <?php echo htmlspecialchars($ys_row['class']); ?>
                                            </option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>

                                <select class="cursor-pointer px-5 border border-gray-500 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 hover:bg-gray-50 w-full py-2"
                                    id="semester" name="semester">
                                    <option selected disabled >Select Semester</option>
                                    <option value="1st semester">1st Semester</option>
                                    <option value="2nd semester">2nd Semester</option>
                                    <option value="summer">Summer</option>
                                </select>

                                <select name="academic_year" id="academic_year" class="cursor-pointer px-5 border border-gray-500 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 hover:bg-gray-50 w-full py-2">
                                    <option selected disabled>Select Academic Year</option>
                                    <?php
                                    $ay_query ="";
                                    
                                    if($role === "Admin"){
                                        $ay_query = "
                                            SELECT DISTINCT academic_year 
                                            FROM student 
                                            WHERE academic_year != '0' 
                                            AND academic_year != '' 
                                            AND academic_year IS NOT NULL 
                                            ORDER BY academic_year ASC
                                        ";
                                    }else{
                                        $ay_query = "
                                            SELECT DISTINCT academic_year 
                                            FROM student 
                                            WHERE faculty_id = '$faculty_id' 
                                            AND academic_year != '0' 
                                            AND academic_year != '' 
                                            AND academic_year IS NOT NULL 
                                            ORDER BY academic_year ASC
                                        ";
                                    }
                                    
                                    
                                    $ay_result = mysqli_query($conn, $ay_query);

                                    if ($ay_result && mysqli_num_rows($ay_result) > 0) {
                                        while ($ay_row = mysqli_fetch_assoc($ay_result)) {
                                    ?>
                                            <option value="<?php echo $ay_row['academic_year']; ?>">
                                                <?php echo htmlspecialchars($ay_row['academic_year']); ?>
                                            </option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>

                                <select name="program" id="program" class="col-span-full cursor-pointer px-5 border border-gray-500 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 hover:bg-gray-50 w-full py-2">
                                    <option selected disabled> Select Program</option>
                                    <?php

                                    $gn_query = "";

                                    if($role === "Admin"){
                                        $gn_query = "
                                            SELECT DISTINCT student.program, program.program_name, program.id as program_id
                                            FROM student 
                                            LEFT JOIN program ON student.program = program.id 
                                            WHERE student.program != '0' AND student.program != ''  -- Exclude program '0' and empty
                                            ORDER BY student.program ASC
                                        ";
                                    }else{
                                        $gn_query = "
                                            SELECT DISTINCT student.program, program.program_name, program.id as program_id
                                            FROM student 
                                            LEFT JOIN program ON student.program = program.id 
                                            WHERE student.faculty_id = '$faculty_id' 
                                            AND student.program != '0' AND student.program != ''  -- Exclude program '0' and empty
                                            ORDER BY student.program ASC
                                        ";
                                    }
                                    
                                    $gn_result = mysqli_query($conn, $gn_query);

                                    if ($gn_result && mysqli_num_rows($gn_result) > 0) {
                                        while ($gn_row = mysqli_fetch_assoc($gn_result)) {
                                    ?>
                                            <option value="<?php echo $gn_row['program_id']; ?>">
                                                <?php echo htmlspecialchars($gn_row['program_name']); ?>
                                            </option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="relative overflow-x-auto shadow-md shadow-gray-200 rounded-lg">

                            <table class="w-full text-sm text-left rtl:text-right text-gray-500" id="allStudents">
                                <caption class="p-5 text-lg font-semibold text-left rtl:text-right text-gray-900 bg-white ">
                                    Student Management
                                </caption>
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-2 text-center">
                                            <div class="flex items-center space-x-1">
                                                <input
                                                    type="checkbox"
                                                    id="checkboxesAll"
                                                    class="w-4 h-4 text-red-500 bg-gray-100 border-gray-300 rounded focus:ring-red-400 focus:ring-2" />
                                                <label for="checkbox1" class="">All</label>
                                            </div>
                                        </th>
                                        <th scope="col" class="px-3 py-3">
                                            Student Name
                                        </th>
                                        <th scope="col" class="px-3 py-3">
                                            ID Number
                                        </th>
                                        <th scope="col" class="px-3 py-3">
                                            Email
                                        </th>
                                        <th scope="col" class="px-3 py-3">
                                            Class
                                        </th>
                                        <th scope="col" class="px-3 py-3">
                                            Semester
                                        </th>
                                        <th scope="col" class="px-3 py-3">
                                            Year Level
                                        </th>
                                        <th scope="col" class="px-3 py-3">
                                            Academic Year
                                        </th>
                                        <th scope="col" class="px-3 py-3">
                                            Program
                                        </th>
                                        <th scope="col" class="px-3 py-3">
                                            Date
                                        </th>
                                        <th scope="col" class="text-center px-3 py-3">
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php include "./studentTable.php"; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </form>


        <script>
            document.getElementById("deleteButton").addEventListener("click", () => {
                let selectedIds = getSelectedCheckboxes(); // Get selected IDs

                if (selectedIds.length === 0) {
                    alert("Please select at least one record to delete.");
                    return;
                }

                fetch("delete.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: "deleteAllSelected=true&delete_ids=" + encodeURIComponent(selectedIds.join(",")),
                })
                .then(response => response.text())
                .then(data => {
                    console.log("Server Response:", data);
                    location.reload(); // Refresh after successful deletion
                })
                .catch(error => console.error("Error:", error));
            });

            function getSelectedCheckboxes() {
                return Array.from(document.querySelectorAll(".checkboxes:checked"))
                            .map(checkbox => checkbox.value); // Get all checked values
            }


            document.addEventListener("DOMContentLoaded", function() {
                setupDateFilter("dateFilter", "allStudents", "dateColumn");
            });

            // For checkbox
            const checkedboxLength = document.getElementById('checkedboxLength');
            const checkboxesAll = document.getElementById('checkboxesAll');
            const checkboxes = document.querySelectorAll('.checkboxes');

            // Count checked checkboxes
            function updateCheckboxCount() {
                const checkedCount = document.querySelectorAll('.checkboxes:checked').length;
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

            // Filtering student table
            const sectionDropdown = document.getElementById("class");
            const semesterDropdown = document.getElementById("semester");
            const academicYearDropdown = document.getElementById("academic_year");
            const programDropdown = document.getElementById("program");
            const table = document.getElementById("allStudents");
            const rows = table.getElementsByTagName("tr");

            const programMap = {
                "1": "BSSE",
                "2": "BSEE",
                "3": "BTLE",
                "4": "BSHM",
                "5": "BSTM",
                "6": "BSIT",
                "7": "BIT"
            };

            function filterTable() {
                const selectedSection = sectionDropdown.value.trim();
                const selectedSemester = semesterDropdown.value.trim();
                const selectedAcademicYear = academicYearDropdown.value.trim();
                const selectedProgramId = programDropdown.value.trim();
                const selectedProgramAbbreviation = (programMap[selectedProgramId] || ""); // Convert to lowercase

                console.log(`Filtering: ${selectedSection}, ${selectedSemester}, ${selectedAcademicYear}, ${selectedProgramAbbreviation}`);

                for (let i = 1; i < rows.length; i++) { // Skip header row
                    let sectionCell = rows[i].querySelector(".class");
                    let semesterCell = rows[i].querySelector(".semester");
                    let academicYearCell = rows[i].querySelector(".academic_year");
                    let programCell = rows[i].querySelector(".program");

                    // Ensure text values are properly extracted
                    let sectionText = sectionCell ? sectionCell.textContent.trim() : "";
                    let semesterText = semesterCell ? semesterCell.textContent.trim() : "";
                    let academicYearText = academicYearCell ? academicYearCell.textContent.trim() : "";
                    let programText = programCell ? programCell.textContent.trim() : "";

                    let sectionMatch = selectedSection === "" || sectionText.includes(selectedSection);
                    let semesterMatch = selectedSemester === "" || semesterText.includes(selectedSemester);
                    let academicYearMatch = selectedAcademicYear === "" || academicYearText.includes(selectedAcademicYear);
                    let programMatch = selectedProgramId === "" || programText.includes(selectedProgramAbbreviation);

                    // Log for debugging
                    console.log(`Row ${i}: Section=${sectionText}, Semester=${semesterText}, Year=${academicYearText}, Program=${programText}`);
                    console.log(`Matches: Section=${sectionMatch}, Semester=${semesterMatch}, Year=${academicYearMatch}, Program=${programMatch}`);
                    console.log(sectionCell);

                    // Show the row only if it matches all selected filters
                    rows[i].style.display = (sectionMatch && semesterMatch && academicYearMatch && programMatch) ? "" : "none";
                }
            }

            // Apply filter on any dropdown change
            sectionDropdown.addEventListener("change", filterTable);
            semesterDropdown.addEventListener("change", filterTable);
            academicYearDropdown.addEventListener("change", filterTable);
            programDropdown.addEventListener("change", filterTable);


        </script>
    </body>

    </html>