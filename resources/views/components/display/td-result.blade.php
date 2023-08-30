@props([
    'result',
    'flag' => '',
])

@php
    $isCriticalValue = str($flag)->contains('*');

    $isAbnormalValue = !$isCriticalValue && str($flag)->contains(['H', 'L']);
@endphp

<td {{ $attributes->class(['border-r border-gray-500 px-2 text-center whitespace-nowrap',
    'bg-red-500 text-red-950 group-hover:bg-sky-500 font-bold' => $isCriticalValue,
    'bg-red-300 text-red-900 group-hover:bg-sky-400 font-bold' => $isAbnormalValue,
    'bg-white group-hover:bg-sky-200' => !$isAbnormalValue && !$isCriticalValue,
    ]) }}>{{ $result }}</td>
