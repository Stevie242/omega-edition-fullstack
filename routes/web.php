<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\PublicCatalogController;

if (Features::enabled(Features::registration())) {
    Route::middleware(['guest'])->group(function (): void {
        Route::get('/register/reader', fn () => Inertia::render('auth/RegisterReader'))->name('register.reader');
        Route::get('/register/creator', fn () => Inertia::render('auth/RegisterCreator'))->name('register.creator');
    });
}

Route::get('/', [LandingController::class, 'home'])->name('home');
Route::get('/about', [LandingController::class, 'about'])->name('about');
Route::get('/privacy', [LandingController::class, 'privacy'])->name('privacy');
Route::get('/terms', [LandingController::class, 'terms'])->name('terms');

Route::get('/catalog', [PublicCatalogController::class, 'index'])->name('catalog.index');
Route::get('/catalog/{series}', [PublicCatalogController::class, 'show'])->name('catalog.show');
Route::get('/catalog/{series}/chapters/{chapter}', [PublicCatalogController::class, 'read'])->name('catalog.read');

Route::middleware(['auth', 'role:creator'])->group(function (): void {
    Route::get('/onboarding/creator', [\App\Http\Controllers\Creator\OnboardingController::class, 'show'])->name('creator.onboarding');
    Route::post('/onboarding/creator', [\App\Http\Controllers\Creator\OnboardingController::class, 'store'])->name('creator.onboarding.store');
});

Route::middleware(['auth', 'role:reader'])->group(function (): void {
    Route::get('/onboarding/reader', [\App\Http\Controllers\Reader\OnboardingController::class, 'show'])->name('reader.onboarding');
    Route::post('/onboarding/reader', [\App\Http\Controllers\Reader\OnboardingController::class, 'store'])->name('reader.onboarding.store');
});

require __DIR__.'/settings.php';
require __DIR__.'/creator.php';
require __DIR__.'/reader.php';
require __DIR__.'/admin.php';
