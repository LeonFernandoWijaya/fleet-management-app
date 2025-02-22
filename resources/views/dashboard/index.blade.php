@extends('layouts.layout')

@section('content')
    <div class="flex flex-col gap-4">
        <div>
            <button type="button" onclick="refreshDashboard()"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center me-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3.5 h-3.5 me-2"
                    id="track-refresh-icon">
                    <path fill-rule="evenodd"
                        d="M4.755 10.059a7.5 7.5 0 0 1 12.548-3.364l1.903 1.903h-3.183a.75.75 0 1 0 0 1.5h4.992a.75.75 0 0 0 .75-.75V4.356a.75.75 0 0 0-1.5 0v3.18l-1.9-1.9A9 9 0 0 0 3.306 9.67a.75.75 0 1 0 1.45.388Zm15.408 3.352a.75.75 0 0 0-.919.53 7.5 7.5 0 0 1-12.548 3.364l-1.902-1.903h3.183a.75.75 0 0 0 0-1.5H2.984a.75.75 0 0 0-.75.75v4.992a.75.75 0 0 0 1.5 0v-3.18l1.9 1.9a9 9 0 0 0 15.059-4.035.75.75 0 0 0-.53-.918Z"
                        clip-rule="evenodd" />
                </svg>
                Refresh
            </button>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div class="flex flex-col gap-4 p-4 rounded-xl border border-gray-300 dark:border-gray-700 ">
                <div class="border-b border-gray-300 dark:border-gray-700 pb-4">
                    <p class="font-bold text-lg">Vehicle Status</p>
                </div>
                <div class="py-6" id="vehicle-status-chart"></div>
            </div>
            <div class="flex flex-col gap-4 p-4 rounded-xl border border-gray-300 dark:border-gray-700 ">
                <div class="border-b border-gray-300 dark:border-gray-700 pb-4">
                    <p class="font-bold text-lg">Trips</p>
                </div>
                <div class="flex flex-col gap-4 text-sm">
                    <div class="flex flex-col gap-4 border border-blue-300 dark:border-blue-700 p-4 rounded-xl">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                            <p>53 Scheduled</p>
                        </div>
                    </div>
                    <div class="flex flex-col gap-4 border border-yellow-300 dark:border-yellow-700 p-4 rounded-xl">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                            <p>12 In Progress</p>
                        </div>
                    </div>
                    <div class="flex flex-col gap-4 border border-red-300 dark:border-red-700 p-4 rounded-xl">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                            <p>5 Delayed</p>
                        </div>
                    </div>
                    <div class="flex flex-col gap-4 border border-green-300 dark:border-green-700 p-4 rounded-xl">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <p>5 Completed</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-col gap-4 p-4 rounded-xl border border-gray-300 dark:border-gray-700 ">
                <div class="border-b border-gray-300 dark:border-gray-700 pb-4">
                    <p class="font-bold text-lg">Vehicle Reports</p>
                </div>
                <div class="flex flex-col gap-4 text-sm">
                    <div class="flex flex-col gap-4 border border-green-300 dark:border-green-700 p-4 rounded-xl">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <p>5 Fixed</p>
                        </div>
                    </div>
                    <div class="flex flex-col gap-4 border border-red-300 dark:border-red-700 p-4 rounded-xl">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                            <p>5 Not Fixed</p>
                        </div>
                    </div>

                </div>
            </div>
            <div class="flex flex-col gap-4 p-4 rounded-xl border border-gray-300 dark:border-gray-700 ">
                <div class="border-b border-gray-300 dark:border-gray-700 pb-4">
                    <p class="font-bold text-lg">Maintenances</p>
                </div>
                <div class="flex flex-col gap-4 text-sm">
                    <div class="flex flex-col gap-4 border border-green-300 dark:border-green-700 p-4 rounded-xl">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <p>5 Good</p>
                        </div>
                    </div>
                    <div class="flex flex-col gap-4 border border-red-300 dark:border-red-700 p-4 rounded-xl">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                            <p>5 Due</p>
                        </div>
                    </div>

                </div>
            </div>
            <div class="flex flex-col gap-4 p-4 rounded-xl border border-gray-300 dark:border-gray-700 ">
                <div class="border-b border-gray-300 dark:border-gray-700 pb-4">
                    <p class="font-bold text-lg">Spareparts</p>
                </div>
                <div class="flex flex-col gap-4 text-sm">
                    <div class="flex flex-col gap-4 border border-green-300 dark:border-green-700 p-4 rounded-xl">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <p>5 High</p>
                        </div>
                    </div>
                    <div class="flex flex-col gap-4 border border-red-300 dark:border-red-700 p-4 rounded-xl">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                            <p>5 Low</p>
                        </div>
                    </div>

                </div>
            </div>
            <div class="flex flex-col gap-4 p-4 rounded-xl border border-gray-300 dark:border-gray-700 ">
                <div class="border-b border-gray-300 dark:border-gray-700 pb-4">
                    <p class="font-bold text-lg">Documents</p>
                </div>
                <div class="flex flex-col gap-4 text-sm">
                    <div class="flex flex-col gap-4 border border-green-300 dark:border-green-700 p-4 rounded-xl">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <p>5 Valid</p>
                        </div>
                    </div>
                    <div class="flex flex-col gap-4 border border-red-300 dark:border-red-700 p-4 rounded-xl">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                            <p>5 Expired</p>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
    <script>
        let vehicleStatusChart = null;
        $(document).ready(function() {
            getVehicleGroupByStatus();
            getTripsGroupByStatus();
        });
    </script>
@endsection
