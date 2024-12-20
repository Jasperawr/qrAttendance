    <?php
        session_start();

        include "../components/loader.php";

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
        <link rel="stylesheet" href="assets/style.css">
        <script src="./assets/script.js"></script>

    </head>
    <body>

    <div id="alert-2" class="<?php if(isset($_SESSION['studentExist'])){ echo "flex";}else{echo "hidden";}?> absolute top-20 right-10 z-50 items-center p-4 mb-4 text-red-800 rounded-lg bg-red-50" role="alert">
        <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
        </svg>
        <span class="sr-only">Info</span>
        <div class="ms-3 text-sm font-medium">
            <?php if(isset($_SESSION['studentExist'])){ echo $_SESSION['studentExist'];}?>
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

        <div class="flex justify-center font-poppins h-screen w-full pt-[120px] bg-gray-50">
            <div class="bg-opacity-75 flex flex-col w-full px-[200px] gap-9">
                
                <form action="./add.php" method="POST" id="form" class="flex flex-col gap-5" onsubmit="return validateForm()" enctype="multipart/form-data" >
                    <!-- The whole card -->
                    <div class=" w-full ">

                        <div class="w-full relative gap-5 flex bg-white p-7 rounded-lg shadow-md shadow-gray-200">
                            
                            <!-- Input avater for student or user -->
                            <div id="dropZone">
                                <label for="avatar" class="tracking-wide block text-[11px] text-gray-900 uppercase font-bold">Student Picture</label>
                                <div id="avatar" class="flex items-center justify-center">
                                    <label  for="dropzone-file" class="dropArea relative overflow-hiddwn flex flex-col items-center justify-center  w-[200px] h-[200px] border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                            </svg>  
                                            <p class="dragText mb-2 text-[10px] text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                            <p class="text-[10px] text-gray-500">SVG, PNG, JPG or JPEG</p>
                                        </div>
                                        <input id="dropzone-file" type="file" class="fileInput opacity-0 w-full h-full absolute" name="studentavatar" accept=".jpg,.jpeg,.png,.png"/>
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
                                            <option value="jr" >Jr</option>
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

                                    <div  class="w-full">
                                        <label for="email" class="tracking-wide block mb-1 text-[11px] text-gray-900 uppercase font-bold">Email</label>
                                        <input type="email" class="w-full border border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block p-2.5 outline-none placeholder:tracking-wide" 
                                        name="email" id="email" placeholder="Email Address" required>
                                        <p class="opacity-0 mb-0.25 px-2 text-[11px] text-red-600 "><span class="font-medium">Oops!</span> Credential is wrong!</p>
                                    </div>

                                </div>

                                <div class="grid grid-flow-col grid-cols-4 gap-5">

                                    <div class="w-full">
                                        <label for="section" class="tracking-wide block mb-1 text-[11px] text-gray-900 uppercase font-bold">Year and Section</label>
                                        <select class="w-full p-[10.5px] px-4 border border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block  outline-none" 
                                        id="section" name="section" required>
                                            <option disabled selected>Year$Section</option>
                                            <?php
                                            $ys_query = "SELECT * From yr_sec";
                                            $ys_result = mysqli_query($conn, $ys_query);

                                            if(mysqli_num_rows($ys_result)>0){
                                                while($ys_row = mysqli_fetch_assoc($ys_result)){
                                                ?>
                                                    <option value="<?php echo $ys_row['id']; ?>"><?php echo $ys_row['year_and_sec']; ?></option>
                                                <?php
                                                }
                                            }
                                            ?>
                                        </select> 
                                        <p class="opacity-0 mb-0.25 px-2 text-[11px] text-red-600 "><span class="font-medium">Oops!</span> Credential is wrong!</p>
                                    </div>

                                    <div class="w-full">
                                        <label for="group" class="tracking-wide block mb-1 text-[11px] text-gray-900 uppercase font-bold">Group Number</label>
                                        <select class="w-full p-[10.5px] px-4 border border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block  outline-none" 
                                        id="group" name="groupnumber" required>
                                            <option disabled selected>Group</option>
                                            <?php
                                            $ys_query = "SELECT * From group_no";
                                            $ys_result = mysqli_query($conn, $ys_query);

                                            if(mysqli_num_rows($ys_result)>0){
                                                while($ys_row = mysqli_fetch_assoc($ys_result)){
                                                ?>
                                                    <option value="<?php echo $ys_row['id']; ?>" ><?php echo $ys_row['group_number']; ?></option>
                                                <?php
                                                }
                                            }
                                            ?>
                                        </select> 
                                        <p class="opacity-0 mb-0.25 px-2 text-[11px] text-red-600 "><span class="font-medium">Oops!</span> Credential is wrong!</p>
                                    </div>

                                </div>
                            </div>

                            

                            <div class="absolute right-5 bottom-5 flex items-center gap-2">
                                <button type="submit" name="addanother"
                                class="text-white bg-blue-500 hover:bg-blue-600 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none ">
                                Add Another</button>
                                <button type="submit" name="addstudent"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none ">
                                Add</button>
                            </div>
                        </div>
                    </div>
                    <!-- End of The whole card -->

                </form>
                    
                    <div class="relative w-full">
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
                                        <?php include "./studentTable.php"; ?>
                                    </tbody>
                                </table>
                            </div>

                    


            </div>
        </div>

    </body>
    </html>

