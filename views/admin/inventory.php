<?php
session_start();

if (!isset($_SESSION['loggedin']) && !$_SESSION['loggedin'] == "true") {
    header("Location: ./login.php");
    exit;
}

include "./totals.php";
include "./connect.php";

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
    <script src="./assets/script.js"></script>
    <script src="./src/scanner.js"></script>

</head>

<body>
    <?php include "components/topbar.php"; ?>
    <?php include "./components/modals/confirmDeleteItem.php"; ?>
    <?php include "./components/modals/updateItemModal.php"; ?>
    <?php include "./components/modals/zoomImage.php"; ?>

    <div class="flex h-full justify-around font-poppins pt-[130px] bg-gray-50">
        <div class="flex flex-col w-full bg-opacity-75 pl-[200px] pr-[100px] pb-[50px]">
            <div class="flex gap-5">

                <a id="showListOfItems" class="font-medium">
                    <div id="listbutton" class="w-full h-full bg-white rounded-t-lg shadow-md shadow-gray-200 px-5 py-5 cursor-pointer flex ">
                        <div class="flex flex-col">
                            <span class="text-[30px]"><?php echo allItms($conn); ?></span>
                            List of Items
                        </div>
                        <svg class="text-red-700 ml-5" xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" viewBox="0 0 24 24">
                            <g fill="none" fill-rule="evenodd">
                                <path d="M24 0v24H0V0zM12.594 23.258l-.012.002l-.071.035l-.02.004l-.014-.004l-.071-.036q-.016-.004-.024.006l-.004.01l-.017.428l.005.02l.01.013l.104.074l.015.004l.012-.004l.104-.074l.012-.016l.004-.017l-.017-.427q-.004-.016-.016-.018m.264-.113l-.014.002l-.184.093l-.01.01l-.003.011l.018.43l.005.012l.008.008l.201.092q.019.005.029-.008l.004-.014l-.034-.614q-.005-.018-.02-.022m-.715.002a.02.02 0 0 0-.027.006l-.006.014l-.034.614q.001.018.017.024l.015-.002l.201-.093l.01-.008l.003-.011l.018-.43l-.003-.012l-.01-.01z" />
                                <path fill="currentColor" d="M4 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2zm14 0H6v16h12zm-6.452 2.763a1 1 0 0 1 0 1.414L9.603 10.12a1.25 1.25 0 0 1-1.767 0l-.884-.884a1 1 0 1 1 1.414-1.414l.354.354l1.414-1.414a1 1 0 0 1 1.414 0ZM13 9a1 1 0 0 1 1-1h2a1 1 0 1 1 0 2h-2a1 1 0 0 1-1-1m-6 4.5A1.5 1.5 0 0 1 8.5 12h2a1.5 1.5 0 0 1 1.5 1.5v2a1.5 1.5 0 0 1-1.5 1.5h-2A1.5 1.5 0 0 1 7 15.5zm2 .5v1h1v-1zm4 .5a1 1 0 0 1 1-1h2a1 1 0 1 1 0 2h-2a1 1 0 0 1-1-1" />
                            </g>
                        </svg>
                    </div>
                </a>

                <a id="showFacultyInventory" class=" font-medium ">
                    <div id="invbutton" class="w-full bg-white shadow-md shadow-gray-200 px-5 py-5 cursor-pointer flex mb-5 hover:bg-gray-200">
                        <div class="flex flex-col">
                            <span class="text-[30px]"><?php echo allInv($conn); ?></span>
                            Faculty Inventory
                        </div>
                        <svg class="text-red-700 ml-5" xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M5 5h2v3h10V5h2v5h2V5c0-1.1-.9-2-2-2h-4.18C14.4 1.84 13.3 1 12 1s-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h6v-2H5zm7-2c.55 0 1 .45 1 1s-.45 1-1 1s-1-.45-1-1s.45-1 1-1" />
                            <path fill="currentColor" d="M21 11.5L15.51 17l-3.01-3l-1.5 1.5l4.51 4.5l6.99-7z" />
                        </svg>
                    </div>
                </a>
            </div>

            <?php include "listOfitems.php"; ?>
            <?php include "facultyInventory.php"; ?>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const showListOfItems = document.getElementById("showListOfItems");
            const showFacultyInventory = document.getElementById("showFacultyInventory");

            if (showListOfItems && showFacultyInventory) {
                console.log('tabs are available');

                showListOfItems.addEventListener('click', function(event) {
                    console.log('Clicked on List of Items');
                    if (document.getElementById("listOfItems").classList.contains('hidden')) {
                        document.getElementById("listOfItems").classList.remove('hidden');
                        document.getElementById("facultyInventory").classList.add('hidden');
                        document.getElementById("listbutton").classList.add('rounded-t-lg', 'h-full');
                        document.getElementById("listbutton").classList.remove('rounded-lg', 'hover:bg-gray-200');
                        document.getElementById("invbutton").classList.remove('rounded-t-lg', 'h-full', 'rounded-lg', 'hover:bg-gray-200');
                        document.getElementById("invbutton").classList.add('rounded-lg', 'hover:bg-gray-200');
                    }
                });

                showFacultyInventory.addEventListener('click', function(event) {
                    console.log('Clicked on Faculty Inventory');
                    if (document.getElementById("facultyInventory").classList.contains('hidden')) {
                        document.getElementById("listOfItems").classList.add('hidden');
                        document.getElementById("facultyInventory").classList.remove('hidden');
                        document.getElementById("invbutton").classList.add('rounded-t-lg', 'h-full');
                        document.getElementById("invbutton").classList.remove('rounded-lg', 'hover:bg-gray-200');
                        document.getElementById("listbutton").classList.remove('rounded-t-lg', 'h-full');
                        document.getElementById("listbutton").classList.add('rounded-lg', 'hover:bg-gray-200');
                    }
                });
            }
        });
    </script>

</body>

</html>