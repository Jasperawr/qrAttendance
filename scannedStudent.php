<?php
date_default_timezone_set('Asia/Manila');

include "connect.php";

$faculty_id = isset($_SESSION['faculty_id']) ? mysqli_real_escape_string($conn, $_SESSION['faculty_id']) : null;

function displayDefaultContent()
{
?>
    <div class="my-5 p-5 bg-white rounded-lg flex flex-col justify-between align-center items-center shadow-md shadow-gray-200 w-80">
        <img class="border-2 border-gray-900 rounded-full" src="assets/img/dummy-image.jpg" width="200px" alt="dummy">
        <div class="mt-10 rounded text-center w-full h-full flex flex-col justify-between">
            <div>
                <p class="text-[20px] text-gray-500 font-bold uppercase">Juan Dela Cruz</p>
                <p class="text-gray-500 uppercase">XXXX XX - XX</p>
                <p class="text-gray-500 font-medium uppercase">2019-XXXXXX</p>
            </div>
            <div class="mt-5 content-none border border-gray-100"></div>
            <p class="text-gray-500 tracking-wider text-xs">Jan 1, 2000</p>
        </div>
    </div>


<?php
}

// if (!$section || !$group_no) {
//     displayDefaultContent();
//     exit;
// }

$query = "
    SELECT 
    a.id,
    s.*
FROM 
    attendance_log a
INNER JOIN 
    student s
ON 
    a.user_id = s.user_id 
WHERE 
    s.faculty_id = '$faculty_id'
    AND a.status = 'Present'
ORDER BY 
    a.attendatetime DESC
LIMIT 1
";


$result = mysqli_query($conn, $query);

if ($result === false) {
    echo "Error executing query: " . mysqli_error($conn);
    exit;
}

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);

    $avatar_base64 = base64_encode($row['avatar']);
?>
    <div class="p-5 bg-white rounded-lg flex flex-col justify-between items-center shadow-md shadow-gray-400 w-80">
        <?php
        if ($row['avatar']) {
        ?>
            <img
                class="w-32 h-32 md:w-48 md:h-48 lg:w-56 lg:h-56 border-2 border-gray-900 rounded-full object-cover"
                src="data:image/jpeg;base64,<?php echo $avatar_base64; ?>"
                alt="dummy">
        <?php
        } else {
        ?>
            <img class="border-2 border-gray-900 rounded-full" src="assets/img/dummy-image.jpg" width="200px" alt="dummy">
        <?php
        }
        ?>

        <div class="mt-10 rounded text-center w-full flex flex-col justify-between">
            <div>
                <p class="text-[20px] text-gray-900 font-bold uppercase"><?php echo $row['name']; ?></p>
                <p class="text-gray-900 uppercase text-sm"><?php echo $row['class']; ?></p>
                <p class="text-gray-900 font-medium uppercase"><?php echo $row['student_number']; ?></p>
            </div>
            <div class="mt-5 border-t border-gray-500"></div>
            <p class="text-gray-500 tracking-wider text-xs"><?php echo (new DateTime($row['datetime']))->format('M j, Y \a\t h:i a'); ?></p>
        </div>
    </div>



<?php
} else {
    displayDefaultContent();
}
?>