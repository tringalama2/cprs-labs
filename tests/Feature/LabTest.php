<?php

use App\Models\Lab;
use App\Models\Panel;

use function Pest\Faker\fake;

it('can store a lab', function () {
    loginAsAdmin()->get(route('admin.lab.create'))->assertOk();

    $label = fake()->word;
    $name = Str::of($label)->upper()->toString();
    $panel = Panel::first();
    loginAsAdmin()->post(route('admin.lab.store'), [
        'panel_id' => $panel->id,
        'label' => $label,
        'name' => $name,
    ])->assertRedirect(route('admin.panel.show', $panel))->assertSessionHas('message', "$label lab has been saved.");

    expect(Lab::latest('id')->first())
        ->label->toBe($label)
        ->name->toBe($name)
        ->panel_id->toBe($panel->id);
});

it('can edit a lab', function () {
    $lab = Lab::inRandomOrder()->first();
    loginAsAdmin()->get(route('admin.lab.edit', $lab))->assertOk();

    $newLabel = fake()->word;
    $newPanel = Panel::inRandomOrder()->first();
    loginAsAdmin()->put(route('admin.lab.update', $lab), [
        'label' => $newLabel,
        'name' => $lab->name,
        'panel_id' => $newPanel->id,
    ])->assertRedirect(route('admin.panel.show', $newPanel))->assertSessionHas('message', "$newLabel lab has been saved.");

    $lab->refresh();

    expect($lab)
        ->panel_id->toBe($newPanel->id)
        ->label->toBe($newLabel);
});
