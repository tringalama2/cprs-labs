@props(['value', 'escapeHtml' => true])

<label {{ $attributes->merge(['class' => 'font-semibold block text-sm text-gray-700']) }}>
    @if($escapeHtml)
        {{ $value ?? $slot }}
    @else
        {!! $value ?? $slot !!}
    @endif
</label>
