<?php

use App\Http\Controllers\Reader\ChaptersController;
use App\Http\Controllers\Reader\DashboardController;
use App\Http\Controllers\Reader\ChapterViewController;
use App\Http\Controllers\Reader\ProfileController;
use App\Http\Controllers\Reader\SeriesController;
use App\Http\Controllers\Reader\SubscriptionController;
use App\Http\Controllers\Reader\CreatorController;
use App\Http\Controllers\Reader\SupportController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'role:reader', 'reader.onboarded'])
    ->prefix('reader')
    ->as('reader.')
    ->group(function (): void {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('series', SeriesController::class)->only(['index', 'show']);
        Route::get('creators/{creator}', [CreatorController::class, 'show'])->name('creators.show');
        Route::post('series/{series}/favorite', [\App\Http\Controllers\Reader\SeriesActionController::class, 'favorite'])->name('series.favorite');
        Route::delete('series/{series}/favorite', [\App\Http\Controllers\Reader\SeriesActionController::class, 'unfavorite'])->name('series.unfavorite');
        Route::post('series/{series}/like', [\App\Http\Controllers\Reader\SeriesActionController::class, 'like'])->name('series.like');
        Route::post('series/{series}/dislike', [\App\Http\Controllers\Reader\SeriesActionController::class, 'dislike'])->name('series.dislike');
        Route::get('chapters/{chapter}', [ChaptersController::class, 'show'])->name('chapters.show');
        Route::post('chapters/{chapter}/view', [ChapterViewController::class, 'store'])->name('chapters.view');
        Route::post('chapters/{chapter}/like', [\App\Http\Controllers\Reader\ChapterActionController::class, 'like'])->name('chapters.like');
        Route::post('chapters/{chapter}/dislike', [\App\Http\Controllers\Reader\ChapterActionController::class, 'dislike'])->name('chapters.dislike');

        Route::get('favorites', [ProfileController::class, 'favorites'])->name('favorites');
        Route::get('history', [ProfileController::class, 'history'])->name('history');

        Route::get('subscription', [SubscriptionController::class, 'index'])->name('subscription');
        Route::post('subscription/choose', [SubscriptionController::class, 'choose'])->name('subscription.choose');
        Route::get('support', [SupportController::class, 'index'])->name('support');
        Route::post('support', [SupportController::class, 'store'])->name('support.store');

        Route::prefix('settings')->as('settings.')->group(function (): void {
            Route::get('profile', [\App\Http\Controllers\Reader\SettingsController::class, 'profile'])->name('profile');
            Route::match(['put', 'post'], 'profile', [\App\Http\Controllers\Reader\SettingsController::class, 'updateProfile'])->name('profile.update');
            Route::get('appearance', [\App\Http\Controllers\Reader\SettingsController::class, 'appearance'])->name('appearance');
            Route::get('password', [\App\Http\Controllers\Reader\SettingsController::class, 'password'])->name('password');
            Route::get('two-factor', [\App\Http\Controllers\Reader\SettingsController::class, 'twoFactor'])->name('two-factor');
        });
    });
