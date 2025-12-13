<?php

namespace App\Http\Controllers\Reader;

use App\Http\Controllers\Controller;
use App\Models\Series;
use App\Models\Tag;
use App\Services\MediaService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SeriesController extends Controller
{
    public function index(Request $request, MediaService $media): Response
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'type' => ['nullable', 'in:manga,webtoon'],
            'status' => ['nullable', 'in:ongoing,hiatus,completed'],
            'format' => ['nullable', 'in:oneshot,miniseries,series'],
            'tag' => ['nullable', 'string', 'max:100'],
        ]);

        $query = Series::query()
            ->with(['creator:id,name', 'tags:id,name,slug'])
            ->when($filters['search'] ?? null, fn ($q, $value) => $q->where('title', 'like', '%'.$value.'%'))
            ->when($filters['type'] ?? null, fn ($q, $value) => $q->where('type', $value))
            ->when($filters['status'] ?? null, fn ($q, $value) => $q->where('status', $value))
            ->when($filters['format'] ?? null, fn ($q, $value) => $q->where('format', $value))
            ->when($filters['tag'] ?? null, fn ($q, $value) => $q->whereHas('tags', fn ($t) => $t->where('slug', $value)));

        $series = $query
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $series->getCollection()->transform(function (Series $serie) use ($media) {
            return [
                'id' => $serie->id,
                'title' => $serie->title,
                'slug' => $serie->slug,
                'type' => $serie->type,
                'status' => $serie->status,
                'format' => $serie->format,
                'language' => $serie->language,
                'synopsis' => $serie->synopsis,
                'rating' => $serie->rating,
                'cover_url' => $media->url($serie->cover_path),
                'creator' => [
                    'id' => $serie->creator?->id,
                    'name' => $serie->creator?->name,
                ],
                'tags' => $serie->tags->map(fn ($tag) => [
                    'name' => $tag->name,
                    'slug' => $tag->slug,
                ]),
            ];
        });

        $tags = Tag::orderBy('name')->limit(30)->get(['name', 'slug']);

        return Inertia::render('reader/Series/Index', [
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

    public function show(string $series): Response
    {
        return Inertia::render('reader/Series/Show', [
            'seriesId' => $series,
        ]);
    }
}
