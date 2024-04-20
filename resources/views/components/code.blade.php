<code {{ $attributes->class(['tracking-widest text-xs text-left bg-gray-700 text-white rounded-md p-1.5 me-1']) }}>{{ $slot->isEmpty() ? $text : $slot }}</code>
