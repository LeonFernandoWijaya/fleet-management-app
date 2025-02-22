<div id="trip-form-modal" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white" id="trip-form-title">
                </h3>
                <button type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    onclick="hideFlowBytesModal('trip-form-modal')">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <form class="p-4 md:p-5" id="trip-form-data">
                <div class="grid gap-4 mb-4 grid-cols-2">
                    <div class="col-span-2">
                        <label for="trip-driver"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Trip driver</label>
                        <select id="trip-driver" name="trip-driver"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label for="trip-vehicle"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Trip vehicle</label>
                        <select id="trip-vehicle" name="trip-vehicle"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label for="trip-departure-time"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Trip departure
                            time</label>
                        <input type="datetime-local" name="trip-departure-time" id="trip-departure-time"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Type trip departure time" required="">
                    </div>
                    <div class="col-span-2">
                        <label for="trip-arrival-time"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Trip arrival
                            time</label>
                        <input type="datetime-local" name="trip-arrival-time" id="trip-arrival-time"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Type trip arrival time" required="">
                    </div>
                    <div class="col-span-2">
                        <label for="trip-departure-location"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Trip departure
                            location</label>
                        <input type="text" name="trip-departure-location" id="trip-departure-location"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Type trip departure location" required="">
                    </div>
                    <div class="col-span-2">
                        <label for="trip-arrival-location"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Trip arrival
                            location</label>
                        <input type="text" name="trip-arrival-location" id="trip-arrival-location"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Type trip arrival location" required="">
                    </div>
                </div>
                <button type="button" id="trip-form-submit"
                    class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Save Trip
                </button>
            </form>
        </div>
    </div>
</div>
