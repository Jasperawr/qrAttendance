<?php


include "connect.php";

$query = "SELECT * from user_acount ORDER BY id DESC";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $userid = $row['userid'];
        $name = $row['name'];
        $imagePath = "https://ui-avatars.com/api/?name=" . $name . "&background=random";

        // $qavatar = "SELECT * from avatar where user_id = '$userid' limit 1";
        // $qavatar_result = mysqli_query($conn, $qavatar);

        // if ($qavatar_result) {
        //     $qavatar_row = mysqli_fetch_assoc($qavatar_result);
        //     if (!empty($qavatar_row['path']) && !empty($qavatar_row['path'])) {
        //         $imagePath =  $qavatar_row['path'] . $qavatar_row['name'];
        //     } else {
        //         $imagePath = "https://ui-avatars.com/api/?name=" . $name . "&background=random";
        //     }
        // } else {
        // }
?>

        <tr class="bg-white border-b ">
            <td scope="row" class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap">
                <img class="w-10 h-10 rounded-full" src="<?php echo $imagePath; ?>" alt="Jese image">
                <div class="ps-3">
                    <div class="text-base font-semibold"><?php echo ucwords($row['name']) ?></div>
                    <div class="font-normal text-gray-500"><?php echo $row['email']; ?></div>
                </div>
            </td>
            <td class="px-6 py-4 text-nowrap dateColumn">
                <?php echo $row['datetime']; ?>
            </td>
            <td class="text-center justify-center items-center flex-nowrap flex">
                <button id="<?php echo $row['id']; ?>" onclick="updateThis(this.id)" data-modal-target="crud-modal" data-modal-toggle="crud-modal" type="button" class="text-green-700 hover:text-white border border-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg p-2 text-center me-2 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" viewBox="0 0 24 24">
                        <path fill="currentColor" d="m12.9 6.855l4.242 4.242l-9.9 9.9H3v-4.243zm1.414-1.415l2.121-2.121a1 1 0 0 1 1.414 0l2.829 2.828a1 1 0 0 1 0 1.415l-2.122 2.121z" />
                    </svg>
                </button>
                <button id="<?php echo $row['id']; ?>" onclick="deleteThis(this.id)" data-modal-target="popup-modal" data-modal-toggle="popup-modal" type="button" class="text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg  p-2 text-center me-2 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M7 21q-.825 0-1.412-.587T5 19V6H4V4h5V3h6v1h5v2h-1v13q0 .825-.587 1.413T17 21zm2-4h2V8H9zm4 0h2V8h-2z" />
                    </svg>
                </button>
            </td>

        </tr>


    <?php
    }
} else {
    // echo "Error executing query: " . mysqli_error($conn);

    ?>

    <tr class="w-full">
        <td colSpan="7" class=" w-full text-center bg-white py-4">
            No Student Available.
        </td>
    </tr>


<?php

}

?>