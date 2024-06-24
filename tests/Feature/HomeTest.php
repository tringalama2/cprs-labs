<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it can display home page', function () {
    $this->get('/')->assertStatus(200);
});

test('it can display terms of service', function () {
    $this->get('/terms')->assertStatus(200);
});

test('it can display privacy policy', function () {
    $this->get('/policy')->assertStatus(200);
});
