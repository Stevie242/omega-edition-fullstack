<?php

use App\Http\Controllers\Reader\ChaptersController;
use App\Http\Controllers\Reader\DashboardController;
use App\Http\Controllers\Reader\ProfileController;
use App\Http\Controllers\Reader\SeriesController;
use App\Http\Controllers\Reader\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])
    ->prefix('reader')
    ->as('reader.')
    ->middleware('role:reader,creator,admin')
    ->group(function (): void {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('series', SeriesController::class)->only(['index', 'show']);
        Route::get('chapters/{chapter}', [ChaptersController::class, 'show'])->name('chapters.show');

        Route::get('favorites', [ProfileController::class, 'favorites'])->name('favorites');
        Route::get('history', [ProfileController::class, 'history'])->name('history');

        Route::get('subscription', [SubscriptionController::class, 'index'])->name('subscription');
    });
