<?php

namespace App\Http\Controllers\Reader;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\ChapterView;
use App\Models\Like;
use App\Services\MediaService;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class ChaptersController extends Controller
{
    public function show(string $chapter): Response
    {
        $media = app(MediaService::class);
        $chapterModel = Chapter::with(['series:id,title,slug,creator_id', 'pages' => fn ($q) => $q->orderBy('order')])->findOrFail($chapter);

        $prev = Chapter::where('series_id', $chapterModel->series_id)
            ->where('number', '<', $chapterModel->number)
            ->orderByDesc('number')
            ->first();

        $next = Chapter::where('series_id', $chapterModel->series_id)
            ->where('number', '>', $chapterModel->number)
            ->orderBy('number')
            ->first();

        $userReaction = null;
        if (Auth::check()) {
            $like = Like::where('user_id', Auth::id())
                ->where('likeable_id', $chapterModel->id)
                ->where('likeable_type', Chapter::class)
                ->first();
            $userReaction = $like ? ($like->is_like ? 'like' : 'dislike') : null;
        }

        $hasRead = false;
        if (Auth::check()) {
            $view = ChapterView::firstOrNew([
                'user_id' => Auth::id(),
                'chapter_id' => $chapterModel->id,
                'year_month' => now()->format('Y-m'),
            ]);

            $view->series_id = $chapterModel->series_id;
            $view->creator_id = $chapterModel->series?->creator_id;
            $view->first_viewed_at = $view->first_viewed_at ?? now();
            $view->last_viewed_at = now();
            $view->save();

            $hasRead = true;
        }

        $data = [
            'id' => $chapterModel->id,
            'title' => $chapterModel->title,
            'number' => $chapterModel->number,
            'status' => $chapterModel->status,
            'published_at' => optional($chapterModel->published_at)->toDateString(),
            'series' => [
                'id' => $chapterModel->series?->id,
                'title' => $chapterModel->series?->title,
            ],
            'pages' => $chapterModel->pages->map(fn ($page) => [
                'id' => $page->id,
                'order' => $page->order,
                'url' => $media->url($page->path),
            ]),
            'prev_id' => $prev?->id,
            'next_id' => $next?->id,
            'likes_count' => $chapterModel->likes_count,
            'dislikes_count' => $chapterModel->dislikes_count,
            'user_reaction' => $userReaction,
            'can_comment' => $hasRead,
        ];

        return Inertia::render('reader/Chapters/Show', [
            'chapter' => $data,
        ]);
    }
}
