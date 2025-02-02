<?php

include "connect.php";

// echo print_r($_SESSION);

$class = isset($_SESSION['class']) ? $_SESSION['class'] : null;
$program = isset($_SESSION['program']) ? $_SESSION['program'] : null;
$semester = isset($_SESSION['semester']) ? $_SESSION['semester'] : null;
// $year_level = isset($_SESSION['year_level']) ? $_SESSION['year_level'] : null;
$academic_year = isset($_SESSION['academic_year']) ? $_SESSION['academic_year'] : null;
$room = isset($_SESSION['room']) ? $_SESSION['room'] : null;
$faculty_id = isset($_SESSION['faculty_id']) ? $_SESSION['faculty_id'] : null;

// echo print_r($_SESSION);

// If section and group number are provided, fetch the data
$query = "
    SELECT 
        student.*, 
        attendance_log.status AS attendance_status
    FROM 
        student
    LEFT JOIN 
        attendance_log ON student.user_id = attendance_log.user_id 
        AND DATE(attendance_log.attendatetime) = CURDATE() 
        AND (attendance_log.status = 'Present' OR attendance_log.status = 'Absent')
        AND attendance_log.room = '$room'
        AND attendance_log.faculty_id = '$faculty_id' 
        AND attendance_log.class = '$class' 
        AND attendance_log.semester = '$semester' 
        AND attendance_log.program = '$program' 
        AND attendance_log.academic_year = '$academic_year'
    WHERE 
        student.faculty_id = '$faculty_id' 
        AND student.class = '$class' 
        AND student.semester = '$semester' 
        AND student.program = '$program' 
        AND student.academic_year = '$academic_year'
        OR attendance_log.room = '$room'
";

$result = mysqli_query($conn, $query);

if ($result) {

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $user_id = $row['user_id'];

?>

            <tr class="bg-white border-b">
                <td class="text-center">
                    <input
                        type="checkbox"
                        id="checkabsent<?php echo $row['id']; ?>"
                        name="mark_ids[]"
                        value="<?php echo $row['user_id']; ?>"
                        class="checkabsent w-4 h-4 text-red-500 bg-gray-100 border-gray-300 rounded focus:ring-red-400 focus:ring-2" />
                </td>
                <td scope="row" class="px-2 py-2 text-gray-900 whitespace-nowrap">
                    <div class="flex items-center overflow-x-auto scrollbar-hide">
                        <img class="w-10 h-10 rounded-full flex-shrink-0" src="<?php echo  $row['avatar'] ? "data:image/jpeg;base64, " . base64_encode($row['avatar']) : "https://ui-avatars.com/api/?name=" . $row['name'] . "&background=random"; ?>" alt="Jese image">
                        <div class="ps-3 flex-shrink-0">
                            <div class="text-base font-semibold text-xs"><?php echo ucwords($row['name']); ?></div>
                            <div class="font-normal text-gray-500 text-xs"><?php echo $row['email']; ?></div>
                        </div>
                    </div>
                </td>

                <td class="px-2 py-2 text-xs">
                    <?php echo $row['student_number']; ?>
                </td>
                <td class="px-2 py-2 text-center text-xs">
                    <?php echo $row['class']; ?>
                </td>
                <td class="px-2 py-2 text-center text-xs">
                    <?php echo ucwords($row['semester']); ?>
                </td>
                <td class="px-2 py-2 font-bold text-xs <?php echo $row['attendance_status'] === 'Present' ? 'text-green-700' : ($row['attendance_status'] === 'Absent' ? 'text-red-500' : 'text-yellow-500'); ?>">
                    <?php echo $row['attendance_status'] ? strtoupper($row['attendance_status']) : 'Pending'; ?>
                </td>
                <td class="px-2 py-2 text-center text-xs">
                    <?php echo $row['modified_attendatetime'] ? $row['modified_attendatetime'] : $row['attendatetime']; ?>
                </td>

            </tr>

        <?php
        }
    } else {
        ?>
        <tr class="w-full">
            <td colSpan="6" class="w-full text-center bg-white py-4">
                No Data Available.
            </td>
        </tr>
    <?php
    }
} else {
    ?>
    <tr class="w-full">
        <td colSpan="6" class="w-full text-center bg-white py-4">
            No Data Available.
        </td>
    </tr>
<?php
}
?>