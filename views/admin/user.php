<?php
session_start();

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
    <link rel="stylesheet" href="./assets/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <script src="./assets/script.js"></script>
    <script src="./src/scanner.js"></script>
</head>

<body>

    <?php include "./components/modals/confirmDelete.php"; ?>
    <?php include "./components/modals/updateModal.php"; ?>

    <div id="alert-2" class="<?php if (isset($_SESSION['userExist'])) {
                                    echo "flex";
                                } else {
                                    echo "hidden";
                                } ?> absolute top-20 right-10 z-50 items-center p-4 mb-4 text-red-800 rounded-lg bg-red-50" role="alert">
        <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
        </svg>

        <span class="sr-only">Info</span>

        <div class="ms-3 text-sm font-medium">
            <?php if (isset($_SESSION['userExist'])) {
                echo $_SESSION['userExist'];
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

    <div class="flex h-screen justify-around font-poppins pt-[100px]">
        <div class="flex h-full w-full bg-opacity-75 pr-[10%]">

            <div class="mr-[30px] w-full pl-[200px] flex gap-5">

                <!-- The whole card -->
                <div class=" w-[500px] ">

                    <form action="./add.php" method="POST" onsubmit="return validateForm()" id="form">
                        <div class="w-full relative gap-5 flex flex-col bg-white p-7 pb-[80px] rounded-lg shadow-md shadow-gray-200">

                            <p class=" text-lg font-semibold text-left  rtl:text-right text-gray-900  border-b-[1px]">
                                Add Faculty</span>
                            </p>

                            <div id="allinputs" class="w-full">
                                <label for="firstname" class=" tracking-wide block mb-1 text-[11px] text-gray-900 uppercase font-bold">First Name</label>
                                <div id="firstname" class="w-full">
                                    <input type="text" class="w-full border border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block p-2.5 outline-0 placeholder:tracking-wide"
                                        name="fname" id="fname" placeholder="First Name" required>
                                    <p class="opacity-0 mb-0.25 px-2 text-[11px] text-red-600 "><span class="font-medium">Oops!</span> Credential is wrong!</p>
                                </div>

                                <label for="lastname" class=" tracking-wide block mb-1 text-[11px] text-gray-900 uppercase font-bold">Last Name</label>
                                <div id="lastname" class="w-full">
                                    <input type="text" class=" w-full border border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block p-2.5 outline-0 placeholder:tracking-wide"
                                        name="lname" id="lname" placeholder="Last Name" required>
                                    <p class="opacity-0 mb-0.25 px-2 text-[11px] text-red-600 "><span class="font-medium">Oops!</span> Credential is wrong!</p>
                                </div>

                                <div class="w-full">
                                    <label for="email" class=" tracking-wide block mb-1 text-[11px] text-gray-900 uppercase font-bold">Email</label>
                                    <input type="email" class="w-full border border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block p-2.5 outline-none placeholder:tracking-wide"
                                        name="email" id="email" placeholder="Email Address" required>
                                    <p class="opacity-0 mb-0.25 px-2 text-[11px] text-red-600 "><span class="font-medium">Oops!</span> Credential is wrong!</p>
                                </div>

                                <div class="w-full">
                                    <label for="password" class=" tracking-wide block mb-1 text-[11px] text-gray-900 uppercase font-bold">Temporary Password</label>
                                    <div class="flex justify-end relative">
                                        <input type="password" class="border border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 outline-0 placeholder:tracking-wide" name="password" id="password" placeholder="Enter your Password">
                                        <!-- show password -->
                                        <svg onclick="showPassword()" id="eye_1" class=" absolute opacity-30 top-2.5 right-2 cursor-pointer" xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" viewBox="0 0 24 24">
                                            <path fill="currentColor" d="M2 5.27L3.28 4L20 20.72L18.73 22l-3.08-3.08c-1.15.38-2.37.58-3.65.58c-5 0-9.27-3.11-11-7.5c.69-1.76 1.79-3.31 3.19-4.54zM12 9a3 3 0 0 1 3 3a3 3 0 0 1-.17 1L11 9.17A3 3 0 0 1 12 9m0-4.5c5 0 9.27 3.11 11 7.5a11.8 11.8 0 0 1-4 5.19l-1.42-1.43A9.86 9.86 0 0 0 20.82 12A9.82 9.82 0 0 0 12 6.5c-1.09 0-2.16.18-3.16.5L7.3 5.47c1.44-.62 3.03-.97 4.7-.97M3.18 12A9.82 9.82 0 0 0 12 17.5c.69 0 1.37-.07 2-.21L11.72 15A3.064 3.064 0 0 1 9 12.28L5.6 8.87c-.99.85-1.82 1.91-2.42 3.13" />
                                        </svg>
                                        <svg onclick="showPassword()" id="eye_2" style="display:none;" class=" absolute opacity-30 top-2.5 right-2 cursor-pointer" xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" viewBox="0 0 24 24">
                                            <path fill="currentColor" d="M12 9a3 3 0 0 1 3 3a3 3 0 0 1-3 3a3 3 0 0 1-3-3a3 3 0 0 1 3-3m0-4.5c5 0 9.27 3.11 11 7.5c-1.73 4.39-6 7.5-11 7.5S2.73 16.39 1 12c1.73-4.39 6-7.5 11-7.5M3.18 12a9.821 9.821 0 0 0 17.64 0a9.821 9.821 0 0 0-17.64 0" />
                                        </svg>
                                    </div>
                                </div>

                            </div>


                            <div class="absolute right-7 bottom-8 flex items-center gap-2 ">
                                <button type="submit" name="adduser" class="button-click text-gray-100 font-medium flex justify-evenly items-center w-[100px] ">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="M20 14h-6v6h-4v-6H4v-4h6V4h4v6h6z" />
                                    </svg>
                                    Add
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
                <!-- End of The whole card -->

                <div class="w-full">
                    <div class=" w-full mb-5 flex gap-5">
                        <div class="relative w-full h-full flex justify-start items-center">
                            <input onkeyup="searchTable('allUser', 'search-dropdown')" type="search" id="search-dropdown" class="block pl-9 p-2.5 w-full  text-sm text-gray-900 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Search..." required />
                            <svg class="w-5 h-5 absolute opacity-40 left-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                            </svg>
                        </div>

                        <div class="relative max-w-sm w-[170px] ">
                            <input type="date" class=" border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                        </div>
                    </div>

                    <div class="relative overflow-x-auto shadow-md shadow-gray-200 rounded-lg w-full">

                        <div id="alert-3" class="<?php if (isset($_SESSION['notifChange'])) {
                                                        echo "flex";
                                                    } else {
                                                        echo "hidden";
                                                    } ?> absolute top-20 right-10 z-50 items-center p-4 mb-4 text-red-800 rounded-lg bg-red-50" role="alert">
                            <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                            </svg>

                            <div class="ms-3 text-sm font-medium">
                                <?php if (isset($_SESSION['notifChange'])) {
                                    echo $_SESSION['notifChange'];
                                } ?>
                            </div>

                            <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 inline-flex items-center justify-center h-8 w-8"
                                data-dismiss-target="#alert-3" aria-label="Close">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                </svg>
                            </button>
                        </div>

                        <table class="w-full text-sm text-left rtl:text-right text-gray-500" id="allUser">
                            <caption class="p-5 text-lg font-semibold text-left rtl:text-right text-gray-900 bg-white ">
                                User/Faculty Management</span>
                            </caption>
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Student Name
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Created Date
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php include "./userTable.php"; ?>
                            </tbody>
                        </table>
                    </div>
                </div>



            </div>

        </div>
    </div>

</body>

</html>