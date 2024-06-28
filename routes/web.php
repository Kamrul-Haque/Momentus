<?php

use App\Enums\Role;
use App\Http\Controllers;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
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

require __DIR__ . '/auth.php';

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware('precognitive')->group(function () {
    Route::get('/dashboard', Controllers\DashboardController::class)->middleware(['auth', 'verified'])
         ->name('dashboard');

    Route::get('users/{user}/image', [Controllers\UserController::class, 'getImage'])->name('users.image');

    Route::middleware('auth')->group(function () {
        Route::get('/profile', [Controllers\ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [Controllers\ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::resource('/events', Controllers\EventController::class);
    });

    Route::middleware('permit:' . Role::ADMIN->value)->group(function () {
        Route::resource('users', Controllers\UserController::class);
        Route::put('users/{user}/restore', [Controllers\UserController::class, 'restore'])->name('users.restore');
        Route::delete('users/{user}/delete', [Controllers\UserController::class, 'delete'])->name('users.delete');
    });

    Route::middleware('permit:' . Role::SUPER_ADMIN->value)->group(function () {
        Route::resource('settings', Controllers\SettingController::class)->only('edit', 'update');
    });
});
