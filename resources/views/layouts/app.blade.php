@extends('layouts.base')

@section('body')
    <x-flash/>
    @yield('content')

    @isset($slot)
        {{ $slot }}
    @endisset

    @livewire('livewire-ui-modal')
@endsection
