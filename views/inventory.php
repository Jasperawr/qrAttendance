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
    <link rel="stylesheet" href="assets/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <script src="assets/script.js"></script>
</head>

<body>

    <?php include "components/topbar.php"; ?>
    <?php include "components/modals/zoomImage.php"; ?>


    <div class="flex h-screen justify-around font-poppins pt-[100px] bg-gray-50">
        <div class="flex justify-center h-full w-full bg-opacity-75 pl-[200px] pr-[100px] gap-5">

            <div class="w-[500px] ">
                <div class="bg-white shadow-md shadow-gray-200 rounded-lg p-5">
                    <img id="imageItem" class="h-auto max-w-normal rounded-lg" src="./assets/img/defaultImage.png" alt="image description">
                </div>
            </div>

            <div class="mr-[30px] w-full">

                <div class=" w-full mb-5 flex gap-5">
                    <div class="relative w-full h-full flex justify-start items-center">
                        <input onkeyup="searchTable('allInventory', 'search-dropdown')" type="search" id="search-dropdown" class="block pl-9 p-2.5 w-full  text-sm text-gray-900 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Search..." required />
                        <svg class="w-5 h-5 absolute opacity-40 left-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                        </svg>
                    </div>

                    <div class="relative max-w-sm w-[170px] ">
                        <input type="date" class=" border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                    </div>

                </div>



                <div class="relative overflow-x-auto shadow-md shadow-gray-200">

                    <table class="w-full text-sm text-left rtl:text-right text-gray-500" id="allInventory">
                        <caption class="p-5 text-lg font-semibold text-left rtl:text-right text-gray-900 bg-white ">
                            Inventory Items</span>
                        </caption>

                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    No
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Item Name
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Items No
                                </th>
                                <th scope="col" class="px-6 py-3 text-nowrap">
                                    QR Code
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Date
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php include "./itemTableFaculty.php"; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

</body>

</html>