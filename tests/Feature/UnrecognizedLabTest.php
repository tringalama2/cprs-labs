<?php

use App\Models\Lab;
use App\Models\UnrecognizedLab;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use function Pest\Faker\fake;

it('can save unrecognized lab', function () {
    $unrecognizedLab = UnrecognizedLab::factory()->create();
    loginAsAdmin()->get(route('admin.unprocessed-labs.edit', $unrecognizedLab))->assertOk();

    $label = fake()->word;
    $panel_id = 1;

    loginAsAdmin()->post(route('admin.unprocessed-labs.update', $unrecognizedLab), [
        'label' => $label,
        'panel_id' => $panel_id,
    ])->assertRedirect(route('admin.panel.show', $panel_id))->assertSessionHas('message', "$label lab has been saved.");

    expect(Lab::latest('id')->first())
        ->label->toBe($label)
        ->name->toBe($unrecognizedLab->name)
        ->panel_id->toBe($panel_id);

    $this->assertDatabaseMissing('unrecognized_labs', ['id' => $unrecognizedLab->id]);
    $unrecognizedLab->refresh();
})->throws(ModelNotFoundException::class, 'No query results for model [App\Models\UnrecognizedLab].');
