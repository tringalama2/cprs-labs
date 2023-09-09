<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

use function Pest\Livewire\livewire;

beforeEach(function () {
    Route::get('/must-be-confirmed', function () {
        return 'You must be confirmed to see this page.';
    })->middleware(['web', 'password.confirm']);
});

test('a user must confirm their password before visiting a protected page', function () {
    $user = User::factory()->create();
    $this->be($user);

    $this->get('/must-be-confirmed')
        ->assertRedirect(route('password.confirm'));

    $this->followingRedirects()
        ->get('/must-be-confirmed')
        ->assertSeeLivewire('auth.passwords.confirm');
});

test('a user must enter a password to confirm it', function () {
    livewire('auth.passwords.confirm')
        ->call('confirm')
        ->assertHasErrors(['password' => 'required']);
});

test('a user must enter their own password to confirm it', function () {
    $user = User::factory()->create([
        'password' => Hash::make('password'),
    ]);

    livewire('auth.passwords.confirm')
        ->set('password', 'not-password')
        ->call('confirm')
        ->assertHasErrors(['password' => 'current_password']);
});

test('a user who confirms their password will get redirected', function () {
    $user = User::factory()->create([
        'password' => Hash::make('password'),
    ]);

    $this->be($user);

    $this->withSession(['url.intended' => '/must-be-confirmed']);

    livewire('auth.passwords.confirm')
        ->set('password', 'password')
        ->call('confirm')
        ->assertRedirect('/must-be-confirmed');
});
