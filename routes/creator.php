<?php

use App\Http\Controllers\Creator\AnalyticsController;
use App\Http\Controllers\Creator\ChaptersController;
use App\Http\Controllers\Creator\DashboardController;
use App\Http\Controllers\Creator\PayoutsController;
use App\Http\Controllers\Creator\TaxProfileController;
use App\Http\Controllers\Creator\SeriesController;
use App\Http\Controllers\Creator\SupportController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'role:creator', 'creator.onboarded'])
    ->prefix('creator')
    ->as('creator.')
    ->group(function (): void {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('series', SeriesController::class)->except(['destroy']);

        Route::resource('series.chapters', ChaptersController::class)
            ->shallow()
            ->except(['destroy']);

        Route::get('analytics', [AnalyticsController::class, 'index'])->name('analytics');
        Route::get('payouts', [PayoutsController::class, 'index'])->name('payouts');
        Route::put('payouts/tax-profile', [TaxProfileController::class, 'update'])->name('payouts.tax-profile');
        Route::get('support', [SupportController::class, 'index'])->name('support');
        Route::post('support', [SupportController::class, 'store'])->name('support.store');

        Route::prefix('settings')->as('settings.')->group(function (): void {
            Route::get('profile', [\App\Http\Controllers\Creator\SettingsController::class, 'profile'])->name('profile');
            Route::match(['put', 'post'], 'profile', [\App\Http\Controllers\Creator\SettingsController::class, 'updateProfile'])->name('profile.update');
            Route::get('appearance', [\App\Http\Controllers\Creator\SettingsController::class, 'appearance'])->name('appearance');
            Route::get('tax', [\App\Http\Controllers\Creator\SettingsController::class, 'tax'])->name('tax');
            Route::get('password', [\App\Http\Controllers\Creator\SettingsController::class, 'password'])->name('password');
            Route::get('two-factor', [\App\Http\Controllers\Creator\SettingsController::class, 'twoFactor'])->name('two-factor');
        });
    });
