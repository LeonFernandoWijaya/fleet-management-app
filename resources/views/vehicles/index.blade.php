@extends('layouts.layout')

@section('content')
    <div class="flex flex-col gap-4">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">VEHICLE</h1>
        <div class="flex items-center gap-4 justify-between">
            <input type="text" onchange="loadVehicleData()" name="vehicleSearch" id="vehicleSearch"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                placeholder="Plate, brand, model..." required="">
            @moduleAction('Vehicle', 'Create')
                <button type="button" class="blue-button" onclick="openCreateVehicleModal()">Create new vehicle</button>
            @endmoduleAction
        </div>
        <ul class="flex items-center gap-2 md:grid-cols-2">
            <li>
                <input onchange="loadVehicleData()" type="radio" id="all" name="vehicleStatusFilter" value=""
                    class="hidden peer" required="" checked="">
                <label for="all"
                    class="inline-flex items-center justify-between w-full px-4 py-2 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">
                    <div class="block">
                        <div class="text-sm font-semibold">All statuses</div>
                    </div>
                </label>
            </li>
            @foreach ($vehicleStatuses as $vehicleStatus)
                <li>
                    <input onchange="loadVehicleData()" type="radio" id="{{ $vehicleStatus->id }}"
                        name="vehicleStatusFilter" value="{{ $vehicleStatus->id }}" class="hidden peer">
                    <label for="{{ $vehicleStatus->id }}"
                        class="inline-flex items-center justify-between w-full px-4 py-2 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">
                        <div class="block">
                            <div class="text-sm font-semibold">{{ $vehicleStatus->name }}</div>
                        </div>
                    </label>
                </li>
            @endforeach
        </ul>


        <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Plate
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Brand
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Model
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Type
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody id="vehicle-tbody">
                </tbody>
            </table>
            <ul class="mt-4 flex justify-center space-x-2 rtl:space-x-reverse text-sm" id="vehicle-pagination"></ul>
        </div>

    </div>
    @include('vehicles.form')
    @include('alerts.confirmation-delete')
    <script>
        $(document).ready(function() {
            loadVehicleData();
        });
    </script>
@endsection
