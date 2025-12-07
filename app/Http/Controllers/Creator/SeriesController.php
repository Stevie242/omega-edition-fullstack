<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class SeriesController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('creator/Series/Index');
    }

    public function create(): Response
    {
        return Inertia::render('creator/Series/Create');
    }

    public function store(): RedirectResponse
    {
        // Mock: replace with real creation logic
        return redirect()->route('creator.series.index');
    }

    public function show(string $series): Response
    {
        return Inertia::render('creator/Series/Show', [
            'seriesId' => $series,
        ]);
    }

    public function edit(string $series): Response
    {
        return Inertia::render('creator/Series/Edit', [
            'seriesId' => $series,
        ]);
    }

    public function update(string $series): RedirectResponse
    {
        // Mock: replace with real update logic
        return redirect()->route('creator.series.edit', $series);
    }
}
