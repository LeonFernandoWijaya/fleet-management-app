@extends('layouts.layout')

@section('content')
    <div class="flex items-center justify-center min-h-screen lg:p-0 p-8">
        <div
            class="py-8 px-10 bg-white lg:w-1/2 w-full justify-center border border-gray-300 dark:border-gray-700 rounded-xl shadow-xl">
            <form class="flex flex-col justify-center">
                <h1 class="font-bold text-2xl mb-8 text-center">Coal Haul Company Login</h1>
                <div class="mb-4">
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email<span
                            class="text-red-500">*</span></label>
                    <input type="email" name="email" id="email"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                        placeholder="Enter your email" required="">
                </div>
                <div class="mb-4">
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Password<span
                            class="text-red-500">*</span></label>
                    <input type="password" name="password" id="password"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 mb-2"
                        placeholder="Enter your password" required="">
                    <div class="flex items-center mb-4">
                        <input id="show-password" type="checkbox" value=""
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                        <label for="show-password" class="ms-2 text-sm font-medium text-gray-900">Show
                            Password</label>
                    </div>
                </div>
                <div class="text-center">
                    <button type="button" onclick="login()"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-8 py-2.5 mb-3 focus:outline-none">Login</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        $('#show-password').click(function() {
            if ($(this).is(':checked')) {
                $('#password').attr('type', 'text');
            } else {
                $('#password').attr('type', 'password');
            }
        });

        $(document).keypress(function(event) {
            if (event.which == 13) {
                login();
            }
        });
    </script>
@endsection
