@extends('layouts.app')

@section('content')
    @include('admin.navigation')


    <div class="mx-auto max-w-4xl relative pt-10 bg-gray-100 bg-center  bg-dots selection:bg-sky-500 selection:text-white">
        <h1 class="mb-4 text-3xl font-extralight tracking-tight leading-none text-gray-900 md:text-4xl lg:text-5xl">
            Dashboard</h1>
        <div class=" grid mb-8 border border-gray-200 rounded-lg shadow-sm md:mb-12 md:grid-cols-2 items-start">
            <div class="flex flex-col items-center justify-center p-8 text-center bg-white border-b border-gray-200 rounded-t-lg md:rounded-t-none md:rounded-tl-lg md:border-r dark:bg-gray-800 dark:border-gray-700">
                <h3 class="max-w-2xl mx-auto mb-2 text-lg font-semibold text-gray-900">Unparsable Labs</h3>
                <ul class="w-48 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg ">
                    @forelse($unparsableLabs as $lab)
                        <li class="w-full px-4 py-2 border-b border-gray-200 last:border-0 first:rounded-t-lg last:rounded-b-lg">
                            {{ $lab->name }}
                        </li>
                    @empty
                        <li class="w-full px-4 py-2">
                            All clear!
                        </li>
                    @endforelse
                </ul>
            </div>
            <div class="flex flex-col items-center justify-center p-8 text-center bg-white border-b border-gray-200 rounded-tr-lg dark:bg-gray-800 dark:border-gray-700">
                <h3 class="max-w-2xl mx-auto mb-2 text-lg font-semibold text-gray-900">Unrecognized Labs</h3>
                <ul class="w-48 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg ">
                    @forelse($unrecognizedLabs as $lab)
                        <li class="w-full px-4 py-2 border-b border-gray-200 last:border-0 first:rounded-t-lg last:rounded-b-lg">
                            {{ $lab->name }}
                            <a href="{{ route('admin.unprocessed-labs.edit', $lab) }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                     class="ml-2 inline" viewBox="0 0 16 16">
                                    <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
                                </svg>
                            </a>
                        </li>
                    @empty
                        <li class="w-full px-4 py-2">
                            All clear!
                        </li>
                    @endforelse
                </ul>
            </div>
            <div class="flex flex-col items-center justify-center p-8 text-center bg-white border-b border-gray-200 rounded-t-lg md:rounded-t-none md:rounded-tl-lg md:border-r dark:bg-gray-800 dark:border-gray-700">
                <h3 class="max-w-2xl mx-auto mb-2 text-lg font-semibold text-gray-900">Panels</h3>
                <ul class="w-48 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg ">
                    @foreach($panels as $panel)
                        <li class="w-full px-4 py-2 border-b border-gray-200 last:border-0 first:rounded-t-lg last:rounded-b-lg">
                            {{ $panel->label }}
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="flex flex-col items-center justify-center p-8 text-center bg-white border-b border-gray-200 rounded-tr-lg dark:bg-gray-800 dark:border-gray-700">
                <h3 class="max-w-2xl mx-auto mb-2 text-lg font-semibold text-gray-900">Labs</h3>
                <ul class="w-48 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg ">
                    @foreach($labs as $lab)
                        <li class="w-full px-4 py-2 border-b border-gray-200 last:border-0 first:rounded-t-lg last:rounded-b-lg">
                            {{ $lab->label }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

@endsection
