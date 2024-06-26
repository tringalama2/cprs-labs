@props(['name' => null, 'value' => null, 'disabled' => false])

@php
    $value = $name ? old($name, $value) : $value;
@endphp

<textarea {{ $disabled ? 'disabled' : '' }}
          @if ($name) name="{{ $name }}" @endif
    {!! $attributes->merge(['class' => 'text-gray-700 border-gray-300 focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50 rounded-md shadow-sm']) !!}
    >{{ trim($slot) == '' ? $value : $slot  }}</textarea>
