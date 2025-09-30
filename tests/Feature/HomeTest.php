<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it can display home page', function () {
    $this->get(route('home'))->assertStatus(200);
});

test('it can display terms of service', function () {
    $this->get(route('terms'))->assertStatus(200);
});

test('it can display privacy policy', function () {
    $this->get(route('policy'))->assertStatus(200);
});

test('it can display about', function () {
    $this->get(route('about'))->assertStatus(200);
});

test('it can display faq', function () {
    $this->get(route('faq'))->assertStatus(200);
});

test('it can display contact', function () {
    $this->get(route('contact'))->assertStatus(200);
});
