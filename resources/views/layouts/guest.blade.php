@extends('layouts.base')



@section('body')
    <div
        class="relative min-h-screen bg-gray-100 bg-center pt-8 md:pt-10 selection:bg-sky-500 selection:text-white">

        @push('styles')
            <style>
                body {
                    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,50,0.10)'/%3E%3C/svg%3E")
                }
            </style>
        @endpush

        <x-flash/>
        @yield('content')

        @isset($slot)
            {{ $slot }}
        @endisset

        <div class="mx-auto mt-8">


            <footer class="bg-gray-900 text-gray-300 mt-auto">
                <div class="max-w-7xl mx-auto px-6 py-12">
                    <div class="flex flex-col md:flex-row justify-between items-center gap-8">

                        <!-- Logo and Tagline -->
                        <div class="flex items-center gap-4">
                            <!-- Logo Image Placeholder -->
                            <div class="flex-shrink-0">
                                <x-application-logo class="w-16 h-16 block fill-current text-sky-400"/>
                            </div>

                            <!-- Text -->
                            <div class="text-center md:text-left">
                                <h3 class="text-3xl font-bold text-white mb-2">
                                    <span class="text-sky-400">Easy</span><span class="text-white">CPRSLabs</span>
                                </h3>
                                <p class="text-gray-400 text-sm max-w-xs">Beautifying VA lab results<br/>for healthcare
                                                                          professionals</p>
                            </div>
                        </div>

                        <!-- Navigation Links -->
                        <nav class="flex flex-wrap justify-center gap-6 text-sm">
                            <a href="{{ route('home') }}" class="text-gray-400 hover:text-sky-400 transition-colors duration-200 hover:underline">Home</a>
                            <span class="text-gray-700">|</span>
                            <a href="{{ route('about') }}" class="text-gray-400 hover:text-sky-400 transition-colors duration-200 hover:underline">About</a>
                            <span class="text-gray-700">|</span>
                            <a href="{{ route('faq') }}" class="text-gray-400 hover:text-sky-400 transition-colors duration-200 hover:underline">FAQ</a>
                            <span class="text-gray-700">|</span>
                            <a href="{{ route('contact') }}" class="text-gray-400 hover:text-sky-400 transition-colors duration-200 hover:underline">Contact</a>
                            <span class="text-gray-700">|</span>
                            <a href="https://www.buymeacoffee.com/tringali" target="_blank" class="text-gray-400 hover:text-sky-400 transition-colors duration-200 hover:underline">Sponsor</a>
                            <span class="text-gray-700">|</span>
                            <a href="{{ route('terms') }}" class="text-gray-400 hover:text-sky-400 transition-colors duration-200 hover:underline">Terms</a>
                            <span class="text-gray-700">|</span>
                            <a href="{{ route('policy') }}" class="text-gray-400 hover:text-sky-400 transition-colors duration-200 hover:underline">Privacy
                                                                                                                                                    Policy</a>
                        </nav>
                    </div>


                    <!-- Divider -->
                    <div class="border-t border-gray-800 my-12"></div>

                    <!-- Disclaimer Section -->
                    <div class="mb-6 text-gray-500 text-xs mx-auto leading-relaxed">
                        <p class="mb-3">
                            <span class="font-semibold text-gray-400">Disclaimer:</span>
                            EasyCPRSLabs is designed to provide a convenient method to view labs in a tabular format and
                            grouped by standard panels. It may not display every lab and does not contain all comments
                            for each lab. Users should verify that all tests ordered are accounted for. The data and
                            calculations provided do not substitute for the healthcare provider's own chart review or
                            their clinical judgment.</p>

                        <p class="mb-3">This tool does not provide medical advice, diagnosis, or treatment
                                        recommendations. All
                                        clinical decisions should be made by qualified healthcare providers based on
                                        complete patient
                                        information and clinical judgment. Users are responsible for verifying the
                                        accuracy of parsed
                                        data.</p>

                        <p>Do not transmit patient identifiers. Laboratory data is not saved on servers, however it is
                           transmitted securely to be formatted and then returned to the end user.</p>
                    </div>

                    <!-- Bottom Bar: Last Updated -->
                    <div class="text-center">
                        <p class="text-gray-600 text-xs">
                            Last updated:
                            <span class="text-sky-400 font-medium"> {{ (new DateTime(trim(exec('git log -n1 --pretty=%ci HEAD'))))->format('M j, Y') }}</span>
                            | © 2025
                            EasyCPRSLabs. All rights reserved.
                        </p>
                    </div>
                </div>
            </footer>
        </div>
    </div>
@endsection
