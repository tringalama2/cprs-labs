@extends('layouts.base')

@section('body')
    @yield('content')

    @isset($slot)
        {{ $slot }}
    @endisset
    
    @livewire('livewire-ui-modal')
@endsection
