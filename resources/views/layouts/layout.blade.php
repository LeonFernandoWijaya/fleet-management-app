<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="base-url" content="{{ url('/') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/style.css', 'resources/js/app.js', 'resources/css/app.css'])
    <script src="{{ url('js/app.js') }}"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="" type="image/x-icon">
    <title>COAL HAULING COMPANY</title>
</head>

<body class="font-sans">
    @auth
        @include('layouts.sidebar')

        <div class="p-4 sm:ml-64">
            <div class="mt-14">
            @endauth
            @yield('content')
            @auth
            </div>
        </div>
        @include('change-password.form')
    @endauth

    @include('alerts.alert')
    @include('alerts.confirmation-delete')
    @include('alerts.confirmation-update')
</body>

</html>
