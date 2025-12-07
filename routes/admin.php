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
    ->group(function (): void {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('catalog', CatalogController::class)->only(['index', 'show']);
        Route::resource('users', UsersController::class)->only(['index', 'show', 'edit', 'update']);
        Route::resource('creators', CreatorsController::class)->only(['index', 'show']);

        Route::get('moderation', [ModerationController::class, 'index'])->name('moderation');
        Route::get('monitoring', [MonitoringController::class, 'index'])->name('monitoring');
    });
