<?php
include "connect.php";

// Retrieve the faculty ID from the session
$faculty_id = isset($_SESSION['faculty_id']) ? $_SESSION['faculty_id'] : null;

// Set the current date
$currdate = date('Y-m-d');

// Query to select all students and their attendance status
$query = "
    SELECT 
        s.user_id,
        s.name,
        s.email,
        s.student_number,
        s.avatar,
        s.class,
        s.semester,
        a.status,
        a.room,
        a.attendatetime
    FROM 
        attendance_log a 
    LEFT JOIN 
        student s
    ON 
        s.user_id = a.user_id
    WHERE 
        s.faculty_id = '$faculty_id'
";

$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $user_id = $row['user_id'];
        $status = $row['status']; // If NULL, defaults to 'absent'
        $attendatetime = $row['attendatetime'];
?>

        <tr class="bg-white border-b ">
            <td scope="row" class="px-2 py-2 text-gray-900 whitespace-nowrap">
                <div class="flex items-center overflow-x-auto scrollbar-hide">
                    <img class="w-10 h-10 rounded-full flex-shrink-0" src="<?php echo  $row['avatar'] ? "data:image/jpeg;base64, " . base64_encode($row['avatar']) : "https://ui-avatars.com/api/?name=" . $row['name'] . "&background=random"; ?>" alt="Jese image">
                    <div class="ps-3 flex-shrink-0">
                        <div class="text-base font-semibold text-xs"><?php echo ucwords($row['name']); ?></div>
                        <div class="font-normal text-gray-500 text-xs"><?php echo $row['email']; ?></div>
                    </div>
                </div>
            </td>
            <td class="px-3 py-2 text-xs">
                <?php echo $row['student_number']; ?>
            </td>
            <td class="classAttendance px-3 py-2 text-xs">
                <?php echo  $row['class']; ?>
            </td>
            <td class="px-3 py-2 text-xs">
                <?php echo $row['semester']; ?>
            </td>
            <td class="statusAttendance px-3 py-2 text-xs font-bold <?php echo $status == 'Present' ? 'text-green-700' : 'text-red-600'; ?>">
                <?php echo strtoupper($status); ?>
            </td>
            <td class="px-3 py-2 text-xs">
                <?php echo $row['room'] ?? 'N/A'; ?>
            </td>
            <td class="dateAttendance px-3 py-2 text-wrap text-xs">
                <?php echo $attendatetime ?? 'N/A'; ?>
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