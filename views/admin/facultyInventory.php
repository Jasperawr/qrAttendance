<div class="mr-[30px] w-full hidden bg-white p-7 rounded-lg shadow-md shadow-gray-200" id="facultyInventory">
    <div class=" w-full mb-5 flex gap-5">
        <div class="relative w-full h-full flex justify-start items-center">
            <input onkeyup="searchTable('allInv', 'facultInv')" type="search" id="facultInv" class="block pl-9 p-2.5 w-full  text-sm text-gray-900 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Search..." required />
            <svg class="w-5 h-5 absolute opacity-40 left-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
            </svg>
        </div>



        <div class="relative max-w-sm w-[170px] ">
            <input type="date" class=" border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
        </div>


    </div>

    <div class="relative overflow-x-auto  rounded-tl-none  rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500" id="allInv">
            <caption class="p-5 text-lg font-semibold text-left rtl:text-right text-gray-900 bg-white ">
                Faculty Inventory
            </caption>
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Id
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Faculty Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Item Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Items No
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Date
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php include "./facultyInventoryTable.php"; ?>
            </tbody>
        </table>
    </div>

</div>