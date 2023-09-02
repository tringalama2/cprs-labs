@extends('layouts.app')

@section('content')
    @include('admin.navigation')

    <div class="mx-auto max-w-4xl relative pt-10 bg-gray-100 bg-center  bg-dots selection:bg-sky-500 selection:text-white">
        <h1 class="mb-4 text-3xl font-extralight tracking-tight leading-none text-gray-900 md:text-4xl lg:text-5xl">
            {{ $panel->label }} Panel</h1>

        <h3 class="mb-4 text-1xl font-extralight tracking-tight leading-none text-gray-900 md:text-2xl lg:text-3xl">
            Panel Labs</h3>
        <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-700">
                <thead class="text-xs text-gray-700 uppercase bg-gray-300">
                <tr>
                    <th scope="col" class="px-4 py-2">Label</th>
                    <th scope="col" class="px-4 py-2">CPRS Name</th>
                    <th scope="col" class="px-4 py-2"></th>
                    <th scope="col" class="px-4 py-2"></th>
                </tr>
                </thead>
                <tbody id="labTableBody">
                @foreach($panel->labs as $lab)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700  hover:bg-gray-200"
                        data-id="{{ $lab->id }}">
                        <td class="px-4 py-2 align-top">{{ $lab->label }}</td>
                        <td class="px-4 py-2 align-top">{{ $lab->name }}</td>
                        <td class="px-4 py-2 align-top">
                            <x-icons.arrows-move fill="currentColor" class="mx-2 w-4"/>
                        </td>
                        <td class="px-4 py-2 align-top text-xs flex">

                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <livewire:lab-sort sortable-id="labTableBody"/>
@endsection
