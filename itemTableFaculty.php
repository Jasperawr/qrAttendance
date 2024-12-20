<?php

include "connect.php";

if (isset($_SESSION['notifChange'])) {
    unset($_SESSION['notifChange']);
}

$faculty_id = $_SESSION['faculty_id'];

$query = "
    SELECT 
        l.id AS log_id,  -- Alias for faculty_inventory_logs ID
        l.faculty_id, 
        l.quantity, 
        l.datetime AS log_datetime, 
        i.item_id, 
        i.item_name, 
        i.datetime AS item_datetime
    FROM 
        faculty_inventory_logs l
    INNER JOIN 
        items i 
    ON 
        l.item_id = i.item_id
    WHERE 
        l.faculty_id = '$faculty_id'
    ORDER BY 
        l.id DESC;
";
$result = mysqli_query($conn, $query);

$i = 0;
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $i += 1;
?>

        <tr class="bg-white border-b hover:bg-green-100 cursor-pointer">

            <td class="px-6 py-4 font-semibold">
                <?php echo $i; ?>
            </td>
            <td scope="row" class="text-gray-900 whitespace-nowrap px-6 py-4" onclick="showItemImage('<?php echo $imageItem ?? 0; ?>')">
                <div class="text-base font-semibold"><?php echo ucwords($row['item_name']) ?></div>
            </td>
            <td class="px-6 py-4 text-nowrap">
                <div class="relative flex items-center w-20 z-20">
                    <input type="number" value="<?php echo $row['quantity']; ?>" id="<?php echo $row['log_id']; ?>" onblur="updateQuantity('<?php echo $row['log_id']; ?>')" class="quan rounded outline-none border-gray-300 h-11 text-center text-gray-900 text-sm focus:ring-blue-500 focus:border-blue-500 block w-full py-2.5 " placeholder="0" />
                </div>
            </td>
            <td class="px-6 py-4 ">
                <img class="cursor-pointer " src="<?php echo $row['qr']; ?>" width="50" alt="QR Code" onclick="zoomImage('<?php echo $row['qr']; ?>')" data-modal-target="zoom-modal" data-modal-toggle="zoom-modal">
            </td>
            <td class="px-6 py-4 text-nowrap">
                <?php echo $row['log_datetime']; ?>
            </td>


        </tr>


    <?php
    }
} else {
    // echo "Error executing query: " . mysqli_error($conn);

    ?>

    <tr class="w-full">
        <td colSpan="7" class=" w-full text-center bg-white py-4">
            No Data Available.
        </td>
    </tr>


<?php

}



?>

<script>
    // var element = document.querySelector(".quan");
    // element.addEventListener("blur", function() {
    //     const id = element.id;
    //     console.log(id);
    //     // window.location = 'login.php?id='.id;
    // }); 

    function updateQuantity(id) {
        let quantity = document.getElementById(id);
        if (quantity.value != 0) {
            window.location.href = 'update.php?unique_id=' + id + '&quantity=' + quantity.value;
        }
    }
</script>