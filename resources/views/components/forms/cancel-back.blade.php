<input type="hidden" name="back_to" value="{{ old('back_to') ?? url()->previous() }}">
<x-link-button {{ $attributes }} href="{{ old('back_to') ?? url()->previous() }}">
    @if ($slot->isEmpty())
        Cancel
    @else
        {{ $slot }}
    @endif
</x-link-button>
