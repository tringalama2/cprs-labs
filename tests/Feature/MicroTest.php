<?php

use App\Models\Micro;

it('can list all micros', function () {
    $microLabels = Micro::query()
        ->orderBy('order_column')
        ->get()
        ->pluck('label')
        ->toArray();

    loginAsAdmin()->get(route('admin.micro.index'))->assertOk()->assertSee($microLabels);
});

it('can store a micro', function () {
    loginAsAdmin()->get(route('admin.micro.create'))->assertOk();

    $label = fake()->word;
    $name = Str::of($label)->upper()->toString();
    loginAsAdmin()->post(route('admin.micro.store'), [
        'label' => $label,
        'name' => $name,
    ])->assertRedirect(route('admin.micro.index'))->assertSessionHas('message', "$label micro has been saved.");

    expect(Micro::latest('id')->first())
        ->label->toBe($label)
        ->name->toBe($name);
});

it('can edit a micro', function () {
    $micro = Micro::factory()->create();
    loginAsAdmin()->get(route('admin.micro.edit', $micro))->assertOk();

    $newLabel = fake()->word;
    loginAsAdmin()->put(route('admin.micro.update', $micro), [
        'label' => $newLabel,
        'name' => $micro->name,
    ])->assertRedirect(route('admin.micro.index'))->assertSessionHas('message', "$newLabel micro has been saved.");

    $micro->refresh();

    expect($micro)->label->toBe($newLabel);
});
