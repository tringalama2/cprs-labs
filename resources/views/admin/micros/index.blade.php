@extends('layouts.auth')

@section('content')
    @include('admin.navigation')


    <div class="mx-auto max-w-4xl relative pt-10 bg-center">
        <h1 class="mb-4 text-3xl font-extralight tracking-tight leading-none text-gray-900 md:text-4xl lg:text-5xl">
            Microbiology</h1>
        <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-700">
                <thead class="text-xs text-gray-700 uppercase bg-gray-300">
                <tr>
                    <th scope="col" class="px-4 py-2">Label</th>
                    <th scope="col" class="px-4 py-2">Name</th>
                    <th scope="col" class="px-4 py-2"></th>
                    <th scope="col" class="px-4 py-2">
                        <a href="{{ route('admin.micro.create') }}">
                            <x-icons.plus fill="currentColor" class="ml-2 w-4"/>
                        </a>
                    </th>
                </tr>
                </thead>
                <tbody id="microTableBody">
                @foreach($micros as $micro)
                    <tr class="bg-white border-b hover:bg-gray-200"
                        data-id="{{ $micro->id }}">
                        <td class="px-4 py-2 align-top">{{ $micro->label }}</td>
                        <td class="px-4 py-2 align-top">
                            {{ $micro->name }}
                        </td>
                        <td class="px-4 py-2 align-top">
                            <x-icons.arrows-move fill="currentColor" class="mx-2 w-4"/>
                        </td>
                        <td class="px-4 py-2 align-top text-xs flex">
                            <a href="{{ route('admin.micro.edit', $micro) }}">
                                <x-icons.edit fill="currentColor" class="ml-2 w-4"/>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <livewire:micro-sort sortable-id="microTableBody"/>
@endsection
