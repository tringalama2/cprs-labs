<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="category" content="Business"/>

    <!-- Default meta tags - can be overridden by individual pages -->
    <meta name="keywords" content="CPRS labs, VA EHR, medical residents, lab results formatter, healthcare tools, medical education, electronic health record">
    <meta name="description" content="EasyCPRSLabs - Transform VA CPRS lab results into clean, organized tables. Essential tool for medical residents, fellows, and healthcare providers.">
    @hasSection('title')
        <title>@yield('title') - {{ config('app.name') }}</title>
    @else
        <title>{{ config('app.name') }}</title>
    @endif

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ url(asset('apple-touch-icon.png')) }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ url(asset('favicon-32x32.png')) }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ url(asset('favicon-16x16.png')) }}">
    <link rel="manifest" href="{{ url(asset('site.webmanifest')) }}">

    <!-- Fonts -->
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @stack('head')
</head>

<body>
@yield('body')
@stack('endScripts')
</body>
</html>
