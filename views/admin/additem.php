<?php
        session_start();

        if(!isset($_SESSION['loggedin']) && !$_SESSION['loggedin'] == "true")
        {
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

    </head>
    <body>

    <?php include "./components/modals/confirmDeleteItem.php";?>
    <?php include "./components/modals/updateItemModal.php";?>
    <?php include "./components/modals/zoomImage.php";?>


    <div id="alert-2" class="<?php if(isset($_SESSION['userExist'])){ echo "flex";}else{echo "hidden";}?> absolute top-20 right-10 z-50 items-center p-4 mb-4 text-red-800 rounded-lg bg-red-50" role="alert">
        <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
        </svg>

        <span class="sr-only">Info</span>

        <div class="ms-3 text-sm font-medium">
            <?php if(isset($_SESSION['userExist'])){ echo $_SESSION['userExist'];}?>
        </div>

        <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex items-center justify-center h-8 w-8" 
        data-dismiss-target="#alert-2" aria-label="Close">
            <span class="sr-only">Close</span>
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
        </button>
    </div>

    

        <?php include "components/topbar.php"; ?>

        <div class="flex h-screen font-poppins pt-[100px] bg-auto">
            <div class="flex h-full w-full bg-opacity-75 pr-[5%]">

                <div class="mr-[30px] w-full pl-[10%] flex gap-5">   

                    <!-- The whole card --> 
                    <div class="w-[500px]">

                        <form action="./add.php" method="POST" onsubmit="return validateForm()"  id="form" enctype="multipart/form-data" >
                            <div class="w-full relative gap-5 flex flex-col bg-teal-800 p-7 pb-[80px] rounded-lg shadow-md shadow-green-200">
                                
                                <p class=" text-lg font-semibold text-left text-white rtl:text-right text-gray-900  border-b-[1px]">
                                Add New Item</span>
                                </p>

                                <div id="allinputs" class="w-full">

                                    <div id="dropZone" class="mb-2">
                                        <label for="avatar" class="tracking-wide block text-[11px] text-gray-900 uppercase font-bold text-white">Item Picture</label>
                                        <div id="avatar" class="flex items-center justify-center">
                                            <label  for="dropzone-file" class="dropArea relative overflow-hiddwn flex flex-col items-center justify-center  w-[200px] h-[200px] border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50">
                                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                    <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                                    </svg>  
                                                    <p class="dragText mb-2 text-[10px] text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                                    <p class="text-[10px] text-gray-500">SVG, PNG, JPG or JPEG</p>
                                                </div>
                                                <input id="dropzone-file" type="file" class="fileInput opacity-0 w-full h-full absolute" name="itempic" accept=".jpg,.jpeg,.png,.png"/>
                                            </label>
                                            <div id="preview" class="previewImage">
                                                
                                            </div>
                                        </div> 
                                    </div>

                                    <label for="name" class="text-white tracking-wide block mb-1 text-[11px] text-gray-900 uppercase font-bold">Item Name</label>
                                    <div id="name" class="w-full">
                                        <input type="text" class="w-full border border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block p-2.5 outline-0 placeholder:tracking-wide" 
                                        name="name" id="item" placeholder="Enter Item Name" required>
                                        <p class="opacity-0 mb-0.25 px-2 text-[11px] text-red-600 "><span class="font-medium">Oops!</span> Credential is wrong!</p>
                                    </div>

                                </div>
                                

                                <div class="absolute right-7 bottom-8 flex items-center gap-2 ">
                                    <button type="submit" name="additem" class="button-click text-teal-900 font-medium flex justify-evenly items-center w-[100px] ">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M20 14h-6v6h-4v-6H4v-4h6V4h4v6h6z"/></svg>
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
                                <input type="search" id="search-dropdown" class="block pl-9 p-2.5 w-full  text-sm text-gray-900 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Search..." required />
                                    <svg class="w-5 h-5 absolute opacity-40 left-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                    </svg>
                            </div>

                            <div class="relative max-w-sm w-[170px] ">
                                <input type="date" class=" border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                            </div>
                        </div>

                        <div class="relative overflow-x-auto shadow-md shadow-green-200 rounded-lg w-full">

                            <div id="alert-3" class="<?php if(isset($_SESSION['notifChange'])){ echo "flex";}else{echo "hidden";}?> absolute top-20 right-10 z-50 items-center p-4 mb-4 text-green-800 rounded-lg bg-green-50" role="alert">
                                <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                                </svg>

                                <div class="ms-3 text-sm font-medium">
                                    <?php if(isset($_SESSION['notifChange'])){ echo $_SESSION['notifChange'];}?>
                                </div>

                                <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 inline-flex items-center justify-center h-8 w-8" 
                                data-dismiss-target="#alert-3" aria-label="Close">
                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                    </svg>
                                </button>
                            </div>

                            <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                                <caption class="p-5 text-lg font-semibold text-left rtl:text-right text-gray-900 bg-white ">
                                    Item</span>
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
                                            Date
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            QR Code
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                    <tbody>
                                        <?php include "./itemTable.php"; ?>
                                    </tbody>
                            </table>
                        </div>
                    </div>

                    
                    
                </div>     

            </div>
        </div>

    </body>
    </html>