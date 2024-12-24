<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Forgot Password</title>

    <link rel="icon" href="assets/img/bulsuhag.png" type="image/x-icon">
    <link rel="shortcut icon" href="assets/img/bulsuhag.png" type="image/x-icon">

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <div class="flex justify-around h-screen w-screen bg-[url('assets/img/maingate.png')] bg-cover bg-center font-poppins">
        <div class="flex justify-center items-center h-full w-full bg-green-800 bg-opacity-75">
            <div class=" flex flex-col items-center border rounded shadow py-10  px-5 bg-white">

                <div id="alert-3"
                    class="<?php
                            if (isset($_SESSION['notifMessage'])) {
                                list($type, $message) = explode('|', $_SESSION['notifMessage'], 2);
                                echo $type === 'error' ? 'flex bg-red-50 text-red-800' : 'flex bg-green-50 text-green-800';
                            } else {
                                echo 'hidden';
                            }
                            ?> absolute top-20 right-10 z-50 items-center p-4 mb-4 rounded-lg"
                    role="alert">

                    <!-- Icon -->
                    <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="<?php
                                    echo isset($type) && $type === 'error'
                                        ? 'M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z'
                                        : 'M10 1a9 9 0 1 1-9 9A9.01 9.01 0 0 1 10 1ZM9 6h2v6H9zm1 8a1 1 0 1 1-1-1 1 1 0 0 1-1 1z';
                                    ?>" />
                    </svg>

                    <!-- Message -->
                    <div class="ms-3 text-sm font-medium">
                        <?php
                        if (isset($message)) {
                            echo $message;
                        }
                        ?>
                    </div>

                    <!-- Close Button -->
                    <button type="button"
                        class="ms-auto -mx-1.5 -my-1.5 rounded-lg focus:ring-2 p-1.5 inline-flex items-center justify-center h-8 w-8 <?php
                                                                                                                                        echo isset($type) && $type === 'error'
                                                                                                                                            ? 'bg-red-50 text-red-500 focus:ring-red-400'
                                                                                                                                            : 'bg-green-50 text-green-500 focus:ring-green-400';
                                                                                                                                        ?>"
                        data-dismiss-target="#alert-3" aria-label="Close">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                    </button>
                </div>

                <p class="text-[16px] font-bold mb-2 uppercase text-gray-900">Forgot your password?</p>
                <p class="text-[12px] mb-5  text-gray-500">Enter your email and we'll send you link to reset your password.</p>


                <form action="forgot_password.php" method="POST" class="w-full">

                    <svg class="absolute text-gray-600 mt-2 ml-2" xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" viewBox="0 0 24 24">
                        <path fill="currentColor" d="m18.73 5.41l-1.28 1L12 10.46L6.55 6.37l-1.28-1A2 2 0 0 0 2 7.05v11.59A1.36 1.36 0 0 0 3.36 20h3.19v-7.72L12 16.37l5.45-4.09V20h3.19A1.36 1.36 0 0 0 22 18.64V7.05a2 2 0 0 0-3.27-1.64" />
                    </svg>
                    <input type="email" class="pl-10 w-full border border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block p-2.5 outline-0 placeholder:tracking-wide" name="email" id="email" placeholder="example@gmail.com">
                    <p class="opacity-0 mb-0.25 px-2 text-[11px] text-red-600 "><span class="font-medium">Oops!</span> Credential is wrong!</p>

                    <button type="submit" class="mt-2 w-full text-white bg-green-700 hover:bg-green-900 focus:ring-4 focus:ring-blue-300 font-medium rounded text-sm px-5 py-1 me-2 focus:outline-none">Send</button>
                    <p class="mt-3 px-2 text-[12px] text-gray-600 "><a href="login.php" class="font-medium text-gray-500 hover:text-black">
                            < Back</a>
                    </p>

                </form>

            </div>
        </div>
    </div>
</body>

</html>