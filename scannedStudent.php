<?php
date_default_timezone_set('Asia/Manila');

include "connect.php";

$section = isset($_SESSION['section']) ? mysqli_real_escape_string($conn, $_SESSION['section']) : null;
$group_no = isset($_SESSION['groupnumber']) ? mysqli_real_escape_string($conn, $_SESSION['groupnumber']) : null;
$faculty_id = isset($_SESSION['faculty_id']) ? mysqli_real_escape_string($conn, $_SESSION['faculty_id']) : null;

function displayDefaultContent()
{
?>
    <div class="my-5 p-5 bg-gray-900 rounded-lg flex flex-col justify-between align-center items-center shadow-md shadow-gray-200 w-80">
        <img class="border-8 border-gray-50 rounded-full" src="assets/img/dummy-image.jpg" width="200px" alt="dummy">
        <div class="mt-10 rounded text-center w-full h-full flex flex-col justify-between">
            <div>
                <p class="text-[20px] text-gray-50 font-bold uppercase">Juan Dela Cruz</p>
                <p class="text-gray-100 uppercase">BSIT 4A - G1</p>
                <p class="text-gray-50 font-medium uppercase">2019-600123</p>
            </div>
            <div class="mt-5 content-none border border-gray-100"></div>
            <p class="text-gray-100 tracking-wider">May 4, 2000</p>
        </div>
    </div>
<?php
}

if (!$section || !$group_no) {
    displayDefaultContent();
    exit;
}

$query = "
    SELECT 
    a.id,
    y.year_and_sec, 
    g.group_number,
    s.*
FROM 
    attendance_log a
INNER JOIN 
    student s
ON 
    a.user_id = s.user_id 
    AND a.yr_sec = s.yr_sec 
    AND a.group_no = s.group_no
INNER JOIN 
    yr_sec y
ON 
    a.yr_sec = y.id
INNER JOIN 
    group_no g
ON 
    a.group_no = g.id
WHERE 
    a.yr_sec = '$section' AND 
    a.group_no = '$group_no' AND
    s.faculty_id = '$faculty_id' AND
    DATE(a.attendatetime) = CURDATE()
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
    <div class="my-5 p-5 bg-gray-900 rounded-lg flex flex-col justify-between items-center shadow-md shadow-gray-200 w-80">
        <img
            class="w-32 h-32 md:w-48 md:h-48 lg:w-56 lg:h-56 border-8 border-gray-50 rounded-full object-cover"
            src="data:image/jpeg;base64,<?php echo $avatar_base64; ?>"
            alt="dummy">
        <div class="mt-10 rounded text-center w-full flex flex-col justify-between">
            <div>
                <p class="text-[20px] text-gray-50 font-bold uppercase"><?php echo $row['name']; ?></p>
                <p class="text-gray-100 uppercase">BSIT <?php echo $row['year_and_sec'], " - ", $row['group_number']; ?></p>
                <p class="text-gray-50 font-medium uppercase"><?php echo $row['student_number']; ?></p>
            </div>
            <div class="mt-5 border-t border-gray-100"></div>
            <p class="text-gray-100 tracking-wider"><?php echo $row['datetime']; ?></p>
        </div>
    </div>

<?php
} else {
    displayDefaultContent();
}
?>