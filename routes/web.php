<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LabController;
use App\Http\Controllers\Admin\MicroController;
use App\Http\Controllers\Admin\PanelController;
use App\Http\Controllers\Admin\UnrecognizedLabController;
use App\Http\Controllers\Admin\UnrecognizedMicroController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\TestController;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Passwords\Confirm;
use App\Livewire\Auth\Passwords\Email;
use App\Livewire\Auth\Passwords\Reset;
use App\Livewire\Auth\Verify;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
    return Inertia::render('Home');
});

//Route::view('/', 'home')->name('home');
Route::view('/terms', 'terms', ['terms' => Str::markdown(file_get_contents(resource_path('markdown/terms.md')))])->name('terms');
Route::view('/policy', 'policy', ['policy' => Str::markdown(file_get_contents(resource_path('markdown/policy.md')))])->name('policy');
Route::get('/test', TestController::class)->name('test');

Route::middleware('guest')->group(function () {
    Route::get('login', Login::class)
        ->name('login');

    //    Route::get('register', Register::class)
    //        ->name('register');
});

Route::get('password/reset', Email::class)
    ->name('password.request');

Route::get('password/reset/{token}', Reset::class)
    ->name('password.reset');

Route::middleware('auth')->group(function () {
    Route::get('email/verify', Verify::class)
        ->middleware('throttle:6,1')
        ->name('verification.notice');

    Route::get('password/confirm', Confirm::class)
        ->name('password.confirm');
});

Route::middleware('auth')->group(function () {
    Route::get('email/verify/{id}/{hash}', EmailVerificationController::class)
        ->middleware('signed')
        ->name('verification.verify');

    Route::view('/profile', 'profile.edit')->name('profile.edit');

    // Admin Routes
    Route::middleware(['isAdmin'])->prefix('admin')->as('admin.')->group(function () {
        Route::get('/', DashboardController::class)->name('dashboard');

        Route::get('unprocessed-labs/edit/{unrecognizedLab}',
            [UnrecognizedLabController::class, 'edit'])->name('unprocessed-labs.edit');
        Route::post('unprocessed-labs/update/{unrecognizedLab}',
            [UnrecognizedLabController::class, 'update'])->name('unprocessed-labs.update');

        Route::get('unprocessed-micros/edit/{unrecognizedMicro}',
            [UnrecognizedMicroController::class, 'edit'])->name('unprocessed-micros.edit');
        Route::post('unprocessed-micros/update/{unrecognizedMicro}',
            [UnrecognizedMicroController::class, 'update'])->name('unprocessed-micros.update');

        Route::resource('panel', PanelController::class)->except(['destroy']);
        Route::resource('micro', MicroController::class)->except(['show', 'destroy']);
        Route::resource('lab', LabController::class)->except(['index', 'show', 'destroy']);
    });

    Route::post('logout', LogoutController::class)
        ->name('logout');
});
