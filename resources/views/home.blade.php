@extends('layouts.app')

@section('content')
    @push('styles')
        <style>
            @media (prefers-color-scheme: light) {
                .bg-dots {
                    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,50,0.10)'/%3E%3C/svg%3E")
                }
            }
        </style>
    @endpush
    <div
            class="relative min-h-screen bg-gray-100 bg-center py-16 sm:py-24 lg:py-32 bg-dots selection:bg-sky-500 selection:text-white">


        {{--        @if (Route::has('login'))--}}
        {{--            <div class="p-6 text-right sm:fixed sm:top-0 sm:right-0">--}}
        {{--                @auth--}}
        {{--                    <a href="{{ route('home') }}"--}}
        {{--                       class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-indigo-500">Home</a>--}}
        {{--                @else--}}
        {{--                    <a href="{{ route('login') }}"--}}
        {{--                       class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-indigo-500">Log--}}
        {{--                        in</a>--}}

        {{--                    @if (Route::has('register'))--}}
        {{--                        <a href="{{ route('register') }}"--}}
        {{--                           class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-indigo-500">Register</a>--}}
        {{--                    @endif--}}
        {{--                @endauth--}}
        {{--            </div>--}}
        {{--        @endif--}}

        <div class="p-6 mx-auto max-w-4xl lg:p-8">
            <div class="flex justify-center">
                <h1 class="text-4xl tracking-tight">CPRS Lab Formatter</h1>
            </div>
            <div class="mt-4">
                <livewire:labs/>
            </div>
            <div class="flex justify-center px-0 mt-16 sm:items-center sm:justify-between">
                <div class="text-sm text-center text-gray-500 sm:text-left">
                    <p>
                        *Disclaimer: This site is designed to provide a convenient method to view labs in a
                        tabular format and grouped by standard panels. It may not display every lab and
                        does not contain all the comments for each lab. Users should verify that
                        all tests ordered are accounted for. The data and calculations
                        provided do not substitute for the healthcare providers
                        own chart review or their clinical judgment.
                    </p>

                </div>
            </div>
            <div class="flex justify-center px-0 mt-16 sm:items-center sm:justify-between">
                <div class="text-sm text-center text-gray-500 sm:text-left">
                    <div class="flex items-center gap-4">
                        <a href="https://www.buymeacoffee.com/tringali" target="_blank">Buy me a burrito bowl
                            &#127791; &#129379; </a>
                    </div>
                </div>
                <div class="ml-4 text-sm text-center text-gray-500 sm:text-right sm:ml-0">
                    Last Updated {{ (new DateTime(trim(exec('git log -n1 --pretty=%ci HEAD'))))->format('M j, Y') }}
                </div>
            </div>
        </div>
    </div>

@endsection
