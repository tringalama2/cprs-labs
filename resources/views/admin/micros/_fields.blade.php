@props(['micro' => null])

<!-- Name -->
<div class="mt-4">
    <x-forms.label for="name" value="CPRS Name"/>
    <x-forms.input id="name" name="name" :value="$micro?->name" type="text" :class="($micro!==null ? 'bg-gray-200 !text-gray-500': '') . 'mt-1 block w-full'"/>
    <x-forms.input-error for="name" class="mt-2"/>
</div>

<!-- Label -->
<div class="mt-4">
    <x-forms.label for="label" value="Label*"/>
    <x-forms.input id="label" name="label" :value="$micro?->label" type="text" class="mt-1 block w-full"/>
    <x-forms.input-error for="label" class="mt-2"/>
</div>
