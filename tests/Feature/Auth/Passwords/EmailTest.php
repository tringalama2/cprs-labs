<?php

use App\Models\User;

use function Pest\Livewire\livewire;

test('can view password request page', function () {
    $this->get(route('password.request'))
        ->assertSuccessful()
        ->assertSeeLivewire('auth.passwords.email');
});

test('a user must enter an email address', function () {
    livewire('auth.passwords.email')
        ->call('sendResetPasswordLink')
        ->assertHasErrors(['email' => 'required']);
});

test('a user must enter a valid email address', function () {
    livewire('auth.passwords.email')
        ->set('email', 'email')
        ->call('sendResetPasswordLink')
        ->assertHasErrors(['email' => 'email']);
});

test('a user who enters a valid email address will get sent an email', function () {
    $user = User::factory()->create();

    livewire('auth.passwords.email')
        ->set('email', $user->email)
        ->call('sendResetPasswordLink')
        ->assertNotSet('emailSentMessage', false);

    $this->assertDatabaseHas('password_reset_tokens', [
        'email' => $user->email,
    ]);
});
