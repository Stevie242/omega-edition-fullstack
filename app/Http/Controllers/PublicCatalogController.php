<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Series;
use App\Models\Tag;
use App\Services\MediaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PublicCatalogController extends Controller
{
    public function index(Request $request, MediaService $media): Response
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'type' => ['nullable', 'in:manga,webtoon'],
            'status' => ['nullable', 'in:ongoing,hiatus,completed'],
            'format' => ['nullable', 'in:oneshot,miniseries,series'],
        ]);

        $query = Series::query()
            ->with(['creator:id,name', 'tags:id,name,slug'])
            ->when($filters['search'] ?? null, fn ($q, $value) => $q->where('title', 'like', '%'.$value.'%'))
            ->when($filters['type'] ?? null, fn ($q, $value) => $q->where('type', $value))
            ->when($filters['status'] ?? null, fn ($q, $value) => $q->where('status', $value))
            ->when($filters['format'] ?? null, fn ($q, $value) => $q->where('format', $value));

        $series = $query
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $series->getCollection()->transform(function (Series $serie) use ($media) {
            return [
                'id' => $serie->id,
                'title' => $serie->title,
                'type' => $serie->type,
                'status' => $serie->status,
                'format' => $serie->format,
                'language' => $serie->language,
                'cover_url' => $media->url($serie->cover_path),
                'creator' => $serie->creator?->name,
                'tags' => $serie->tags->map(fn ($tag) => [
                    'name' => $tag->name,
                    'slug' => $tag->slug,
                ]),
            ];
        });

        $tags = Tag::orderBy('name')->limit(30)->get(['name', 'slug']);

        return Inertia::render('public/Catalog/Index', [
            'series' => $series,
            'filters' => $filters,
            'filterOptions' => [
                'types' => ['manga', 'webtoon'],
                'statuses' => ['ongoing', 'hiatus', 'completed'],
                'formats' => ['oneshot', 'miniseries', 'series'],
                'tags' => $tags,
            ],
        ]);
    }

    public function show(string $series, MediaService $media): Response
    {
        $serie = Series::with(['creator:id,name', 'tags:id,name,slug'])
            ->findOrFail($series);

        $freeChapter = $serie->chapters()
            ->orderBy('number')
            ->orderBy('published_at')
            ->first();

        $firstPage = $freeChapter
            ? $freeChapter->pages()->orderBy('order')->first()
            : null;

        $data = [
            'id' => $serie->id,
            'title' => $serie->title,
            'type' => $serie->type,
            'status' => $serie->status,
            'format' => $serie->format,
            'language' => $serie->language,
            'synopsis' => $serie->synopsis,
            'cover_url' => $media->url($serie->cover_path),
            'hero_url' => $media->url($serie->hero_path),
            'creator' => [
                'id' => $serie->creator?->id,
                'name' => $serie->creator?->name,
            ],
            'tags' => $serie->tags->map(fn ($tag) => [
                'name' => $tag->name,
                'slug' => $tag->slug,
            ]),
            'free_chapter_id' => $freeChapter?->id,
            'free_chapter_number' => $freeChapter?->number,
            'free_chapter_preview' => $firstPage ? $media->url($firstPage->path) : null,
        ];

        return Inertia::render('public/Catalog/Show', [
            'series' => $data,
        ]);
    }

    public function read(string $series, string $chapter, MediaService $media)
    {
        $serie = Series::findOrFail($series);

        $freeChapter = $serie->chapters()
            ->orderBy('number')
            ->orderBy('published_at')
            ->first();

        if (!$freeChapter || $freeChapter->id !== $chapter) {
            return redirect()->route('login')->with('warning', 'Connectez-vous pour lire la suite de la série.');
        }

        $chapterModel = Chapter::with(['pages' => fn ($q) => $q->orderBy('order')])->findOrFail($chapter);

        $data = [
            'id' => $chapterModel->id,
            'title' => $chapterModel->title,
            'number' => $chapterModel->number,
            'series' => [
                'id' => $serie->id,
                'title' => $serie->title,
                'cover_url' => $media->url($serie->cover_path),
            ],
            'pages' => $chapterModel->pages->map(fn ($page) => [
                'id' => $page->id,
                'order' => $page->order,
                'url' => $media->url($page->path),
            ]),
        ];

        return Inertia::render('public/Catalog/Chapter', [
            'chapter' => $data,
        ]);
    }
}
