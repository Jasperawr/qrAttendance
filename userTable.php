<?php


include "connect.php";

$query = "SELECT * from user_acount ORDER BY id DESC";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $userid = $row['userid'];
        $name = $row['name'];
        $imagePath = "https://ui-avatars.com/api/?name=" . $name . "&background=random";

        // $qavatar = "SELECT * from avatar where user_id = '$userid' limit 1";
        // $qavatar_result = mysqli_query($conn, $qavatar);

        // if ($qavatar_result) {
        //     $qavatar_row = mysqli_fetch_assoc($qavatar_result);
        //     if (!empty($qavatar_row['path']) && !empty($qavatar_row['path'])) {
        //         $imagePath =  $qavatar_row['path'] . $qavatar_row['name'];
        //     } else {
        //         $imagePath = "https://ui-avatars.com/api/?name=" . $name . "&background=random";
        //     }
        // } else {
        // }
?>

        <tr class="bg-white border-b ">
            <td scope="row" class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap">
                <img class="w-10 h-10 rounded-full" src="<?php echo $imagePath; ?>" alt="Jese image">
                <div class="ps-3">
                    <div class="text-base font-semibold"><?php echo ucwords($row['name']) ?></div>
                    <div class="font-normal text-gray-500"><?php echo $row['email']; ?></div>
                </div>
            </td>
            <td class="px-6 py-4 text-nowrap dateColumn">
                <?php echo $row['datetime']; ?>
            </td>
            <td class="text-center justify-center items-center flex-nowrap flex">
                <button id="<?php echo $row['id']; ?>" onclick="toggleUpdateModal(<?php echo $row['id']; ?>)" type="button" class="text-green-700 hover:text-white border border-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg p-2 text-center me-2 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" viewBox="0 0 24 24">
                        <path fill="currentColor" d="m12.9 6.855l4.242 4.242l-9.9 9.9H3v-4.243zm1.414-1.415l2.121-2.121a1 1 0 0 1 1.414 0l2.829 2.828a1 1 0 0 1 0 1.415l-2.122 2.121z" />
                    </svg>
                </button>
                <button id="<?php echo $row['id']; ?>" onclick="deleteThis(this.id)" data-modal-target="popup-modal" data-modal-toggle="popup-modal" type="button" class="text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg  p-2 text-center me-2 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M7 21q-.825 0-1.412-.587T5 19V6H4V4h5V3h6v1h5v2h-1v13q0 .825-.587 1.413T17 21zm2-4h2V8H9zm4 0h2V8h-2z" />
                    </svg>
                </button>
            </td>

        </tr>

        <div id="update<?php echo $row['id']; ?>" class="fixed top-0 left-0 flex justify-center items-center w-full h-full bg-black/30 backdrop-opacity-50 z-50 hidden">
            <div class="w-[50em] h-[50em] bg-gray-50 rounded-lg ">
                <div class="flex justify-between w-full items-center">
                    <p class="p-5 text-lg font-semibold text-left rtl:text-right text-gray-900">
                        User Information
                    </p>
                    <svg onclick="toggleUpdateModal(<?php echo $row['id']; ?>)" class="mr-5 cursor-pointer hover:filter hover:drop-shadow-lg" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
                        <path fill="currentColor" d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15l-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152l2.758 3.15a1.2 1.2 0 0 1 0 1.698" />
                    </svg>
                </div>

                <form action="./update.php" method="POST" id="form" class="flex flex-col gap-5" onsubmit="return validateForm()" enctype="multipart/form-data">
                    <input type="hidden" name="user_id" id="user_id" value="<?php echo $row['userid']; ?>">


                    <div class="p-5 px-20">

                        <div id="allinputs<?php echo $row['id']; ?>" class="w-full">
                            <label for="name" class="tracking-wide block mb-1 text-[11px] text-gray-900 uppercase font-bold">User Name</label>

                            <div id="name<?php echo $row['id']; ?>" class="w-full">
                                <input type="text" value="<?php echo $row['name']; ?>" class="w-full border border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block p-2.5 outline-0 placeholder:tracking-wide"
                                    name="name" id="name" placeholder="First Name" required>
                                <p class="opacity-0 mb-0.25 px-2 text-[11px] text-red-600 "><span class="font-medium">Oops!</span> Credential is wrong!</p>
                            </div>

                            <div class="w-full">
                                <label for="email<?php echo $row['id']; ?>" class="tracking-wide block mb-1 text-[11px] text-gray-900 uppercase font-bold">Email</label>
                                <input type="email" value="<?php echo $row['email']; ?>" class="w-full border border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block p-2.5 outline-none placeholder:tracking-wide"
                                    name="email" id="email<?php echo $row['id']; ?>" placeholder="Email Address" required>
                                <p class="opacity-0 mb-0.25 px-2 text-[11px] text-red-600 "><span class="font-medium">Oops!</span> Credential is wrong!</p>
                            </div>

                            <div id="userPassword<?php echo $row['id']; ?>" class="w-full">
                                <label for="password<?php echo $row['id']; ?>" class="tracking-wide block mb-1 text-[11px] text-gray-900 uppercase font-bold">User Password</label>
                                <input type="password" value="<?php echo $row['student_number']; ?>" class="w-full border border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block p-2.5 outline-none placeholder:tracking-wide"
                                    name="password" id="password<?php echo $row['id']; ?>" placeholder="**********">
                                <p class="opacity-0 mb-0.25 px-2 text-[11px] text-red-600 "><span class="font-medium">Oops!</span> Credential is wrong!</p>
                            </div>

                        </div>



                        <div class="flex items-center gap-2">
                            <button type="submit" name="updateUser"
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
        <td colSpan="7" class=" w-full text-center bg-white py-4">
            No Student Available.
        </td>
    </tr>


<?php

}

?>