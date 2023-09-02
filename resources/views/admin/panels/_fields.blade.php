@props(['panel' => null])

<!-- Label -->
<div class="mt-4">
    <x-forms.label for="label" value="Label*"/>
    <x-forms.input id="label" name="label" :value="$panel?->label" type="text"
                   class="mt-1 block w-full"/>
    <x-forms.input-error for="label" class="mt-2"/>
</div>
