@extends('layouts.layout')

@section('content')
<div class="flex flex-col gap-4">
    <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">SPARE PARTS</h1>
    <div class="flex items-center gap-4 justify-between">
        <input type="text" onchange="loadSparepartData()" name="sparepartSearch" id="sparepartSearch"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
            placeholder="Type spare part name" required="">
        <button type="button" class="blue-button" onclick="openCreateSparepartModal()">Create new spare part</button>
    </div>

    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-2 py-3">

                    </th>
                    <th scope="col" class="px-6 py-3">
                        Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Stock
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Reorder Level
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody id="sparepart-tbody">
            </tbody>
        </table>
        <ul class="mt-4 flex justify-center space-x-2 rtl:space-x-reverse text-sm" id="sparepart-pagination"></ul>
    </div>

</div>

@include('spareparts.form')
@include('alerts.confirmation-delete')
<script>
    $(document).ready(function() {
        loadSparepartData();
    });
</script>
@endsection