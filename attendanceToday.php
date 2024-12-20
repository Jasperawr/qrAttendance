<?php

include "connect.php";

$section = $_SESSION['section'];
$group_no = $_SESSION['groupnumber'];
$faculty_id = isset($_SESSION['faculty_id']) ? $_SESSION['faculty_id'] : null;

if (!isset($_SESSION['section']) && !isset($_SESSION['groupnumber'])) {
?>

    <tr class="w-full">
        <td colSpan="6" class=" w-full text-center bg-white py-4">
            No Data Available.
        </td>
    </tr>

    <?php

}

$query = "
    SELECT 
        a.*, 
        s.name, 
        s.email, 
        s.student_number, 
        s.avatar
    FROM 
        attendance_log a
    INNER JOIN 
        student s
    ON 
        a.user_id = s.user_id
    WHERE 
        a.yr_sec = '$section' AND 
        a.group_no = '$group_no' AND
        s.faculty_id = '$faculty_id' AND
        DATE(a.attendatetime) = CURDATE()
";

$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $user_id = $row['user_id'];
        $status = $row['status']; // status is from the attendance_log
        $attendatetime = $row['attendatetime'];

        $yearAndSec = "";
        $y_q = "SELECT * from yr_sec LEFT JOIN attendance_log ON yr_sec.id = attendance_log.yr_sec where attendance_log.yr_sec = $section LIMIT 1";
        $y_r = mysqli_query($conn, $y_q);
        if (mysqli_num_rows($y_r) > 0) {
            $y_row = mysqli_fetch_assoc($y_r);
            $yearAndSec = $y_row['year_and_sec'];
        }

        $groupNo = "";
        $gn_q = "SELECT * from group_no LEFT JOIN attendance_log ON group_no.id = attendance_log.group_no where attendance_log.group_no = $group_no LIMIT 1";
        $gn_r = mysqli_query($conn, $gn_q);
        if (mysqli_num_rows($gn_r) > 0) {
            $gn_row = mysqli_fetch_assoc($gn_r);
            $groupNo = $gn_row['group_number'];
        }
    ?>

        <tr class="bg-white border-b ">
            <th scope="row" class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap">
                <img class="w-10 h-10 rounded-full" src="data:image/jpeg;base64,<?php echo base64_encode($row['avatar']); ?>" alt="Student Avatar">
                <div class="ps-3">
                    <div class="text-base font-semibold"><?php echo ucwords($row['name']); ?></div>
                    <div class="font-normal text-gray-500"><?php echo $row['email']; ?></div>
                </div>
            </th>
            <td class="px-6 py-4 text-xs">
                <?php echo $row['student_number']; ?>
            </td>
            <td class="px-6 py-4 text-center">
                <?php echo strtoupper($yearAndSec); ?>
            </td>
            <td class="px-6 py-4 text-center">
                <?php echo strtoupper($groupNo); ?>
            </td>
            <td class="px-6 py-4 font-bold <?php echo $status == 'present' ? 'text-green-400' : ($status == 'late' ? 'text-yellow-400' : 'text-red-400'); ?>">
                <?php echo strtoupper($status); ?>
            </td>
            <td class="px-6 py-4 text-wrap text-xs">
                <?php echo $row['attendatetime']; ?>
            </td>
        </tr>

    <?php
    }
} else {
    ?>

    <tr class="w-full">
        <td colSpan="6" class=" w-full text-center bg-white py-4">
            No Data Available.
        </td>
    </tr>

<?php

}

?>