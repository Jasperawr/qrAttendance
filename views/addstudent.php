    <?php
    // session_start();

    // include "../components/loader.php";

    if (!isset($_SESSION['loggedin']) && !$_SESSION['loggedin'] == "true") {
        header("Location: login.php");
        exit;
    }

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
        <script src="./assets/script.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
    </head>

    <body>

    <div id="loadingScreen" class="hidden z-50 fixed inset-0 bg-black bg-opacity-50 flex flex-col justify-center items-center text-white text-xl">
        <p id="loadingText">Processing... Please wait</p>
        <div class="w-64 mt-4 bg-gray-700 rounded-full">
            <div id="progressBar" class="h-3 bg-blue-500 rounded-full w-0"></div>
        </div>
        <p id="progressText" class="text-sm mt-2">0%</p>
        <button id="successButton" class="hidden mt-4 bg-green-500 text-white px-4 py-1 rounded text-sm hover:bg-green-700 transition-all duration-300 ease-in-out" onclick="window.location.href='students'">
            Go to Students
        </button>
    </div>



        <div id="alert-2" class="<?php if (isset($_SESSION['studentExist'])) {
                                        echo "flex";
                                    } else {
                                        echo "hidden";
                                    } ?> absolute top-20 right-10 z-50 items-center p-4 mb-4 text-red-800 rounded-lg bg-red-50" role="alert">
            <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <span class="sr-only">Info</span>
            <div class="ms-3 text-sm font-medium">
                <?php if (isset($_SESSION['studentExist'])) {
                    echo $_SESSION['studentExist'];
                } ?>
            </div>
            <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex items-center justify-center h-8 w-8"
                data-dismiss-target="#alert-2" aria-label="Close">
                <span class="sr-only">Close</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
            </button>
        </div>

        <?php include "components/topbar.php"; ?>

        <div class="flex justify-center font-poppins h-screen w-full pt-[120px] bg-gray-50">
            <div class="bg-opacity-75 flex flex-col w-full px-[200px] gap-9">

                <div>
                    <a href="?typeUpload=single">
                        <button type="button" class="text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                            Add Single</button>
                    </a>
                    <a href="?typeUpload=multiple">
                        <button type="button" class="text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                            Add Multiple</button>
                    </a>
                </div>

                <form action="./add.php" method="POST" id="single" class=" hidden flex flex-col gap-5" onsubmit="return validateForm()" enctype="multipart/form-data">


                    <!-- The whole card -->
                    <div class=" w-full bg-white p-7 rounded-lg shadow-md shadow-gray-200 flex flex-col gap-3">
                        <p class="text-xs text-gray-500">This is the form for adding a student. Just fill the form and click the add button.</p>

                        <div class="w-full relative gap-5 flex ">

                            <!-- Input avater for student or user -->
                            <div id="dropZone">
                                <label for="avatar" class="tracking-wide block text-[11px] text-gray-900 uppercase font-bold">Student Picture</label>
                                <div id="avatar" class="flex items-center justify-center">
                                    <label for="dropzone-file" class="dropArea relative overflow-hiddwn flex flex-col items-center justify-center  w-[200px] h-[200px] border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                            </svg>
                                            <p class="dragText mb-2 text-[10px] text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                            <p class="text-[10px] text-gray-500">SVG, PNG, JPG or JPEG</p>
                                        </div>
                                        <input id="dropzone-file" type="file" class="fileInput opacity-0 w-full h-full absolute" name="studentavatar" accept=".jpg,.jpeg,.png,.png" />
                                    </label>
                                    <div id="preview" class="previewImage">

                                    </div>
                                </div>
                            </div>

                            <div id="allinputs" class="w-full">
                                <label for="name" class="tracking-wide block mb-1 text-[11px] text-gray-900 uppercase font-bold">Student Name</label>
                                <div id="name" class="flex gap-5 w-full">
                                    <div id="firstname" class="w-full">
                                        <input type="text" class="w-full border border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block p-2.5 outline-0 placeholder:tracking-wide"
                                            name="fname" id="fname" placeholder="First Name" required>
                                        <p class="opacity-0 mb-0.25 px-2 text-[11px] text-red-600 "><span class="font-medium">Oops!</span> Credential is wrong!</p>
                                    </div>
                                    <div id="middlename" class="w-full">
                                        <input type="text" class="w-full border border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block p-2.5 outline-0 placeholder:tracking-wide"
                                            name="mname" id="mname" placeholder="Middle Name">
                                        <p class="opacity-0 mb-0.25 px-2 text-[11px] text-red-600 "><span class="font-medium">Oops!</span> Credential is wrong!</p>
                                    </div>
                                    <div id="lastname" class="w-full">
                                        <input type="text" class="w-full border border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block p-2.5 outline-0 placeholder:tracking-wide"
                                            name="lname" id="lname" placeholder="Last Name" required>
                                        <p class="opacity-0 mb-0.25 px-2 text-[11px] text-red-600 "><span class="font-medium">Oops!</span> Credential is wrong!</p>
                                    </div>
                                    <div class="w-full">
                                        <select class="w-full p-[10.5px] px-4 border border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block  outline-none"
                                            id="suffix" name="suffix">
                                            <option disabled selected>Suffix</option>
                                            <option value="jr">Jr</option>
                                            <option value="sr">Sr</option>
                                            <option value="i">I</option>
                                            <option value="ii">II</option>
                                            <option value="iii">III</option>
                                        </select>
                                        <p class="opacity-0 mb-0.25 px-2 text-[11px] text-red-600 "><span class="font-medium">Oops!</span> Credential is wrong!</p>
                                    </div>
                                </div>


                                <div class="flex gap-5 w-full">

                                    <div id="studentidnumber" class="w-full">
                                        <label for="idnumber" class="tracking-wide block mb-1 text-[11px] text-gray-900 uppercase font-bold">Student Number</label>
                                        <input type="text" class="w-full border border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block p-2.5 outline-none placeholder:tracking-wide"
                                            name="idnumber" id="idnumber" placeholder="Student ID Number" required>
                                        <p class="opacity-0 mb-0.25 px-2 text-[11px] text-red-600 "><span class="font-medium">Oops!</span> Credential is wrong!</p>
                                    </div>

                                    <div class="w-full">
                                        <label for="email" class="tracking-wide block mb-1 text-[11px] text-gray-900 uppercase font-bold">Email</label>
                                        <input type="email" class="w-full border border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block p-2.5 outline-none placeholder:tracking-wide"
                                            name="email" id="email" placeholder="Email Address" required>
                                        <p class="opacity-0 mb-0.25 px-2 text-[11px] text-red-600 "><span class="font-medium">Oops!</span> Credential is wrong!</p>
                                    </div>



                                </div>

                                <div class="flex gap-5 w-full">

                                    <div class="w-full">
                                        <label for="class" class="tracking-wide block mb-1 text-[11px] text-gray-900 uppercase font-bold">Class section</label>
                                        <input type="class" class="w-full border border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block p-2.5 outline-none placeholder:tracking-wide"
                                            name="class" id="class" placeholder="Class Section" required>
                                        <p class="opacity-0 mb-0.25 px-2 text-[11px] text-red-600 "><span class="font-medium">Oops!</span> Credential is wrong!</p>
                                    </div>

                                    <div class="w-full">
                                        <label for="program" class="tracking-wide block mb-1 text-[11px] text-gray-900 uppercase font-bold">Program</label>
                                        <select class="w-full p-[10.5px] px-4 border border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block  outline-none"
                                            id="program" name="program" required>
                                            <option disabled selected>Select Program</option>
                                            <?php
                                            $ys_query = "SELECT * From program";
                                            $ys_result = mysqli_query($conn, $ys_query);

                                            if (mysqli_num_rows($ys_result) > 0) {
                                                while ($ys_row = mysqli_fetch_assoc($ys_result)) {
                                            ?>
                                                    <option value="<?php echo $ys_row['id']; ?>"><?php echo $ys_row['program_name']; ?></option>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                        <p class="opacity-0 mb-0.25 px-2 text-[11px] text-red-600 "><span class="font-medium">Oops!</span> Credential is wrong!</p>
                                    </div>

                                </div>

                                <div class="flex gap-5 w-full">


                                    <div class="w-full">
                                        <label for="year_level" class="tracking-wide block mb-1 text-[11px] text-gray-900 uppercase font-bold">Year Level</label>
                                        <input type="year_level" class="w-full border border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block p-2.5 outline-none placeholder:tracking-wide"
                                            name="year_level" id="year_level" placeholder="e.g. 1st Year" required>
                                        <p class="opacity-0 mb-0.25 px-2 text-[11px] text-red-600 "><span class="font-medium">Oops!</span> Credential is wrong!</p>
                                    </div>

                                    <div class="w-full">
                                        <label for="year_level" class="tracking-wide block mb-1 text-[11px] text-gray-900 uppercase font-bold">Semester</label>

                                        <select class="w-full p-[10.5px] px-4 border border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block  outline-none"
                                            id="semester" name="semester">
                                            <option disabled selected>Select Semester</option>
                                            <option value="1st semester">1st Semester</option>
                                            <option value="2nd semester">2nd Semester</option>
                                            <option value="summer">Summer</option>
                                        </select>
                                        <p class="opacity-0 mb-0.25 px-2 text-[11px] text-red-600 "><span class="font-medium">Oops!</span> Credential is wrong!</p>
                                    </div>

                                    <div class="w-full">
                                        <label for="academic_year" class="tracking-wide block mb-1 text-[11px] text-gray-900 uppercase font-bold">Academic Year</label>
                                        <input type="academic_year" class="w-full border border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block p-2.5 outline-none placeholder:tracking-wide"
                                            name="academic_year" id="academic_year" placeholder="e.g.  2025-2026" required>
                                        <p class="opacity-0 mb-0.25 px-2 text-[11px] text-red-600 "><span class="font-medium">Oops!</span> Credential is wrong!</p>
                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="flex justify-end gap-2 w-full">
                            <button type="submit" name="addanother"
                                class="text-white bg-blue-500 hover:bg-blue-600 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none ">
                                Add Another</button>
                            <button type="submit" name="addstudent"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none ">
                                Add</button>
                        </div>
                    </div>
                    <!-- End of The whole card -->

                </form>

                <div id="multiple" class="hidden bg-white px-8 py-5 rounded-lg shadow-md flex flex-col gap-2 space-x-4 w-full">

                    <p class="text-xs text-gray-500">This is the form for adding a student. Just fill the form and click the add button.</p>

                    <!-- Form -->
                    <form id="uploadForm" method="post" enctype="multipart/form-data" class="flex flex-col gap-4 justify-center items-center w-full">


                        <div class="flex gap-5 w-full">

                            <div class="w-full">
                                <label for="class1" class="tracking-wide block mb-1 text-[11px] text-gray-900 uppercase font-bold">Class section</label>
                                <input type="class1" class="w-full border border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block p-2.5 outline-none placeholder:tracking-wide"
                                    name="class1" id="class1" placeholder="Class Section" required>
                                <p class="opacity-0 mb-0.25 px-2 text-[11px] text-red-600 "><span class="font-medium">Oops!</span> Credential is wrong!</p>
                            </div>

                            <div class="w-full">
                                <label for="program1" class="tracking-wide block mb-1 text-[11px] text-gray-900 uppercase font-bold">Program</label>
                                <select class="w-full p-[10.5px] px-4 border border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block  outline-none"
                                    id="program1" name="program1" required>
                                    <option disabled selected>Select Program</option>
                                    <?php
                                    $ys_query = "SELECT * From program";
                                    $ys_result = mysqli_query($conn, $ys_query);

                                    if (mysqli_num_rows($ys_result) > 0) {
                                        while ($ys_row = mysqli_fetch_assoc($ys_result)) {
                                    ?>
                                            <option value="<?php echo $ys_row['id']; ?>"><?php echo $ys_row['program_name']; ?></option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                                <p class="opacity-0 mb-0.25 px-2 text-[11px] text-red-600 "><span class="font-medium">Oops!</span> Credential is wrong!</p>
                            </div>

                        </div>

                        <div class="flex gap-5 w-full">


                            <div class="w-full">
                                <label for="year_level1" class="tracking-wide block mb-1 text-[11px] text-gray-900 uppercase font-bold">Year Level</label>
                                <input type="year_level1" class="w-full border border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block p-2.5 outline-none placeholder:tracking-wide"
                                    name="year_level1" id="year_level1" placeholder="e.g. 1st Year" required>
                                <p class="opacity-0 mb-0.25 px-2 text-[11px] text-red-600 "><span class="font-medium">Oops!</span> Credential is wrong!</p>
                            </div>

                            <div class="w-full">
                                <label for="semester1" class="tracking-wide block mb-1 text-[11px] text-gray-900 uppercase font-bold">Semester</label>

                                <select class="w-full p-[10.5px] px-4 border border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block  outline-none"
                                    id="semester1" name="semester1">
                                    <option disabled selected>Select Semester</option>
                                    <option value="1st semester">1st Semester</option>
                                    <option value="2nd semester">2nd Semester</option>
                                    <option value="summer">Summer</option>
                                </select>
                                <p class="opacity-0 mb-0.25 px-2 text-[11px] text-red-600 "><span class="font-medium">Oops!</span> Credential is wrong!</p>
                            </div>

                            <div class="w-full">
                                <label for="academic_year1" class="tracking-wide block mb-1 text-[11px] text-gray-900 uppercase font-bold">Academic Year</label>
                                <input type="academic_year1" class="w-full border border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block p-2.5 outline-none placeholder:tracking-wide"
                                    name="academic_year1" id="academic_year1" placeholder="e.g.  2025-2026" required>
                                <p class="opacity-0 mb-0.25 px-2 text-[11px] text-red-600 "><span class="font-medium">Oops!</span> Credential is wrong!</p>
                            </div>

                        </div>

                        <div class="flex gap-5 w-full">

                            <div class="w-full">
                                <label for="excelFile" class="tracking-wide block mb-1 text-[11px] text-gray-900 uppercase font-bold">Upload file</label>
                                <input
                                    type="file"
                                    name="excelFile"
                                    id="excelFile"
                                    accept=".csv, .xlsx, .xls"
                                    class="block w-full text-sm text-gray-500 border border-gray-300 rounded-lg file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                    required>
                                <p class="opacity-0 mb-0.25 px-2 text-[11px] text-red-600 "><span class="font-medium">Oops!</span> Credential is wrong!</p>
                            </div>



                        </div>

                        <div class="flex justify-end gap-2 w-full">
                            <button type="submit" name="uploadExcel"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none ">
                                Upload</button>
                        </div>
                        <div id="previewSection" class="hidden w-full mt-5">
                            <h3 class="text-xl font-bold mb-3">Preview Data</h3>
                            <table class="w-full text-sm text-gray-900" id="studentsList">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2 text-left">Student No</th>
                                        <th class="px-4 py-2 text-left">Full Name</th>
                                        <th class="px-4 py-2 text-left">Email</th>
                                    </tr>
                                </thead>
                                <tbody id="previewTableBody">
                                    <!-- Data will be populated here -->
                                </tbody>
                            </table>
                        </div>

                    </form>
                </div>

                <!-- Preview Section -->


                <!-- <div class="relative w-full">
                    <table class=" w-full shadow-md shadow-gray-200 rounded-lg text-sm text-left rtl:text-right text-gray-500">
                        <caption class="p-5 text-lg font-semibold text-left rtl:text-right text-gray-900 bg-white ">
                            Student Management</span>
                        </caption>
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
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
                            <th scope="col" class="text-center px-6 py-3">
                                Action
                            </th>
                        </thead>
                        <tbody>
                            <?php
                            // include "./studentTable.php"; 
                            ?>
                        </tbody>
                    </table>
                </div> -->




            </div>
        </div>

        <script>

            function showLoadingScreen(message = "Processing... Please wait") {
                document.getElementById("loadingScreen").classList.remove("hidden");
                document.getElementById("progressBar").style.width = "0%";
                document.getElementById("progressText").innerText = "0%";
                document.getElementById("loadingText").innerText = message;
                document.getElementById("successButton").classList.add("hidden");
            }

            function updateLoadingProgress(percent, text = "") {
                let progressBar = document.getElementById("progressBar");
                let progressText = document.getElementById("progressText");

                progressBar.style.width = percent + "%";
                progressText.innerText = `${percent}% ${text}`;
            }

            function hideLoadingScreen(successMessage = "Process Completed!", redirectURL = null) {
                let loadingText = document.getElementById("loadingText");
                let successButton = document.getElementById("successButton");

                loadingText.innerText = successMessage;
                document.getElementById("progressBar").style.width = "100%";
                document.getElementById("progressText").innerText = "100%";

                successButton.classList.remove("hidden");
                if (redirectURL) {
                    successButton.onclick = () => window.location.href = redirectURL;
                } else {
                    successButton.style.display = "none"; // Hide button if no redirect
                }
            }

            function hideLoadingOnError(errorMessage) {
                alert(errorMessage);
                document.getElementById("loadingScreen").classList.add("hidden");
            }

            document.getElementById("uploadForm").addEventListener("submit", async function (event) {
                event.preventDefault();
                showLoadingScreen("Uploading File...");

                let formData = new FormData(this);
                formData.append("uploadExcel", "1");

                try {
                    const response = await fetch("add.php", { method: "POST", body: formData });
                    const reader = response.body.getReader();
                    const decoder = new TextDecoder();

                    let receivedText = "";

                    while (true) {
                        const { done, value } = await reader.read();
                        if (done) break;

                        receivedText += decoder.decode(value, { stream: true });
                        const lines = receivedText.trim().split("\n");

                        lines.forEach((line) => {
                            let cleanLine = line.replace("data: ", "").trim();
                            let parts = cleanLine.split("|");
                            let percent = parseInt(parts[0]);

                            if (!isNaN(percent)) {
                                updateLoadingProgress(percent, parts[1] || "");
                            }

                            if (percent === 100) {
                                hideLoadingScreen("Upload Successful!", "students");
                            }
                        });
                    }
                } catch (error) {
                    hideLoadingOnError("Upload failed! " + error);
                }
            });

            // Get query parameter value
            const typeUpload = new URLSearchParams(window.location.search).get('typeUpload');

            // Toggle visibility based on 'typeUpload'
            document.getElementById('single').classList.toggle('hidden', typeUpload !== 'single');
            document.getElementById('multiple').classList.toggle('hidden', typeUpload !== 'multiple');

            // Listen for file input change and handle file reading
            document.getElementById("excelFile").addEventListener("change", function(event) {
                const file = event.target.files[0];
                if (!file) {
                    alert('Please select an Excel file!');
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    const data = new Uint8Array(e.target.result);
                    const workbook = XLSX.read(data, {
                        type: "array"
                    });

                    const previewTableBody = document.getElementById("previewTableBody");
                    previewTableBody.innerHTML = "";

                    // Loop through sheets in the workbook
                    workbook.SheetNames.forEach((sheetName) => {
                        const sheet = workbook.Sheets[sheetName];
                        const jsonData = XLSX.utils.sheet_to_json(sheet, {
                            header: 1,
                            range: 35
                        });

                        // Find column indices for 'Student No' and 'Full Name'
                        const headerRow = jsonData[0];
                        const studentNoIndex = headerRow.indexOf('Student No');
                        const fullNameIndex = headerRow.indexOf('Full Name');

                        if (studentNoIndex === -1 || fullNameIndex === -1) {
                            alert(`Headers "Student No" and "Full Name" not found in sheet "${sheetName}".`);
                            return;
                        }

                        // Filter rows with valid Student No and Full Name
                        const filteredData = jsonData.slice(1).filter(row => {
                            const studentNo = row[studentNoIndex];
                            const fullName = row[fullNameIndex];
                            return studentNo && fullName && studentNo.trim() !== '' && fullName.trim() !== '';
                        });

                        // Display filtered data
                        filteredData.forEach((row) => {
                            const studentNo = row[studentNoIndex] || '';
                            const fullName = row[fullNameIndex] || '';
                            const email = row[38] || ''; // Change value to 38

                            const rowElement = document.createElement("tr");
                            rowElement.innerHTML = `<td class="px-4 py-2"><input type="text" name="student_no[]" value="${studentNo}" hidden>${studentNo}</td>
                                        <td class="px-4 py-2"><input type="text" name="full_name[]" value="${fullName}" hidden>${fullName}</td>
                                        <td class="px-4 py-2"><input type="text" name="email[]" value="${email}" hidden>${email}</td>`;

                            previewTableBody.appendChild(rowElement);
                        });
                    });
                    document.getElementById("previewSection").classList.remove("hidden");
                };
                reader.readAsArrayBuffer(file);
            });
        </script>

    </body>

    </html>