
<div id="crud-modal" class="hidden overflow-y-auto overflow-x-hidden absolute top-0 right-0 left-0 z-50 flex justify-center items-center w-full h-full bg-gray-800 bg-opacity-50 max-w-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow ">
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t ">
                <h3 class="text-lg font-semibold text-gray-900">
                    Update Info
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center " data-modal-toggle="crud-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                </button>
            </div>
                        <form action="./add.php" method="POST" onsubmit="return validateForm()"  id="form" class="py-5">
                            <div class="w-full relative gap-5 flex flex-col px-7">

                                <div id="allinputs" class="w-full">
                                    <label for="firstname" class="tracking-wide block mb-1 text-[11px] text-gray-900 uppercase font-bold">Item Name</label>
                                    <div id="firstname" class="w-full">
                                        <input type="text" class="w-full border border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block p-2.5 outline-0 placeholder:tracking-wide" 
                                        name="name" id="fname" placeholder="Item Name" required>
                                        <p class="opacity-0 mb-0.25 px-2 text-[11px] text-red-600 "><span class="font-medium">Oops!</span> Credential is wrong!</p>
                                    </div>


                                    <div  class="w-full">
                                        <label for="email" class="tracking-wide block mb-1 text-[11px] text-gray-900 uppercase font-bold">Quantity</label>
                                        <input type="numbers" class="w-full border border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block p-2.5 outline-none placeholder:tracking-wide" 
                                        name="quantity" id="email" placeholder="" required>
                                        <p class="opacity-0 mb-0.25 px-2 text-[11px] text-red-600 "><span class="font-medium">Oops!</span> Credential is wrong!</p>
                                    </div>

                                </div>
                            </div>     
                            <button type="submit" class="ml-7 text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                Submit Changes
                            </button> 
                        </form>   

        </div>
    </div>
</div> 
