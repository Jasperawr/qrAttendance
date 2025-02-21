<?php
// session_start();

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
    <script src="./src/scanner.js"></script>
    <script src="./assets/script.js"></script>
</head>

<body>

    <?php include "components/topbar.php"; ?>
    <?php include "./components/modals/confirmDeleteItem.php"; ?>


    <?php

    // Initialize variables
    $facultyName = '';
    $facultyEmail = '';
    $userid = '';

    if (isset($_SESSION['faculty_id'])) {
        $facultyId = htmlspecialchars($_SESSION['faculty_id'], ENT_QUOTES, 'UTF-8'); // Sanitize session data

        // Query to fetch faculty name and email
        $query = "SELECT name, email, userid FROM user_acount WHERE id = '$facultyId' LIMIT 1";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            // Fetch data
            $row = mysqli_fetch_assoc($result);
            $facultyName = $row['name'];
            $facultyEmail = $row['email'];
            $userid = $row['userid'];
        }
    }
    ?>

    <div id="update<?php echo $facultyId; ?>" class="flex justify-center items-center w-full h-full pt-[100px]">
        <div class="w-[50em] h-[50em] bg-gray-50 rounded-lg">
            <div class="flex justify-between w-full items-center">
                <p class="p-5 text-lg font-semibold text-left rtl:text-right text-gray-900">
                    Your Information
                </p>
            </div>

            <form action="./update.php" method="POST" id="form" class="flex flex-col gap-5" onsubmit="return validateForm()" enctype="multipart/form-data">
                <input type="hidden" name="user_id" id="user_id" value="<?php echo $userid; ?>">

                <div class="p-5 px-20">
                    <div id="allinputs<?php echo $facultyId; ?>" class="w-full">
                        <!-- Name Input -->
                        <label for="name" class="tracking-wide block mb-1 text-[11px] text-gray-900 uppercase font-bold">Faculty Name</label>
                        <div id="name<?php echo $facultyId; ?>" class="w-full">
                            <input
                                type="text"
                                value="<?php echo htmlspecialchars($facultyName, ENT_QUOTES, 'UTF-8'); ?>"
                                class="w-full border border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block p-2.5 outline-0 placeholder:tracking-wide"
                                name="name"
                                id="name"
                                placeholder="Faculty Name"
                                required>
                            <p class="opacity-0 mb-0.25 px-2 text-[11px] text-red-600">
                                <span class="font-medium">Oops!</span> Credential is wrong!
                            </p>
                        </div>

                        <!-- Email Input -->
                        <div class="w-full">
                            <label for="email<?php echo $facultyId; ?>" class="tracking-wide block mb-1 text-[11px] text-gray-900 uppercase font-bold">Email</label>
                            <input
                                type="email"
                                value="<?php echo htmlspecialchars($facultyEmail, ENT_QUOTES, 'UTF-8'); ?>"
                                class="w-full border border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block p-2.5 outline-none placeholder:tracking-wide"
                                name="email"
                                id="email<?php echo $facultyId; ?>"
                                placeholder="Email Address"
                                required>
                            <p class="opacity-0 mb-0.25 px-2 text-[11px] text-red-600">
                                <span class="font-medium">Oops!</span> Credential is wrong!
                            </p>
                        </div>
                    </div>

                    <!-- Save Button -->
                    <div class="flex items-center gap-2">
                        <button
                            type="submit"
                            name="updateUser"
                            class="text-white bg-blue-500 hover:bg-blue-600 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none">
                            Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>


</body>

</html>