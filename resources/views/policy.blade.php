@extends('layouts.guest')

@push('head')
    <meta name="description" content="Privacy Policy for EasyCPRSLabs - Learn how we protect your privacy when using our VA CPRS lab formatting tool. HIPAA-conscious design with no data storage.">
    <meta name="keywords" content="EasyCPRSLabs privacy, privacy policy, HIPAA, patient data protection, healthcare privacy, VA lab tool privacy">
    <meta property="og:title" content="Privacy Policy - EasyCPRSLabs">
    <meta property="og:description" content="Privacy policy for EasyCPRSLabs - HIPAA-conscious design ensuring patient data protection and privacy for healthcare providers.">
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="EasyCPRSLabs Privacy Policy">
    <meta name="twitter:description" content="Learn about our privacy practices and commitment to protecting healthcare provider data.">
    <title>Privacy Policy | EasyCPRSLabs</title>
@endpush

@section('content')
    <div class="p-4 md:p-6 mx-auto max-w-5xl lg:max-w-7xl">
        <x-logo-and-title title="Policy" subtitle="Privacy Policy and Data Protection"/>

        <div class="bg-white rounded shadow-2xl shadow-black/10 ring-1 ring-white/5 p-8 md:p-12">
            <div class="prose max-w-none prose-sky prose-headings:text-gray-900 prose-h1:text-sky-600 prose-h2:text-gray-800 prose-h3:text-sky-600">
                {!! $policy !!}
            </div>
        </div>
    </div>
@endsection
