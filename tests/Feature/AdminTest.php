<?php

use App\Models\Panel;
use App\Models\UnrecognizedLab;

it('restricts access to admin users', function (string $route, string $method) {
    login(null, ['is_admin' => false])
        ->$method($route)
        ->assertForbidden();
})->with([
    'admin.dashboard' => [fn () => route('admin.dashboard'), 'get'],
    'admin.panel.index' => [fn () => route('admin.panel.index'), 'get'],
    'admin.panel.create' => [fn () => route('admin.panel.create'), 'get'],
    'admin.panel.store' => [fn () => route('admin.panel.store'), 'post'],
    'admin.panel.show' => [fn () => route('admin.panel.show', Panel::factory()->create()), 'get'],
    'admin.panel.edit' => [fn () => route('admin.panel.edit', Panel::factory()->create()), 'get'],
    'admin.panel.update' => [fn () => route('admin.panel.update', Panel::factory()->create()), 'put'],
    'admin.unprocessed-labs.edit' => [
        fn () => route('admin.unprocessed-labs.edit',
            UnrecognizedLab::factory()->create()), 'get',
    ],
    'admin.unprocessed-labs.update' => [
        fn () => route('admin.unprocessed-labs.update',
            UnrecognizedLab::factory()->create()), 'post',
    ],
]);
