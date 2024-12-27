<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="category" content="Business"/>
    <meta name="keywords" content="medical education, electronic health record">
    <meta name="description"
          content="Allows users to format labs from CPRS for enhanced viewing. No patient information or lab data is stored on servers or local computers.">

    <title inertia>{{ config('app.name', 'Laravel') }}</title>

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ url(asset('apple-touch-icon.png')) }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ url(asset('favicon-32x32.png')) }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ url(asset('favicon-16x16.png')) }}">
    <link rel="manifest" href="{{ url(asset('site.webmanifest')) }}">

    <!-- Fonts -->
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">

    @vite(['resources/css/app.css', 'resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    @stack('styles')
    <style>
        body {
            background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,50,0.10)'/%3E%3C/svg%3E")
        }
    </style>
    @inertiaHead


    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
@inertia
@yield('body')
@stack('endScripts')
</body>
</html>
