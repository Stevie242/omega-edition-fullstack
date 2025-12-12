<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

if (Features::enabled(Features::registration())) {
    Route::middleware(['guest'])->group(function (): void {
        Route::get('/register/reader', fn () => Inertia::render('auth/RegisterReader'))->name('register.reader');
        Route::get('/register/creator', fn () => Inertia::render('auth/RegisterCreator'))->name('register.creator');
    });
}

Route::middleware(['auth', 'role:creator'])->group(function (): void {
    Route::get('/onboarding/creator', [\App\Http\Controllers\Creator\OnboardingController::class, 'show'])->name('creator.onboarding');
    Route::post('/onboarding/creator', [\App\Http\Controllers\Creator\OnboardingController::class, 'store'])->name('creator.onboarding.store');
});

require __DIR__.'/settings.php';
require __DIR__.'/creator.php';
require __DIR__.'/reader.php';
require __DIR__.'/admin.php';
