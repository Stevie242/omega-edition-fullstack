<?php

namespace App\Http\Controllers\Reader;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\ChapterView;
use App\Models\ReaderFavorite;
use App\Services\MediaService;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    public function favorites(MediaService $media): Response
    {
        $userId = Auth::id();

        $favorites = ReaderFavorite::query()
            ->where('user_id', $userId)
            ->with(['series.creator:id,name', 'series.tags:id,name,slug'])
            ->get();

        $seriesIds = $favorites->pluck('series_id')->filter()->all();

        $views = ChapterView::where('user_id', $userId)
            ->whereIn('series_id', $seriesIds)
            ->orderByDesc('last_viewed_at')
            ->get()
            ->unique('series_id')
            ->keyBy('series_id');
        $views->load('chapter:id,number,series_id,title');

        $firstChapters = Chapter::select('id', 'series_id', 'number', 'title')
            ->whereIn('series_id', $seriesIds)
            ->orderBy('number')
            ->orderBy('published_at')
            ->get()
            ->groupBy('series_id')
            ->map(fn ($items) => $items->first());

        $items = $favorites
            ->filter(fn ($fav) => $fav->series)
            ->map(function (ReaderFavorite $fav) use ($media, $views, $firstChapters) {
                $serie = $fav->series;
                $view = $views->get($serie->id);
                $first = $firstChapters->get($serie->id);

                return [
                    'id' => $serie->id,
                    'title' => $serie->title,
                    'type' => $serie->type,
                    'status' => $serie->status,
                    'format' => $serie->format,
                    'language' => $serie->language,
                    'cover_url' => $media->url($serie->cover_path),
                    'creator' => [
                        'id' => $serie->creator?->id,
                        'name' => $serie->creator?->name,
                    ],
                    'tags' => $serie->tags->map(fn ($tag) => [
                        'name' => $tag->name,
                        'slug' => $tag->slug,
                    ]),
                    'last_read_chapter_id' => $view?->chapter_id,
                    'last_read_chapter_number' => $view?->chapter?->number,
                    'start_chapter_id' => $view?->chapter_id ?? $first?->id,
                    'start_chapter_number' => $view?->chapter?->number ?? $first?->number,
                ];
            })
            ->values();

        return Inertia::render('reader/Favorites', [
            'favorites' => $items,
        ]);
    }

    public function history(MediaService $media): Response
    {
        $userId = Auth::id();

        $views = ChapterView::where('user_id', $userId)
            ->orderByDesc('last_viewed_at')
            ->with(['series:id,title,cover_path,type,status,format,language,creator_id', 'chapter:id,number,title,series_id'])
            ->get()
            ->unique('series_id')
            ->take(30);

        $seriesIds = $views->pluck('series_id')->filter()->all();

        $firstChapters = Chapter::select('id', 'series_id', 'number', 'title')
            ->whereIn('series_id', $seriesIds)
            ->orderBy('number')
            ->orderBy('published_at')
            ->get()
            ->groupBy('series_id')
            ->map(fn ($items) => $items->first());

        $items = $views
            ->filter(fn ($view) => $view->series)
            ->map(function (ChapterView $view) use ($media, $firstChapters) {
                $serie = $view->series;
                $first = $firstChapters->get($serie->id);

                return [
                    'id' => $serie->id,
                    'title' => $serie->title,
                    'type' => $serie->type,
                    'status' => $serie->status,
                    'format' => $serie->format,
                    'language' => $serie->language,
                    'cover_url' => $media->url($serie->cover_path),
                    'last_read_at' => optional($view->last_viewed_at)->toDateTimeString(),
                    'last_chapter_id' => $view->chapter?->id,
                    'last_chapter_number' => $view->chapter?->number,
                    'start_chapter_id' => $view->chapter?->id ?? $first?->id,
                    'start_chapter_number' => $view->chapter?->number ?? $first?->number,
                ];
            })
            ->values();

        return Inertia::render('reader/History', [
            'history' => $items,
        ]);
    }
}
