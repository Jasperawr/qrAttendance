<?php

include "connect.php";

$faculty_id = isset($_SESSION['faculty_id']) ? $_SESSION['faculty_id'] : null;

$query = "SELECT * FROM student WHERE faculty_id = '$faculty_id' ORDER BY class, semester, academic_year, program, id DESC";
$result = mysqli_query($conn, $query);

$groupedData = [];

// Organizing data into groups
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $key = $row['class'] . '|' . $row['semester'] . '|' . $row['academic_year'] . '|' . $row['program'];
        $groupedData[$key][] = $row;
    }
}else {
    // echo "Error executing query: " . mysqli_error($conn);

    ?>

    <tr class="w-full">
        <td colSpan="6" class=" w-full text-center bg-white py-4">
            No Student Available.
        </td>
    </tr>


<?php

}

// Display grouped data
foreach ($groupedData as $groupKey => $students) {
    list($class, $semester, $academic_year, $program) = explode('|', $groupKey);

    echo "<tr class='bg-gray-200 text-gray-700 font-bold'>
            <td colspan='12' class='px-3 py-2 text-center'>
                Class: $class | Semester: $semester | Academic Year: $academic_year | Program: " . getProgramName($program) . "
            </td>
          </tr>";

    foreach ($students as $row) {
        ?>
        <tr class="bg-white border-b ">
            <td class="text-center">
                <input type="checkbox" id="checkboxes<?php echo $row['id']; ?>" name="delete_ids[]" value="<?php echo $row['id']; ?>" class="checkboxes w-4 h-4 text-red-500 bg-gray-100 border-gray-300 rounded focus:ring-red-400 focus:ring-2" />
            </td>
            <td scope="row" class="flex items-center px-3 py-4 text-gray-900 whitespace-nowrap text-xs">
                <img class="w-10 h-10 rounded-full" src="<?php echo $row['avatar'] ? "data:image/jpeg;base64, " . base64_encode($row['avatar']) : "https://ui-avatars.com/api/?name=" . urlencode($row['name']) . "&background=random"; ?>" alt="Avatar">
                <div class="ps-3">
                    <div class="text-sm font-semibold"><?php echo ucwords($row['name']) ?></div>
                    <div class="text-xs font-normal text-gray-500"><?php echo $row['email']; ?></div>
                </div>
            </td>
            <td class="px-3 py-4 text-xs"><?php echo $row['student_number']; ?></td>
            <td class="px-3 py-4 text-xs"><?php echo $row['email']; ?></td>
            <td class="px-3 py-4 text-xs class"><?php echo $row['class']; ?></td>
            <td class="px-3 py-4 text-xs semester"><?php echo $row['semester']; ?></td>
            <td class="px-3 py-4 text-xs "><?php echo $row['year_level']; ?></td>
            <td class="px-3 py-4 text-xs academic_year"><?php echo $row['academic_year']; ?></td>
            <td class="px-3 py-4 text-xs font-semibold program"><?php echo getProgramName($row['program']); ?></td>
            <td class="px-3 py-4 dateColumn text-xs"><?php echo $row['datetime']; ?></td>
            <td class="flex justify-center">
                <button type="button" onclick="toggleUpdateModal(<?php echo $row['id']; ?>)" class="text-green-700 hover:text-white border border-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-xs p-1 text-center me-1 mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"><path fill="currentColor" d="m7 17.013l4.413-.015l9.632-9.54c.378-.378.586-.88.586-1.414s-.208-1.036-.586-1.414l-1.586-1.586c-.756-.756-2.075-.752-2.825-.003L7 12.583zM18.045 4.458l1.589 1.583l-1.597 1.582l-1.586-1.585zM9 13.417l6.03-5.973l1.586 1.586l-6.029 5.971L9 15.006z"/><path fill="currentColor" d="M5 21h14c1.103 0 2-.897 2-2v-8.668l-2 2V19H8.158c-.026 0-.053.01-.079.01c-.033 0-.066-.009-.1-.01H5V5h6.847l2-2H5c-1.103 0-2 .897-2 2v14c0 1.103.897 2 2 2"/></svg>
                </button>
                <button id="<?php echo $row['id']; ?>" onclick="deleteThisStudent(this.id)" data-modal-target="popup-modal" data-modal-toggle="popup-modal" type="button" class="text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-xs p-1 text-center me-2 mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 16 16"><path fill="currentColor" fill-rule="evenodd" d="M5.442 3.5H12.5A1.5 1.5 0 0 1 14 5v6a1.5 1.5 0 0 1-1.5 1.5H5.442a1.5 1.5 0 0 1-1.171-.563L1.796 8.844a1.35 1.35 0 0 1 0-1.688l2.475-3.093A1.5 1.5 0 0 1 5.44 3.5m-2.343-.374A3 3 0 0 1 5.442 2H12.5a3 3 0 0 1 3 3v6a3 3 0 0 1-3 3H5.442a3 3 0 0 1-2.343-1.126L.625 9.781a2.85 2.85 0 0 1 0-3.562zM7.28 5.47a.75.75 0 0 0-1.06 1.06L7.69 8L6.22 9.47a.75.75 0 1 0 1.06 1.06l1.47-1.47l1.47 1.47a.75.75 0 1 0 1.06-1.06L9.81 8l1.47-1.47a.75.75 0 0 0-1.06-1.06L8.75 6.94z" clip-rule="evenodd"/></svg>
                </button>
            </td>
        </tr>

        <div id="update<?php echo $row['id']; ?>" class="fixed top-0 left-0 py-20 flex justify-center items-center w-full h-full bg-black/30 backdrop-opacity-50 z-50 hidden overflow-scroll">
            <div class="w-[50em] bg-gray-50 rounded-lg mt-40">
                <div class="flex justify-between w-full items-center">
                    <p class="p-5 text-lg font-semibold text-left rtl:text-right text-gray-900">
                        Student Information
                    </p>
                    <svg onclick="toggleUpdateModal(<?php echo $row['id']; ?>)" class="mr-5 cursor-pointer hover:filter hover:drop-shadow-lg" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
                        <path fill="currentColor" d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15l-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152l2.758 3.15a1.2 1.2 0 0 1 0 1.698" />
                    </svg>
                </div>

                <form action="./update.php" method="POST" id="form" class="flex flex-col gap-5" onsubmit="return validateForm()" enctype="multipart/form-data">
                    <input type="hidden" name="student_id" id="student_id" value="<?php echo $row['id']; ?>">

                    <div class="p-5 px-20">
                        <div id="dropZone" class="mb-10">
                            <label for="avatar" class="tracking-wide block text-[11px] text-gray-900 uppercase font-bold">Student Picture</label>
                            <div id="avatar" class="flex items-center justify-center">
                                <label for="dropzone-file<?php echo $row['id']; ?>" class="dropArea relative overflow-hiddwn flex flex-col items-center justify-center  w-[200px] h-[200px] border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                        </svg>
                                        <p class="dragText mb-2 text-[10px] text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                        <p class="text-[10px] text-gray-500">SVG, PNG, JPG or JPEG</p>
                                    </div>
                                    <input id="dropzone-file<?php echo $row['id']; ?>" type="file" class="fileInput opacity-0 w-full h-full absolute" name="studentavatar" accept=".jpg,.jpeg,.png,.png" />
                                </label>
                                <div id="preview<?php echo $row['id']; ?>" class="previewImage">

                                </div>
                            </div>
                        </div>

                        <div id="allinputs<?php echo $row['id']; ?>" class="w-full pb-10">
                            <label for="name<?php echo $row['id']; ?>" class="tracking-wide block mb-1 text-[11px] text-gray-900 uppercase font-bold">Student Name</label>

                            <div id="name<?php echo $row['id']; ?>" class="w-full">
                                <input type="text" value="<?php echo $row['name']; ?>" class="w-full border border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block p-2.5 outline-0 placeholder:tracking-wide"
                                    name="name" id="name" placeholder="First Name" >
                                <p class="opacity-0 mb-0.25 px-2 text-[11px] text-red-600 "><span class="font-medium">Oops!</span> Credential is wrong!</p>
                            </div>


                            <div id="studentidnumber<?php echo $row['id']; ?>" class="w-full">
                                <label for="idnumber<?php echo $row['id']; ?>" class="tracking-wide block mb-1 text-[11px] text-gray-900 uppercase font-bold">Student Number</label>
                                <input type="text" value="<?php echo $row['student_number']; ?>" class="w-full border border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block p-2.5 outline-none placeholder:tracking-wide"
                                    name="idnumber" id="idnumber<?php echo $row['id']; ?>" placeholder="Student ID Number" >
                                <p class="opacity-0 mb-0.25 px-2 text-[11px] text-red-600 "><span class="font-medium">Oops!</span> Credential is wrong!</p>
                            </div>

                            <div class="w-full">
                                <label for="email<?php echo $row['id']; ?>" class="tracking-wide block mb-1 text-[11px] text-gray-900 uppercase font-bold">Email</label>
                                <input type="email" value="<?php echo $row['email']; ?>" class="w-full border border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block p-2.5 outline-none placeholder:tracking-wide"
                                    name="email" id="email<?php echo $row['id']; ?>" placeholder="Email Address" >
                                <p class="opacity-0 mb-0.25 px-2 text-[11px] text-red-600 "><span class="font-medium">Oops!</span> Credential is wrong!</p>
                            </div>

                            <div class="w-full">
                                <label for="class<?php echo $row['id']; ?>" class="tracking-wide block mb-1 text-[11px] text-gray-900 uppercase font-bold">Class</label>
                                <input type="class" value="<?php echo $row['class']; ?>" class="w-full border border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block p-2.5 outline-none placeholder:tracking-wide"
                                    name="class" id="class<?php echo $row['id']; ?>" placeholder="Email Address" >
                                <p class="opacity-0 mb-0.25 px-2 text-[11px] text-red-600 "><span class="font-medium">Oops!</span> Credential is wrong!</p>
                            </div>
                            <div class="w-full">
                                <label for="year_level<?php echo $row['id']; ?>" class="tracking-wide block mb-1 text-[11px] text-gray-900 uppercase font-bold">Year lLvel</label>
                                <input type="year_level" value="<?php echo $row['year_level']; ?>" class="w-full border border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block p-2.5 outline-none placeholder:tracking-wide"
                                    name="year_level" id="year_level<?php echo $row['id']; ?>" placeholder="Email Address" >
                                <p class="opacity-0 mb-0.25 px-2 text-[11px] text-red-600 "><span class="font-medium">Oops!</span> Credential is wrong!</p>
                            </div>
                            <div class="w-full">
                                <label for="academic_year<?php echo $row['id']; ?>" class="tracking-wide block mb-1 text-[11px] text-gray-900 uppercase font-bold">Academic Year</label>
                                <input type="academic_year" value="<?php echo $row['academic_year']; ?>" class="w-full border border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block p-2.5 outline-none placeholder:tracking-wide"
                                    name="academic_year" id="academic_year<?php echo $row['id']; ?>" placeholder="Email Address" >
                                <p class="opacity-0 mb-0.25 px-2 text-[11px] text-red-600 "><span class="font-medium">Oops!</span> Credential is wrong!</p>
                            </div>
                            <div class="w-full">
                                    <label for="program<?php echo $row['id']; ?>" class="tracking-wide block mb-1 text-[11px] text-gray-900 uppercase font-bold">Program</label>
                                    <select class="w-full p-[10.5px] px-4 border border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block  outline-none"
                                        id="program<?php echo $row['id']; ?>" name="program" >
                                        <option disabled selected>Select Program</option>
                                            <?php
                                            $ys_query = "SELECT * From program";
                                            $ys_result = mysqli_query($conn, $ys_query);

                                            if (mysqli_num_rows($ys_result) > 0) {
                                                while ($ys_row = mysqli_fetch_assoc($ys_result)) {
                                            ?>
                                                    <option value="<?php echo $ys_row['id']; ?>" <?php echo $ys_row['id'] === $row['program'] ? "selected" : ""; ?> ><?php echo $ys_row['program_name']; ?></option>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    <p class="opacity-0 mb-0.25 px-2 text-[11px] text-red-600 "><span class="font-medium">Oops!</span> Credential is wrong!</p>
                            </div>

                            <div class="w-full">
                                    <label for="semester<?php echo $row['id']; ?>" class="tracking-wide block mb-1 text-[11px] text-gray-900 uppercase font-bold">Semester</label>
                                    <select class="w-full p-[10.5px] px-4 border border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block  outline-none"
                                        id="semester<?php echo $row['id']; ?>" name="semester" >
                                        <option disabled selected>Select Semester</option>  
                                        <option value="1st semester" <?php echo $row['semester'] === "1st semester" ? "selected" : "" ; ?> >1st Semester</option>
                                        <option value="2nd semester"  <?php echo $row['semester'] === "2nd semester" ? "selected" : "" ; ?> >2nd Semester</option>
                                        <option value="summer" <?php echo $row['semester'] === "summer" ? "selected" : "" ; ?>  >Summer</option>
                                    </select>
                                    <p class="opacity-0 mb-0.25 px-2 text-[11px] text-red-600 "><span class="font-medium">Oops!</span> Credential is wrong!</p>
                            </div>
                            

                            <div class="flex items-center gap-2">
                                <button type="submit" name="updateStudent"
                                    class="text-white bg-blue-500 hover:bg-blue-600 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none ">
                                    Save Changes</button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>


    <?php
     }
    
} 

function getProgramName($program)
{
    $programs = [
        "1" => "BSSE",
        "2" => "BSEE",
        "3" => "BTLE",
        "4" => "BSHM",
        "5" => "BSTM",
        "6" => "BSIT",
        "7" => "BIT"
    ];
    return $programs[$program] ?? "None";
}

?>