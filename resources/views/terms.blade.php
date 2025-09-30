@extends('layouts.guest')

@push('head')
    <meta name="description" content="Terms of Service for EasyCPRSLabs - Read our terms and conditions for using our VA CPRS lab formatting tool designed for healthcare providers.">
    <meta name="keywords" content="EasyCPRSLabs terms, terms of service, legal terms, healthcare tool terms, VA lab tool conditions">
    <meta property="og:title" content="Terms of Service - EasyCPRSLabs">
    <meta property="og:description" content="Terms and conditions for using EasyCPRSLabs, the essential VA CPRS lab formatting tool for healthcare providers.">
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="EasyCPRSLabs Terms of Service">
    <meta name="twitter:description" content="Legal terms and conditions for using our VA CPRS lab results formatting tool.">
    <title>Terms of Service | EasyCPRSLabs</title>
@endpush

@section('content')
    <div class="p-4 md:p-6 mx-auto max-w-5xl lg:max-w-7xl">
        <x-logo-and-title title="Terms" subtitle="Terms of Service and Conditions of Use"/>

        <!-- Terms Content -->
        <div class="bg-white rounded shadow-2xl shadow-black/10 ring-1 ring-white/5 p-8 md:p-12">
            <div class="prose max-w-none prose-sky prose-headings:text-gray-900 prose-h1:text-sky-600 prose-h2:text-gray-800 prose-h3:text-sky-600">
                {!! $terms !!}
            </div>
        </div>
    </div>
@endsection
