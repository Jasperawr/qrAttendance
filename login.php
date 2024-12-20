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
</head>
<body>
    <div class="flex justify-around h-screen w-screen bg-[url('assets/img/bg2.jpg')] bg-cover bg-center font-poppins">
        <div class="flex justify-center items-center h-full w-full bg-red-950 bg-opacity-35">    
            <div class=" flex flex-col items-center border rounded shadow pb-10 pt-[50px] px-5 bg-white">
                <div class="relative flex flex-col justify-center items-center">
                    <img class="absolute bottom-[60px] p-2 bg-white rounded-full" src="assets/img/bulsuhag.png" width="120px" >
                    <p class="text-[14px] uppercase  text-gray-800">bulacan state university</p>
                    <p class="text-[16px] font-bold mb-5 uppercase text-gray-900">Attendance Management System</p>
                </div>

                <form action="verify.php" method="POST" class="w-full" >
                    <label for="email" class="tracking-wide block mb-1 text-[11px] text-gray-900 uppercase font-bold">Email</label>
                    <input type="email" class="w-full border border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block p-2.5 outline-0 placeholder:tracking-wide" name="email" id="email" placeholder="Enter your Email Address">
                    <p class="opacity-0 mb-0.25 px-2 text-[11px] text-red-600 "><span class="font-medium">Oops!</span> Credential is wrong!</p>

                    <label for="password" class="tracking-wide block mb-1 text-[11px] text-gray-900 uppercase font-bold">Password</label>
                    <div class="flex justify-end relative">
                        <input type="password" class="border border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 outline-0 placeholder:tracking-wide" name="password" id="password" placeholder="Enter your Password">
                        <!-- show password -->
                        <svg onclick="showPassword()" id="eye_1" class=" absolute opacity-30 top-2.5 right-2 cursor-pointer" xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" viewBox="0 0 24 24"><path fill="currentColor" d="M2 5.27L3.28 4L20 20.72L18.73 22l-3.08-3.08c-1.15.38-2.37.58-3.65.58c-5 0-9.27-3.11-11-7.5c.69-1.76 1.79-3.31 3.19-4.54zM12 9a3 3 0 0 1 3 3a3 3 0 0 1-.17 1L11 9.17A3 3 0 0 1 12 9m0-4.5c5 0 9.27 3.11 11 7.5a11.8 11.8 0 0 1-4 5.19l-1.42-1.43A9.86 9.86 0 0 0 20.82 12A9.82 9.82 0 0 0 12 6.5c-1.09 0-2.16.18-3.16.5L7.3 5.47c1.44-.62 3.03-.97 4.7-.97M3.18 12A9.82 9.82 0 0 0 12 17.5c.69 0 1.37-.07 2-.21L11.72 15A3.064 3.064 0 0 1 9 12.28L5.6 8.87c-.99.85-1.82 1.91-2.42 3.13"/></svg>
                        <svg onclick="showPassword()" id="eye_2" style="display:none;" class=" absolute opacity-30 top-2.5 right-2 cursor-pointer" xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" viewBox="0 0 24 24"><path fill="currentColor" d="M12 9a3 3 0 0 1 3 3a3 3 0 0 1-3 3a3 3 0 0 1-3-3a3 3 0 0 1 3-3m0-4.5c5 0 9.27 3.11 11 7.5c-1.73 4.39-6 7.5-11 7.5S2.73 16.39 1 12c1.73-4.39 6-7.5 11-7.5M3.18 12a9.821 9.821 0 0 0 17.64 0a9.821 9.821 0 0 0-17.64 0"/></svg>
                    </div>

                    <p class="opacity-0 mb-0.25 px-2 text-[11px] text-red-600 "><span class="font-medium">Oops!</span> Credential is wrong!</p>

                    <button name="login" type="submit" class="mt-2 w-full text-white bg-green-700 hover:bg-green-900 focus:ring-4 focus:ring-blue-300 font-medium rounded text-sm px-5 py-2 me-2 focus:outline-none">Login</button>
                    <p class=" px-2 text-[12px] text-gray-600 ">Did you forgot your password? <a href="forgotPassword.php" class="font-medium text-black">Reset Password</a></p>

                </form>

            </div>
        </div>
    </div>

    <script>
        function showPassword(){ // For show password
            var pwdInput = document.getElementById('password');
            var close = document.getElementById('eye_1');
            var open = document.getElementById('eye_2');

            if(pwdInput.type === "password"){
                close.style.display = "none";
                open.style.display = "block";
                pwdInput.type = "text";
                console.log(pwdInput.type);
                console.log(close.style.display);

            }else{
                open.style.display = "none";
                close.style.display = "block";
                pwdInput.type = "password";
                console.log(pwdInput.type);
                console.log(close.style.display);
            }

        }
    </script>
</body>
</html>