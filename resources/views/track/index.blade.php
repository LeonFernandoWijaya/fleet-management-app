@extends('layouts.layout')

@section('content')
<div class="grid grid-cols-1 gap-16 items-center">
    <div class="flex flex-row items-center justify-between">
        <div>
            <button type="button"
                class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium text-sm px-5 py-2.5 rounded-full dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Report
                Issue</button>
        </div>
        <div>
            <button type="button"
                class="focus:outline-none text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-full text-sm px-5 py-2.5 dark:focus:ring-yellow-900">Report
                Trip</button>
        </div>
    </div>
    <div class="w-full h-52 mb-5 rounded-2xl border border-gray-300 dark:border-gray-700" id="mapid">

    </div>
    <div class="flex items-center justify-center">
        <button type="button" onclick="startTracking()"
            class="text-blue-700 border text-xl animate-pulse border-blue-700 hover:bg-blue-700 hover:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-full p-2.5 text-center flex justify-center items-center dark:border-blue-500 w-40 h-40 dark:text-blue-500 dark:hover:text-white dark:focus:ring-blue-800 dark:hover:bg-blue-500">
            Start
        </button>
    </div>
</div>
<script>
    let latitude = 0;
    let longitude = 0;
    let mymap = null;

    $(document).ready(async function() {
        await getLocation();
        await mapMaker();
    });
</script>
@endsection