<?php

use App\Http\Controllers\Creator\AnalyticsController;
use App\Http\Controllers\Creator\ChaptersController;
use App\Http\Controllers\Creator\DashboardController;
use App\Http\Controllers\Creator\PayoutsController;
use App\Http\Controllers\Creator\SeriesController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])
    ->prefix('creator')
    ->as('creator.')
    ->middleware('role:creator,admin')
    ->group(function (): void {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('series', SeriesController::class)->except(['destroy']);

        Route::resource('series.chapters', ChaptersController::class)
            ->shallow()
            ->except(['destroy']);

        Route::get('analytics', [AnalyticsController::class, 'index'])->name('analytics');
        Route::get('payouts', [PayoutsController::class, 'index'])->name('payouts');
    });
