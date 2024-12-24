<?php

include "connect.php";

if (isset($_SESSION['notifChange'])) {
    unset($_SESSION['notifChange']);
}

$query = "SELECT * from items ORDER BY id DESC";
$result = mysqli_query($conn, $query);

$i = 0;
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $i += 1;
        $itemid = $row['item_id'];
        $name = $row['item_name'];

?>

        <tr class="bg-white border-b ">

            <td class="px-6 py-4 font-semibold">
                <?php echo $i; ?>
            </td>
            <td scope="row" class="text-gray-900 whitespace-nowrap px-6 py-4">
                <div class="text-base font-semibold"><?php echo ucwords($row['item_name']) ?></div>
            </td>
            <td class="px-6 py-4 text-nowrap">
                <?php echo $row['datetime']; ?>
            </td>
            <td class="px-6 py-4 hover:bg-gray-100">
                <img class="cursor-pointer " src="<?php echo $row['qr']; ?>" width="50" alt="QR Code" onclick="zoomImage('<?php echo $row['qr']; ?>')" data-modal-target="zoom-modal" data-modal-toggle="zoom-modal">
            </td>
            <td class="flex-nowrap flex pt-6">
                <button id="<?php echo $row['id']; ?>" onclick="toggleUpdateModal(<?php echo $row['id']; ?>)" type="button" class="text-green-700 hover:text-white border border-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg p-2 text-center me-2 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" viewBox="0 0 24 24">
                        <path fill="currentColor" d="m12.9 6.855l4.242 4.242l-9.9 9.9H3v-4.243zm1.414-1.415l2.121-2.121a1 1 0 0 1 1.414 0l2.829 2.828a1 1 0 0 1 0 1.415l-2.122 2.121z" />
                    </svg>
                </button>
                <button id="<?php echo $row['id']; ?>" onclick="deleteThisItem(this.id)" data-modal-target="popup-modal" data-modal-toggle="popup-modal" type="button" class="text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg  p-2 text-center me-2 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M7 21q-.825 0-1.412-.587T5 19V6H4V4h5V3h6v1h5v2h-1v13q0 .825-.587 1.413T17 21zm2-4h2V8H9zm4 0h2V8h-2z" />
                    </svg>
                </button>
            </td>

        </tr>

        <div id="update<?php echo $row['id']; ?>" class="fixed top-0 left-0 flex justify-center items-center w-full h-full bg-black/30 backdrop-opacity-50 z-50 hidden">
            <div class="w-[50em] h-[50em] bg-gray-50 rounded-lg ">
                <div class="flex justify-between w-full items-center">
                    <p class="p-5 text-lg font-semibold text-left rtl:text-right text-gray-900">
                        Student Information
                    </p>
                    <svg onclick="toggleUpdateModal(<?php echo $row['id']; ?>)" class="mr-5 cursor-pointer hover:filter hover:drop-shadow-lg" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
                        <path fill="currentColor" d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15l-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152l2.758 3.15a1.2 1.2 0 0 1 0 1.698" />
                    </svg>
                </div>

                <form action="./update.php" method="POST" id="form" class="flex flex-col gap-5" onsubmit="return validateForm()" enctype="multipart/form-data">
                    <input type="hidden" name="item_id" id="item_id" value="<?php echo $row['item_id']; ?>">


                    <div class="p-5 px-20">
                        <div id="dropZone" class="mb-10">
                            <label for="avatar" class="tracking-wide block text-[11px] text-gray-900 uppercase font-bold">Student Picture</label>
                            <div id="avatar" class="flex items-center justify-center">
                                <label for="dropzone-file<?php echo $row['id']; ?>" class="dropArea relative overflow-hiddwn flex flex-col items-center justify-center  w-[200px] h-[200px] border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                        </svg>
                                        <p class="dragText mb-2 text-[10px] text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                        <p class="text-[10px] text-gray-500">SVG, PNG, JPG or JPEG</p>
                                    </div>
                                    <input id="dropzone-file<?php echo $row['id']; ?>" type="file" class="fileInput opacity-0 w-full h-full absolute" name="studentavatar" accept=".jpg,.jpeg,.png,.png" />
                                </label>
                                <div id="preview<?php echo $row['id']; ?>" class="previewImage">

                                </div>
                            </div>
                        </div>

                        <div id="allinputs<?php echo $row['id']; ?>" class="w-full">
                            <label for="name" class="tracking-wide block mb-1 text-[11px] text-gray-900 uppercase font-bold">Student Name</label>
                            <div id="name<?php echo $row['id']; ?>" class="w-full">
                                <input type="text" value="<?php echo $row['item_name']; ?>" class="w-full border border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block p-2.5 outline-0 placeholder:tracking-wide"
                                    name="name" id="name" placeholder="First Name" required>
                                <p class="opacity-0 mb-0.25 px-2 text-[11px] text-red-600 "><span class="font-medium">Oops!</span> Credential is wrong!</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <button type="submit" name="updateItem"
                                class="text-white bg-blue-500 hover:bg-blue-600 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none ">
                                Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


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