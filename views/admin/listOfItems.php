<div class="mr-[30px] w-full bg-white p-5 rounded-tl-none rounded-lg shadow-md shadow-gray-200 flex gap-9" id="listOfItems">

    <!-- The whole card -->
    <div class="w-[500px]">

        <form action="./add.php" method="POST" onsubmit="return validateForm()" id="form" enctype="multipart/form-data">
            <div class="w-full relative gap-5 flex flex-col bg-gray-100 p-7 pb-[80px] rounded-lg border border-gray-300 ">

                <p class=" text-black text-lg font-semibold text-left border-black  border-b-[2px]">
                    Add New Item</span>
                </p>

                <div id="allinputs" class="w-full">

                    <div id="dropZone" class="mb-2">
                        <label for="avatar" class="tracking-wide block text-[11px] text-gray-900 uppercase font-bold ">Item Picture</label>
                        <div id="avatar" class="flex items-center justify-center">
                            <label for="dropzone-file" class="dropArea relative overflow-hiddwn flex flex-col items-center justify-center  w-[200px] h-[200px] border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-900">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 mb-4 text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                    </svg>
                                    <p class="dragText mb-2 text-[10px] text-gray-200"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                    <p class="text-[10px] text-gray-200">SVG, PNG, JPG or JPEG</p>
                                </div>
                                <input id="dropzone-file" type="file" class="fileInput opacity-0 w-full h-full absolute" name="itempic" accept=".jpg,.jpeg,.png,.png" />
                            </label>
                            <div id="preview" class="previewImage">

                            </div>
                        </div>
                    </div>

                    <label for="name" class=" tracking-wide block mb-1 text-[11px] text-gray-900 uppercase font-bold">Item Name</label>
                    <div id="name" class="w-full">
                        <input type="text" class="w-full border border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block p-2.5 outline-0 placeholder:tracking-wide"
                            name="name" id="item" placeholder="Enter Item Name" required>
                        <p class="opacity-0 mb-0.25 px-2 text-[11px] text-red-600 "><span class="font-medium">Oops!</span> Credential is wrong!</p>
                    </div>

                </div>


                <div class="absolute right-7 bottom-8 flex items-center gap-2 ">
                    <button type="submit" name="additem" class="button-click text-gray-100 font-medium flex justify-evenly items-center w-[100px]">
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M20 14h-6v6h-4v-6H4v-4h6V4h4v6h6z" />
                        </svg>
                        Add
                    </button>
                </div>
            </div>
        </form>

    </div>
    <!-- End of The whole card -->

    <div class="w-full">
        <div class=" w-full mb-5 flex gap-5">

            <div class="relative w-full h-full flex justify-start items-center">
                <input onkeyup="searchTable('allItems', 'search-dropdown')" type="search" id="search-dropdown" class="block pl-9 p-2.5 w-full  text-sm text-gray-900 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Search..." required />
                <svg class="w-5 h-5 absolute opacity-40 left-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                </svg>
            </div>

            <div class="relative max-w-sm w-[170px] ">
                <input type="date" class=" border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
            </div>

        </div>

        <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500" id="allItems">

                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            No
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Item Name
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Date
                        </th>
                        <th scope="col" class="px-6 py-3 text-nowrap">
                            QR Code
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php include "./itemTable.php"; ?>
                </tbody>
            </table>
        </div>
    </div>



</div>