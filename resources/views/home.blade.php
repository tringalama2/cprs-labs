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
        class="relative min-h-screen bg-gray-100 bg-center pt-16 pb-8 md:pt-20 md:pb-10 bg-dots selection:bg-sky-500 selection:text-white">


        {{--        @if (Route::has('login'))--}}
        {{--            <div class="p-6 text-right sm:fixed sm:top-0 sm:right-0">--}}
        {{--                @auth--}}
        {{--                    <a href="{{ route('home') }}"--}}
        {{--                       class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-sky-500">Home</a>--}}
        {{--                @else--}}
        {{--                    <a href="{{ route('login') }}"--}}
        {{--                       class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-sky-500">Log--}}
        {{--                        in</a>--}}

        {{--                    @if (Route::has('register'))--}}
        {{--                        <a href="{{ route('register') }}"--}}
        {{--                           class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-sky-500">Register</a>--}}
        {{--                    @endif--}}
        {{--                @endauth--}}
        {{--            </div>--}}
        {{--        @endif--}}
        <div class="p-4 md:p-6 mx-auto max-w-5xl lg:max-w-7xl">
            <div class="flex justify-center mb-6 md:mb-12">
                <h1 class="text-4xl tracking-tight">CPRS Lab Formatter</h1>
            </div>
            <div class="mt-4 flex flex-col md:flex-row  gap-12 lg:gap-20 xl:gap-24 2xl:gap-28">
                <div class="md:basis-1/3 lg:basis-1/4 2xl:basis-1/5">
                    <div class=" p-8 rounded bg-white shadow-2xl shadow-black/10 ring-1 ring-white/5">
                        <!-- Instruction Steps Panel -->
                        <ol class="relative text-gray-500 border-s border-gray-200 mb-2">
                            <li class="mb-10 ms-6" x-data="{ instructionsTooltip: false }" @mouseover.away="instructionsTooltip = false">
                            <span class="absolute flex items-center justify-center w-8 h-8 bg-sky-800 text-white rounded-full -start-4 ring-4 ring-sky-600 text-xl font-light">
                                1
                            </span>
                                <h3 class="font-medium leading-tight text-gray-600">Copy Labs from CPRS</h3>
                                <p class="text-sm my-2 leading-7">
                                    Select "All Tests By Date".
                                    Use
                                    <x-code :text="Agent::isMacintosh() ? 'Cmd' : 'Ctrl'"/>
                                    <x-code>A</x-code>
                                    to select all labs then

                                    <x-code :text="Agent::isMacintosh() ? 'Cmd' : 'Ctrl'"/>
                                    <x-code>C</x-code>

                                    to copy.
                                </p>
                                <a href="#" x-on:mouseover="instructionsTooltip = ! instructionsTooltip"
                                   class="text-sm font-medium text-sky-600 hover:underline">Show Me</a>
                                <div x-cloak x-show.transition="instructionsTooltip" class="absolute mt-4 ms-4 z-10 min-w-max">
                                    <div class="p-4 bg-white rounded shadow-lg ring-4 ring-gray-100">
                                        <h4 class="mt-1 text-xl font-semibold leading-tight"
                                        >CPRS 'Labs' Tab</h4>
                                        <div class="mt-4">
                                            <img src="{{ url(asset('instructions.gif')) }}" alt="instructions gif" class="mt-4 ring-2 ring-gray-300"/>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="mb-10 ms-6">
                                <span class="absolute flex items-center justify-center w-8 h-8 bg-sky-800 text-white rounded-full -start-4 ring-4 ring-sky-600 text-xl font-light">
                                    2
                                </span>
                                <h3 class="font-medium leading-tight text-gray-600">Paste Into Formatter</h3>
                                <p class="text-sm my-2  leading-7">
                                    Use
                                    <x-code :text="Agent::isMacintosh() ? 'Cmd' : 'Ctrl'"/>
                                    <x-code>P</x-code>
                                    to paste them into the text box and click "Submit".
                                </p>
                            </li>
                            <li class="ms-6">
                                <span class="absolute flex items-center justify-center w-8 h-8 bg-sky-800 text-white rounded-full -start-4 ring-4 ring-sky-600 text-xl font-light">
                                    3
                                </span>
                                <h3 class="font-medium leading-tight text-gray-600">Enjoy the Results!</h3>
                            </li>
                        </ol>
                    </div>
                </div>
                <div class="md:basis-2/3 lg:basis-1/2 2xl:basis-3/5">

                    <div>
                        <livewire:labs/>
                    </div>
                    <div class="flex justify-center px-0 mt-16 sm:items-center sm:justify-between">
                        <div class="text-sm text-center text-gray-500 sm:text-left">
                            <p class="mb-4">
                                *Disclaimer: This site is designed to provide a convenient method to view labs in a
                                tabular format and grouped by standard panels. It may not display every lab and
                                does not contain all the comments for each lab. Users should verify that
                                all tests ordered are accounted for. The data and calculations
                                provided do not substitute for the healthcare providers
                                own chart review or their clinical judgment.
                            </p>
                            <p class="mb-4">
                                Do not transmit patient identifiers. Laboratory data is not saved on servers,
                                however it is transmitted securely to be formatted and then
                                returned to the end user.
                            </p>
                        </div>
                    </div>
                    <div class="flex justify-center px-0 mt-8 sm:items-center sm:justify-between">
                        <div class="text-xs text-center text-gray-500 sm:text-left">
                            <div class="flex items-center gap-4">
                                <a href="https://www.buymeacoffee.com/tringali" target="_blank">Buy me a burrito bowl
                                                                                                &#127791; &#129379; </a>
                            </div>
                        </div>
                        <div class="ml-4 text-xs text-center text-gray-500 sm:text-right sm:ml-0">
                            Last Updated
                            {{ (new DateTime(trim(exec('git log -n1 --pretty=%ci HEAD'))))->format('M j, Y') }}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
