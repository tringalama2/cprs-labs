<?php

use App\Models\Lab;
use App\Models\Panel;

use function Pest\Faker\fake;

it('can store a panel', function () {
    loginAsAdmin()->get(route('admin.panel.create'))->assertOk();

    $label = fake()->word;
    loginAsAdmin()->post(route('admin.panel.store'), [
        'label' => $label,
    ])->assertRedirect(route('admin.panel.index'))->assertSessionHas('message', "{$label} panel has been saved.");

    $panel = Panel::latest('id')->first();

    expect($panel->label)->toBe($label);
});

it('can edit a panel', function () {
    $panel = Panel::factory()->create();
    loginAsAdmin()->get(route('admin.panel.edit', $panel))->assertOk();
    $newLabel = fake()->word;
    loginAsAdmin()->put(route('admin.panel.update', $panel), [
        'label' => $newLabel,
    ])->assertRedirect(route('admin.panel.index'))->assertSessionHas('message', "{$newLabel} panel has been saved.");

    $panel->refresh();

    expect($panel->label)->toBe($newLabel);
});

it('can show a panel', function () {
    $panel = Panel::factory()->has(Lab::factory()->count(5))->create();
    $labLabels = Lab::where('panel_id', $panel->id)
        ->orderBy('order_column')
        ->get()
        ->pluck('label')
        ->toArray();
    loginAsAdmin()->get(route('admin.panel.show', $panel))->assertOk()->assertSeeTextInOrder($labLabels);

});

it('can list all panels', function () {
    $panelLabels = Lab::query()
        ->orderBy('order_column')
        ->get()
        ->pluck('label')
        ->toArray();
    loginAsAdmin()->get(route('admin.panel.index'))->assertOk()->assertSee($panelLabels);

});
