@extends('layouts.auth')

@section('content')
    @include('admin.navigation')

    <div class="mx-auto max-w-4xl relative pt-10 bg-center  bg-dots selection:bg-sky-500 selection:text-white">
        <h1 class="mb-4 text-3xl font-extralight tracking-tight leading-none text-gray-900 md:text-4xl lg:text-5xl">
            Unrecognized Microbiology</h1>
        <h2 class="mb-4 text-1xl font-extralight tracking-tight leading-none text-gray-700 md:text-2xl lg:text-3xl">{{ $unrecognizedMicro->name }}</h2>

        <form method="POST" action="{{ route('admin.unprocessed-micros.update', $unrecognizedMicro) }}">
            @csrf
            <!-- Label -->
            <div class="mt-4">
                <x-forms.label for="label" value="Label*"/>
                <x-forms.input id="label" name="label" :value="str($unrecognizedMicro->name)->trim()->title()" type="text"
                               class="mt-1 block w-full"/>
                <x-forms.input-error for="label" class="mt-2"/>
            </div>

            <div class="flex items-center justify-start mt-4">
                <x-forms.submit-button/>
                <x-forms.cancel-back class="ml-4"/>
            </div>
        </form>

    </div>

@endsection
