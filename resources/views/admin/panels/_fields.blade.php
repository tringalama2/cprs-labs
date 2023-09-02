@props(['panel' => null])

<!-- Label -->
<div class="mt-4">
    <x-forms.label for="label" value="Label*"/>
    <x-forms.input id="label" name="label" :value="$panel?->label" type="text"
                   class="mt-1 block w-full"/>
    <x-forms.input-error for="label" class="mt-2"/>
</div>

<!-- Order Column -->
<div class="mt-4">
    <x-forms.label for="order_column" value="Order Column"/>
    <x-forms.input id="order_column" name="order_column" :value="$panel?->order_column" type="text"
                   class="mt-1 block w-full"/>
    <x-forms.input-error for="order_column" class="mt-2"/>
</div>
