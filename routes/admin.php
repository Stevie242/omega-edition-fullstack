<?php

use App\Http\Controllers\Admin\CatalogController;
use App\Http\Controllers\Admin\CreatorsController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ModerationController;
use App\Http\Controllers\Admin\MonitoringController;
use App\Http\Controllers\Admin\UsersController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])
    ->prefix('admin')
    ->as('admin.')
    ->middleware('role:admin')
    ->group(function (): void {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('catalog', CatalogController::class)->only(['index', 'show']);
        Route::resource('users', UsersController::class)->only(['index', 'show', 'edit', 'update']);
        Route::resource('creators', CreatorsController::class)->only(['index', 'show']);

        Route::get('moderation', [ModerationController::class, 'index'])->name('moderation');
        Route::get('monitoring', [MonitoringController::class, 'index'])->name('monitoring');

        Route::prefix('settings')->as('settings.')->group(function (): void {
            Route::get('profile', [\App\Http\Controllers\Admin\SettingsController::class, 'profile'])->name('profile');
            Route::get('appearance', [\App\Http\Controllers\Admin\SettingsController::class, 'appearance'])->name('appearance');
            Route::get('password', [\App\Http\Controllers\Admin\SettingsController::class, 'password'])->name('password');
            Route::get('two-factor', [\App\Http\Controllers\Admin\SettingsController::class, 'twoFactor'])->name('two-factor');
            Route::get('security', [\App\Http\Controllers\Admin\SettingsController::class, 'security'])->name('security');
        });
    });
