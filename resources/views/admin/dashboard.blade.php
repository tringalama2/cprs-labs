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