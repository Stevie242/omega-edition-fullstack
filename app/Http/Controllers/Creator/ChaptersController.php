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
        $chapter = Chapter::with(['pages' => fn ($q) => $q->orderBy('order')])
            ->with('series')
            ->findOrFail($chapter);

        abort_if($chapter->series->creator_id !== Auth::id(), 403);

        $disk = app('filesystem')->disk(config('filesystems.images_disk', 'images'));

        return Inertia::render('creator/Chapters/Edit', [
            'seriesId' => $chapter->series_id,
            'chapter' => [
                'id' => $chapter->id,
                'title' => $chapter->title,
                'number' => $chapter->number,
                'status' => $chapter->status,
                'scheduled_for' => $chapter->scheduled_for?->format('Y-m-d\TH:i'),
                'published_at' => $chapter->published_at?->toDateTimeString(),
                'pages' => $chapter->pages->map(fn (ChapterPage $page) => [
                    'id' => $page->id,
                    'order' => $page->order,
                    'url' => url($disk->url($page->path)),
                    'size_kb' => $page->size_kb,
                ]),
            ],
        ]);
    }

    public function update(string $chapter, Request $request, MediaService $media): RedirectResponse
    {
        $chapter = Chapter::with('series', 'pages')->findOrFail($chapter);
        abort_if($chapter->series->creator_id !== Auth::id(), 403);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'number' => ['required', 'integer', 'min:1'],
            'status' => ['required', Rule::in(['draft', 'scheduled', 'published'])],
            'scheduled_for' => ['nullable', 'date', 'required_if:status,scheduled'],
            'pages' => ['array'],
            'pages.*' => ['file', 'image'],
            'orders' => ['array'],
            'orders.*' => ['integer'],
            'removed' => ['array'],
            'removed.*' => ['string'],
            'existing_orders' => ['array'],
            'existing_orders.*' => ['integer'],
        ]);

        DB::transaction(function () use ($data, $chapter, $request, $media) {
            $chapter->fill([
                'title' => $data['title'],
                'number' => $data['number'],
                'status' => $data['status'],
                'scheduled_for' => $data['status'] === 'scheduled' ? $data['scheduled_for'] : null,
                'published_at' => $data['status'] === 'published' ? ($chapter->published_at ?? now()) : null,
            ]);

            $newFiles = $request->file('pages', []);
            $newOrders = $request->input('orders', []);
            $existingOrders = $request->input('existing_orders', []);
            $removed = $request->input('removed', []);

            if (!empty($removed)) {
                foreach ($chapter->pages as $page) {
                    if (in_array($page->id, $removed, true)) {
                        $media->delete($page->path);
                        $page->delete();
                    }
                }
                // refresh pages relation after deletions
                $chapter->load('pages');
            }

            if (count($newFiles) > 0) {
                foreach ($chapter->pages as $page) {
                    $media->delete($page->path);
                    $page->delete();
                }

                foreach ($newFiles as $idx => $file) {
                    $order = $newOrders[$idx] ?? $idx;
                    $path = $media->storeChapterPage($file, $chapter->series->slug, $chapter->number, $order);

                    ChapterPage::create([
                        'chapter_id' => $chapter->id,
                        'path' => $path,
                        'order' => $order,
                        'size_kb' => (int) round($file->getSize() / 1024),
                    ]);
                }

                $chapter->pages_count = count($newFiles);
            } elseif (!empty($existingOrders)) {
                foreach ($chapter->pages as $page) {
                    if (isset($existingOrders[$page->id])) {
                        $page->order = $existingOrders[$page->id];
                        $page->save();
                    }
                }
                $chapter->pages_count = $chapter->pages()->count();
            } else {
                $chapter->pages_count = $chapter->pages()->count();
            }

            $chapter->save();
        });

        return redirect()
            ->route('creator.chapters.edit', $chapter->id)
            ->with('success', 'Chapitre mis à jour.');
    }
}
