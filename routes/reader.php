<?php

use App\Http\Controllers\Reader\ChaptersController;
use App\Http\Controllers\Reader\DashboardController;
use App\Http\Controllers\Reader\ChapterViewController;
use App\Http\Controllers\Reader\ProfileController;
use App\Http\Controllers\Reader\SeriesController;
use App\Http\Controllers\Reader\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'role:reader', 'reader.onboarded'])
    ->prefix('reader')
    ->as('reader.')
    ->group(function (): void {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('series', SeriesController::class)->only(['index', 'show']);
        Route::get('chapters/{chapter}', [ChaptersController::class, 'show'])->name('chapters.show');
        Route::post('chapters/{chapter}/view', [ChapterViewController::class, 'store'])->name('chapters.view');

        Route::get('favorites', [ProfileController::class, 'favorites'])->name('favorites');
        Route::get('history', [ProfileController::class, 'history'])->name('history');

        Route::get('subscription', [SubscriptionController::class, 'index'])->name('subscription');
        Route::post('subscription/choose', [SubscriptionController::class, 'choose'])->name('subscription.choose');

        Route::prefix('settings')->as('settings.')->group(function (): void {
            Route::get('profile', [\App\Http\Controllers\Reader\SettingsController::class, 'profile'])->name('profile');
            Route::match(['put', 'post'], 'profile', [\App\Http\Controllers\Reader\SettingsController::class, 'updateProfile'])->name('profile.update');
            Route::get('appearance', [\App\Http\Controllers\Reader\SettingsController::class, 'appearance'])->name('appearance');
            Route::get('password', [\App\Http\Controllers\Reader\SettingsController::class, 'password'])->name('password');
            Route::get('two-factor', [\App\Http\Controllers\Reader\SettingsController::class, 'twoFactor'])->name('two-factor');
        });
    });
