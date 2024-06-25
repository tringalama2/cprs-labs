@extends('layouts.base')



@section('body')
    <div
        class="relative min-h-screen bg-gray-100 bg-center pt-16 pb-8 md:pt-20 md:pb-10 selection:bg-sky-500 selection:text-white">

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
            <footer class="p-4 border-t-2 border-gray-200 md:flex md:items-center md:justify-between md:p-6">
                <span class="text-xs text-gray-400 sm:text-center">Last Updated {{ (new DateTime(trim(exec('git log -n1 --pretty=%ci HEAD'))))->format('M j, Y') }}</span>
                <ul class="flex flex-wrap items-center mt-3 sm:mt-0">
                    <li>
                        <a href="https://www.buymeacoffee.com/tringali" target="_blank"
                           class="mr-8 text-xs text-gray-400 hover:underline md:mr-12">Buy me a burrito bowl &#127791;
                                                                                       &#129379; </a>
                    </li>
                    <li>
                        <a href="{{ route('terms') }}" class="mr-4 text-xs text-gray-400 hover:underline md:mr-6">Terms</a>
                    </li>
                    <li>
                        <a href="{{ route('policy') }}" class="mr-4 text-xs text-gray-400 hover:underline md:mr-6">Privacy
                                                                                                                   Policy</a>
                    </li>
                </ul>
            </footer>
        </div>
    </div>
@endsection
