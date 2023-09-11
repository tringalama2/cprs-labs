<?php

use App\Models\Micro;
use App\Models\UnrecognizedMicro;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use function Pest\Faker\fake;

it('can save unrecognized micro', function () {
    $unrecognizedMicro = UnrecognizedMicro::factory()->create();
    loginAsAdmin()->get(route('admin.unprocessed-micros.edit', $unrecognizedMicro))->assertOk();

    $label = fake()->word;

    loginAsAdmin()->post(route('admin.unprocessed-micros.update', $unrecognizedMicro), [
        'label' => $label,
    ])->assertRedirect(route('admin.micro.index'))->assertSessionHas('message', "$label micro has been saved.");

    expect(Micro::latest('id')->first())
        ->label->toBe($label)
        ->name->toBe($unrecognizedMicro->name);

    $this->assertDatabaseMissing('unrecognized_micros', ['id' => $unrecognizedMicro->id]);
    $unrecognizedMicro->refresh();
})->throws(ModelNotFoundException::class, 'No query results for model [App\Models\UnrecognizedMicro].');
