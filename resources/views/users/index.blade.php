@extends('layouts.layout')

@section('content')
    <div class="flex flex-col gap-4">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">USERS</h1>
        <div class="flex items-center gap-4 justify-between">
            <input type="text" onchange="loadUserData()" name="userSearch" id="userSearch"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                placeholder="Type user name" required="">
            @moduleAction('User', 'Create')
                <button type="button" class="blue-button" onclick="openCreateUserModal()">Create new
                    user</button>
            @endmoduleAction
        </div>
        <ul class="flex items-center gap-2 md:grid-cols-2">
            <li>
                <input onchange="loadUserData()" type="radio" id="all" name="userStatusFilter" value=""
                    class="hidden peer" required="" checked="">
                <label for="all"
                    class="inline-flex items-center justify-between w-full px-4 py-2 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">
                    <div class="block">
                        <div class="text-sm font-semibold">All statuses</div>
                    </div>
                </label>
            </li>
            <li>
                <input onchange="loadUserData()" type="radio" id="active" name="userStatusFilter" value="active"
                    class="hidden peer">
                <label for="active"
                    class="inline-flex items-center justify-between w-full px-4 py-2 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">
                    <div class="block">
                        <div class="text-sm font-semibold">Active</div>
                    </div>
                </label>
            </li>
            <li>
                <input onchange="loadUserData()" type="radio" id="inactive" name="userStatusFilter" value="inactive"
                    class="hidden peer">
                <label for="inactive"
                    class="inline-flex items-center justify-between w-full px-4 py-2 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">
                    <div class="block">
                        <div class="text-sm font-semibold">Inactive</div>
                    </div>
                </label>
            </li>
        </ul>

        <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Name
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Email
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Role
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Is active
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody id="user-tbody">
                </tbody>
            </table>
            <ul class="mt-4 flex justify-center space-x-2 rtl:space-x-reverse text-sm" id="user-pagination"></ul>
        </div>

    </div>

    @include('users.form')
    @include('alerts.confirmation-delete')
    <script>
        $(document).ready(function() {
            loadUserData();
        });
    </script>
@endsection
