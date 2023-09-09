@extends('layouts.app')

@section('content')
    @include('admin.navigation')

    <div class="mx-auto max-w-4xl relative pt-10 bg-center">
        <h1 class="mb-4 text-3xl font-extralight tracking-tight leading-none text-gray-900 md:text-4xl lg:text-5xl">
            Edit {{ $panel->label }} Panel</h1>


        <form method="post" action="{{ route('admin.panel.update', $panel) }}">
            @csrf
            @method('PUT')

            @include('admin.panels._fields')

            <div class="flex items-center justify-start mt-4">
                <x-forms.submit-button/>
                <x-forms.cancel-back class="ml-4"/>
            </div>
        </form>
    </div>

@endsection
