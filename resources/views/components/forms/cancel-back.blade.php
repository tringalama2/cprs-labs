<input type="hidden" name="back_to" value="{{ old('back_to') ?? url()->previous() }}">
<x-anchor-button-light href="{{ old('back_to') ?? url()->previous() }}" class="ml-2">{{ $slot }}</x-anchor-button-light>
