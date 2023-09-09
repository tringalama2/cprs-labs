<?php

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;

use function Pest\Livewire\livewire;

test('registration page contains livewire component', function () {
    $this->get(route('register'))
        ->assertSuccessful()
        ->assertSeeLivewire('auth.register');
});

test('is redirected if already logged in', function () {
    $user = User::factory()->create();

    $this->be($user);

    $this->get(route('register'))
        ->assertRedirect(RouteServiceProvider::HOME);
});

test('a user can register', function () {
    Event::fake();

    livewire('auth.register')
        ->set('name', 'Tall Stack')
        ->set('email', 'tallstack@example.com')
        ->set('password', 'password')
        ->set('passwordConfirmation', 'password')
        ->call('register')
        ->assertRedirect(route('home'));

    expect(User::whereEmail('tallstack@example.com')->exists())->toBeTrue();
    expect(Auth::user()->email)->toEqual('tallstack@example.com');

    Event::assertDispatched(Registered::class);
});

test('name is required', function () {
    livewire('auth.register')
        ->set('name', '')
        ->call('register')
        ->assertHasErrors(['name' => 'required']);
});

test('email is required', function () {
    livewire('auth.register')
        ->set('email', '')
        ->call('register')
        ->assertHasErrors(['email' => 'required']);
});

test('email is valid email', function () {
    livewire('auth.register')
        ->set('email', 'tallstack')
        ->call('register')
        ->assertHasErrors(['email' => 'email']);
});

test('email hasnt been taken already', function () {
    User::factory()->create(['email' => 'tallstack@example.com']);

    livewire('auth.register')
        ->set('email', 'tallstack@example.com')
        ->call('register')
        ->assertHasErrors(['email' => 'unique']);
});

test('see email hasnt already been taken validation message as user types', function () {
    User::factory()->create(['email' => 'tallstack@example.com']);

    livewire('auth.register')
        ->set('email', 'smallstack@gmail.com')
        ->assertHasNoErrors()
        ->set('email', 'tallstack@example.com')
        ->call('register')
        ->assertHasErrors(['email' => 'unique']);
});

test('password is required', function () {
    livewire('auth.register')
        ->set('password', '')
        ->set('passwordConfirmation', 'password')
        ->call('register')
        ->assertHasErrors(['password' => 'required']);
});

test('password is minimum of eight characters', function () {
    livewire('auth.register')
        ->set('password', 'secret')
        ->set('passwordConfirmation', 'secret')
        ->call('register')
        ->assertHasErrors(['password' => 'min']);
});

test('password matches password confirmation', function () {
    livewire('auth.register')
        ->set('email', 'tallstack@example.com')
        ->set('password', 'password')
        ->set('passwordConfirmation', 'not-password')
        ->call('register')
        ->assertHasErrors(['password' => 'same']);
});
