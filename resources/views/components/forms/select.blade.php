@props(['options' => null, 'name' => null, 'default' => null, 'placeholder' => '', 'disabled' => false])
@php
    $selected = $name ? old($name, $default) : $default;
@endphp
<select
    {{ $disabled ? 'disabled' : '' }}
    @if ($name) name="{{ $name }}" @endif
    {!! $attributes->merge(['class' => 'text-gray-700 border-gray-300 focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50 rounded-md shadow-sm']) !!}>
    @if($options === null)
        {{ $slot }}
    @else
        <option value="">{{ $placeholder }}</option>
        @foreach ($options as $key => $value)
            <option value="{{ $key }}" @selected($selected == $key)>{{ $value }}</option>
        @endforeach
    @endif
</select>
