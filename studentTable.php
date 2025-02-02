<?php


include "connect.php";


$section = isset($_SESSION['section']) ? $_SESSION['section'] : null;
$group_no = isset($_SESSION['groupnumber']) ? $_SESSION['groupnumber'] : null;

$faculty_id = isset($_SESSION['faculty_id']) ? $_SESSION['faculty_id'] : null;

$query = "SELECT * from student where faculty_id = '$faculty_id'  ORDER BY id DESC";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $user_id = $row['user_id'];
        $name = $row['name'];
        $imagePath = "";
        $yrsec = $row['yr_sec'];
        $groupno = $row['group_no'];

        $qavatar = "SELECT * from avatar where user_id = '$user_id' limit 1";
        $qavatar_result = mysqli_query($conn, $qavatar);

        if ($qavatar_result) {
            $qavatar_row = mysqli_fetch_assoc($qavatar_result);
            if (!empty($qavatar_row['path']) && !empty($qavatar_row['path'])) {
                $imagePath =  $qavatar_row['path'] . $qavatar_row['name'];
            } else {
                $imagePath = "https://ui-avatars.com/api/?name=" . $name . "&background=random";
            }
        } else {
        }

        $yearAndSec = "";
        $y_q = "SELECT * from yr_sec LEFT JOIN student ON yr_sec.id = student.yr_sec where student.faculty_id = $faculty_id LIMIT 1";
        $y_r = mysqli_query($conn, $y_q);
        if (mysqli_num_rows($y_r) > 0) {
            $y_row = mysqli_fetch_assoc($y_r);
            $yearAndSec = $y_row['year_and_sec'];
        }

        $groupNo = "";
        $gn_q = "SELECT * from group_no LEFT JOIN student ON group_no.id = student.group_no where student.faculty_id = $faculty_id LIMIT 1";
        $gn_r = mysqli_query($conn, $gn_q);
        if (mysqli_num_rows($gn_r) > 0) {
            $gn_row = mysqli_fetch_assoc($gn_r);
            $groupNo = $gn_row['group_number'];
        }


?>

        <tr class="bg-white border-b ">
            <td class="text-center">
                <input
                    type="checkbox"
                    id="checkboxes<?php echo $row['id']; ?>"
                    name="delete_ids[]"
                    value="<?php echo $row['id']; ?>"
                    class="checkboxes w-4 h-4 text-red-500 bg-gray-100 border-gray-300 rounded focus:ring-red-400 focus:ring-2" />
            </td>
            <td scope="row" class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap">
                <img class="w-10 h-10 rounded-full" src="<?php echo  $row['avatar'] ? "data:image/jpeg;base64, " . base64_encode($row['avatar']) : "https://ui-avatars.com/api/?name=" . $name . "&background=random"; ?>" alt="Jese image">
                <div class="ps-3">
                    <div class="text-base font-semibold"><?php echo ucwords($row['name']) ?></div>
                    <div class="font-normal text-gray-500"><?php echo $row['email']; ?></div>
                </div>
            </td>
            <td class="px-6 py-4">
                <?php echo $row['student_number']; ?>
            </td>
            <td class="px-6 py-4">
                <?php echo strtoupper($yearAndSec); ?>
            </td>
            <td class="px-6 py-4">
                <?php echo $groupNo; ?>
            </td>
            <td class="px-6 py-4 dateColumn">
                <?php echo $row['datetime']; ?>
            </td>
            <td class="text-center">
                <button type="button" onclick="toggleUpdateModal(<?php echo $row['id']; ?>)" class="text-green-700 hover:text-white border border-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                    Edit
                </button>
                <button id="<?php echo $row['id']; ?>" onclick="deleteThisStudent(this.id)" data-modal-target="popup-modal" data-modal-toggle="popup-modal" type="button" class="text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                    Delete
                </button>
            </td>

        </tr>

        <div id="update<?php echo $row['id']; ?>" class="fixed top-0 left-0 flex justify-center items-center w-full h-full bg-black/30 backdrop-opacity-50 z-50 hidden">
            <div class="w-[50em] h-[50em] bg-gray-50 rounded-lg ">
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

                        <div id="allinputs<?php echo $row['id']; ?>" class="w-full">
                            <label for="name<?php echo $row['id']; ?>" class="tracking-wide block mb-1 text-[11px] text-gray-900 uppercase font-bold">Student Name</label>

                            <div id="name<?php echo $row['id']; ?>" class="w-full">
                                <input type="text" value="<?php echo $row['name']; ?>" class="w-full border border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block p-2.5 outline-0 placeholder:tracking-wide"
                                    name="name" id="name" placeholder="First Name" required>
                                <p class="opacity-0 mb-0.25 px-2 text-[11px] text-red-600 "><span class="font-medium">Oops!</span> Credential is wrong!</p>
                            </div>


                            <div id="studentidnumber<?php echo $row['id']; ?>" class="w-full">
                                <label for="idnumber<?php echo $row['id']; ?>" class="tracking-wide block mb-1 text-[11px] text-gray-900 uppercase font-bold">Student Number</label>
                                <input type="text" value="<?php echo $row['student_number']; ?>" class="w-full border border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block p-2.5 outline-none placeholder:tracking-wide"
                                    name="idnumber" id="idnumber<?php echo $row['id']; ?>" placeholder="Student ID Number" required>
                                <p class="opacity-0 mb-0.25 px-2 text-[11px] text-red-600 "><span class="font-medium">Oops!</span> Credential is wrong!</p>
                            </div>

                            <div class="w-full">
                                <label for="email<?php echo $row['id']; ?>" class="tracking-wide block mb-1 text-[11px] text-gray-900 uppercase font-bold">Email</label>
                                <input type="email" value="<?php echo $row['email']; ?>" class="w-full border border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block p-2.5 outline-none placeholder:tracking-wide"
                                    name="email" id="email<?php echo $row['id']; ?>" placeholder="Email Address" required>
                                <p class="opacity-0 mb-0.25 px-2 text-[11px] text-red-600 "><span class="font-medium">Oops!</span> Credential is wrong!</p>
                            </div>

                            <div class="grid grid-flow-col grid-cols-4 gap-5">

                                <div class="w-full">
                                    <label for="section<?php echo $row['id']; ?>" class="tracking-wide block mb-1 text-[11px] text-gray-900 uppercase font-bold">Year and Section</label>
                                    <select class="w-full p-[10.5px] px-4 border border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block  outline-none"
                                        id="section<?php echo $row['id']; ?>" name="section" required>
                                        <?php
                                        $ys_query = "SELECT * From yr_sec";
                                        $ys_result = mysqli_query($conn, $ys_query);

                                        if (mysqli_num_rows($ys_result) > 0) {
                                            while ($ys_row = mysqli_fetch_assoc($ys_result)) {
                                        ?>
                                                <option value="<?php echo $ys_row['id']; ?>" <?php echo ($ys_row['id'] === $row['yr_sec']) ? 'selected' : ''; ?>><?php echo $ys_row['year_and_sec']; ?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                    <p class="opacity-0 mb-0.25 px-2 text-[11px] text-red-600 "><span class="font-medium">Oops!</span> Credential is wrong!</p>
                                </div>

                                <div class="w-full">
                                    <label for="group<?php echo $row['id']; ?>" class="tracking-wide block mb-1 text-[11px] text-gray-900 uppercase font-bold">Group Number</label>
                                    <select class="w-full p-[10.5px] px-4 border border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block  outline-none"
                                        id="group<?php echo $row['id']; ?>" name="groupnumber" required>
                                        <?php
                                        $ys_query = "SELECT * From group_no";
                                        $ys_result = mysqli_query($conn, $ys_query);

                                        if (mysqli_num_rows($ys_result) > 0) {
                                            while ($ys_row = mysqli_fetch_assoc($ys_result)) {
                                        ?>
                                                <option value="<?php echo $ys_row['id']; ?>" <?php echo ($ys_row['id'] === $row['group_no']) ? 'selected' : ''; ?>><?php echo $ys_row['group_number']; ?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                    <p class="opacity-0 mb-0.25 px-2 text-[11px] text-red-600 "><span class="font-medium">Oops!</span> Credential is wrong!</p>
                                </div>

                            </div>
                        </div>



                        <div class="flex items-center gap-2">
                            <button type="submit" name="updateStudent"
                                class="text-white bg-blue-500 hover:bg-blue-600 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none ">
                                Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


    <?php
    }
} else {
    // echo "Error executing query: " . mysqli_error($conn);

    ?>

    <tr class="w-full">
        <td colSpan="6" class=" w-full text-center bg-white py-4">
            No Student Available.
        </td>
    </tr>


<?php

}

?>