<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ChaptersController extends Controller
{
    public function index(string $series): Response
    {
        return Inertia::render('creator/Chapters/Index', [
            'seriesId' => $series,
        ]);
    }

    public function create(string $series): Response
    {
        return Inertia::render('creator/Chapters/Create', [
            'seriesId' => $series,
        ]);
    }

    public function store(string $series): RedirectResponse
    {
        // Mock: replace with real creation logic
        return redirect()->route('creator.series.chapters.index', $series);
    }

    public function show(string $chapter): Response
    {
        return Inertia::render('creator/Chapters/Show', [
            'chapterId' => $chapter,
        ]);
    }

    public function edit(string $chapter): Response
    {
        return Inertia::render('creator/Chapters/Edit', [
            'chapterId' => $chapter,
        ]);
    }

    public function update(string $chapter): RedirectResponse
    {
        // Mock: replace with real update logic
        return redirect()->route('creator.chapters.edit', $chapter);
    }
}
