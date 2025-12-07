<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\ChapterPage;
use App\Models\Series;
use App\Services\MediaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
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

    public function store(string $series, Request $request, MediaService $media): RedirectResponse
    {
        $serie = Series::where('id', $series)
            ->where('creator_id', Auth::id())
            ->firstOrFail();

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'number' => ['required', 'integer', 'min:1'],
            'status' => ['required', Rule::in(['draft', 'scheduled', 'published'])],
            'scheduled_for' => ['nullable', 'date', 'required_if:status,scheduled'],
            'pages' => ['array'],
            'pages.*' => ['file', 'image'],
            'orders' => ['array'],
            'orders.*' => ['integer'],
        ]);

        DB::transaction(function () use ($data, $serie, $request, $media) {
            $chapter = Chapter::create([
                'series_id' => $serie->id,
                'title' => $data['title'],
                'number' => $data['number'],
                'status' => $data['status'],
                'scheduled_for' => $data['status'] === 'scheduled' ? $data['scheduled_for'] : null,
                'published_at' => $data['status'] === 'published' ? now() : null,
            ]);

            $files = $request->file('pages', []);
            $orders = $request->input('orders', []);

            foreach ($files as $idx => $file) {
                $order = $orders[$idx] ?? $idx;
                $path = $media->storeChapterPage($file, $serie->slug, $chapter->number, $order);

                ChapterPage::create([
                    'chapter_id' => $chapter->id,
                    'path' => $path,
                    'order' => $order,
                    'size_kb' => (int) round($file->getSize() / 1024),
                ]);
            }

            if (count($files) > 0) {
                $chapter->pages_count = count($files);
                $chapter->save();
            }
        });

        return redirect()
            ->route('creator.series.show', $serie->id)
            ->with('success', 'Chapitre créé.');
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
