@extends('layouts.app')

@section('content')
    @include('admin.navigation')

    <div class="mx-auto max-w-4xl relative pt-10 bg-center">
        <h1 class="mb-4 text-3xl font-extralight tracking-tight leading-none text-gray-900 md:text-4xl lg:text-5xl">
            Edit {{ $lab->label }} lab</h1>


        <form method="post" action="{{ route('admin.lab.update', $lab) }}">
            @csrf
            @method('PUT')

            <!-- Panel -->
            <div class="mt-4">
                <x-forms.label for="panel_id" value="Panel*"/>
                <x-forms.selects.panels id="panel_id" name="panel_id" :default="$lab->panel_id" class="block mt-1 w-full"/>
                <x-forms.input-error for="panel_id" class="mt-2"/>
            </div>

            <!-- Name -->
            <div class="mt-4">
                <x-forms.label for="name" value="CPRS Name"/>
                <x-forms.input id="name" name="name" :value="$lab->name" type="text" class="bg-gray-200 !text-gray-500 mt-1 block w-full"/>
                <x-forms.input-error for="name" class="mt-2"/>
            </div>

            <!-- Label -->
            <div class="mt-4">
                <x-forms.label for="label" value="Label*"/>
                <x-forms.input id="label" name="label" :value="$lab->label" type="text" class="mt-1 block w-full"/>
                <x-forms.input-error for="label" class="mt-2"/>
            </div>

            <div class="flex items-center justify-start mt-4">
                <x-forms.submit-button/>
                <x-forms.cancel-back class="ml-4"/>
            </div>
        </form>
    </div>

@endsection
