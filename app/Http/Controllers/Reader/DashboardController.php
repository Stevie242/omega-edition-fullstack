<?php

namespace App\Http\Controllers\Reader;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\ChapterView;
use App\Models\ReaderFavorite;
use App\Models\Series;
use App\Services\MediaService;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(MediaService $media): Response
    {
        $userId = Auth::id();

        // Reprendre : dernières séries vues
        $views = ChapterView::where('user_id', $userId)
            ->with([
                'series:id,title,cover_path,type,status,format,language,creator_id',
                'chapter:id,number,title,series_id',
            ])
            ->orderByDesc('last_viewed_at')
            ->get()
            ->unique('series_id')
            ->take(6);

        $seriesIds = $views->pluck('series_id')->filter()->all();

        $firstChapters = Chapter::select('id', 'series_id', 'number', 'title')
            ->whereIn('series_id', $seriesIds)
            ->orderBy('number')
            ->orderBy('published_at')
            ->get()
            ->groupBy('series_id')
            ->map(fn ($items) => $items->first());

        $continue = $views
            ->filter(fn ($view) => $view->series)
            ->map(function (ChapterView $view) use ($media, $firstChapters) {
                $serie = $view->series;
                $first = $firstChapters->get($serie->id);

                return [
                    'id' => $serie->id,
                    'title' => $serie->title,
                    'cover_url' => $media->url($serie->cover_path),
                    'type' => $serie->type,
                    'status' => $serie->status,
                    'format' => $serie->format,
                    'language' => $serie->language,
                    'last_chapter_id' => $view->chapter?->id,
                    'last_chapter_number' => $view->chapter?->number,
                    'start_chapter_id' => $view->chapter?->id ?? $first?->id,
                    'start_chapter_number' => $view->chapter?->number ?? $first?->number,
                ];
            })
            ->values();

        // Favoris récents
        $favorites = ReaderFavorite::where('user_id', $userId)
            ->latest()
            ->with(['series:id,title,cover_path,type,status,format,language'])
            ->take(6)
            ->get()
            ->filter(fn ($fav) => $fav->series)
            ->map(fn (ReaderFavorite $fav) => [
                'id' => $fav->series->id,
                'title' => $fav->series->title,
                'cover_url' => $media->url($fav->series->cover_path),
                'type' => $fav->series->type,
                'status' => $fav->series->status,
                'format' => $fav->series->format,
                'language' => $fav->series->language,
            ])
            ->values();

        // Chapitres récents
        $latestChapters = Chapter::with(['series:id,title,cover_path'])
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->limit(8)
            ->get()
            ->map(function (Chapter $chapter) use ($media) {
                return [
                    'id' => $chapter->id,
                    'title' => $chapter->title,
                    'number' => $chapter->number,
                    'published_at' => optional($chapter->published_at)->toDateString(),
                    'series' => [
                        'id' => $chapter->series?->id,
                        'title' => $chapter->series?->title,
                        'cover_url' => $media->url($chapter->series?->cover_path),
                    ],
                ];
            });

        // Tendances simples : plus likées
        $trending = Series::query()
            ->orderByDesc('likes_count')
            ->limit(6)
            ->get(['id', 'title', 'cover_path', 'type', 'status', 'format', 'language', 'likes_count'])
            ->map(fn (Series $serie) => [
                'id' => $serie->id,
                'title' => $serie->title,
                'cover_url' => $media->url($serie->cover_path),
                'type' => $serie->type,
                'status' => $serie->status,
                'format' => $serie->format,
                'language' => $serie->language,
                'likes_count' => $serie->likes_count,
            ]);

        return Inertia::render('reader/Dashboard', [
            'continue' => $continue,
            'favorites' => $favorites,
            'latestChapters' => $latestChapters,
            'trending' => $trending,
        ]);
    }
}
