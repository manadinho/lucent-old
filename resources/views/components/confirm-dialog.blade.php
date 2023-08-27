<div class="fixed inset-0 flex items-center justify-center z-50 backdrop-blur confirm-dialog hidden">
    <div class="relative px-4 min-h-screen md:flex md:items-center md:justify-center">
        <div class=" opacity-25 w-full h-full absolute z-10 inset-0"></div>
        <div class="bg-white rounded-lg md:max-w-md md:mx-auto p-4 fixed inset-x-0 bottom-0 z-50 mb-4 mx-4 md:relative shadow-lg">
            <div class="md:flex items-center">
                <div class="rounded-full border border-gray-300 flex items-center justify-center w-16 h-16 flex-shrink-0 mx-auto">
                    <i class="bx bx-error text-3xl">
                        <x-bladewind.icon name="exclamation-triangle" />
                    </i>
                </div>
                <div class="mt-4 md:mt-0 md:ml-6 text-center md:text-left">
                    <p class="font-bold">Warning!</p>
                    <p class="text-sm text-gray-700 mt-1" id="confirm-dialog-message"></p>
                </div>
            </div>
            <div class="text-center md:text-right mt-4 md:flex md:justify-end">
                <button id="confirm-delete-btn" class="block w-full md:inline-block md:w-auto px-4 py-3 md:py-2 bg-gray-800 text-white rounded-lg font-semibold text-sm md:ml-2 md:order-2">
                    Delete
                </button>
                <button id="confirm-cancel-btn" class="block w-full md:inline-block md:w-auto px-4 py-3 md:py-2 bg-gray-500 text-white rounded-lg font-semibold text-sm mt-4 md:mt-0 md:order-1">
                Cancel
                </button>
            </div>
        </div>
    </div>
</div>
