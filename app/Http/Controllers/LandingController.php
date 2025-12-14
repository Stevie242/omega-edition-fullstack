<?php

namespace App\Http\Controllers;

use App\Models\Series;
use App\Models\Chapter;
use App\Models\ProfileCreator;
use App\Models\ReaderProfile;
use App\Services\MediaService;
use Inertia\Inertia;
use Inertia\Response;

class LandingController extends Controller
{
    public function home(MediaService $media): Response
    {
        $hero = Series::query()
            ->latest()
            ->limit(6)
            ->get(['id', 'title', 'cover_path'])
            ->map(fn (Series $s) => [
                'id' => $s->id,
                'title' => $s->title,
                'cover_url' => $media->url($s->cover_path),
            ]);

        $freeSeries = Series::query()
            ->with(['chapters' => fn ($q) => $q->orderBy('number')->orderBy('published_at')->limit(1)])
            ->latest()
            ->limit(9)
            ->get(['id', 'title', 'cover_path'])
            ->map(fn (Series $s) => [
                'id' => $s->id,
                'title' => $s->title,
                'tag' => '1er chapitre offert',
                'cover_url' => $media->url($s->cover_path),
                'free_chapter_id' => $s->chapters->first()?->id,
            ]);

        $seriesCount = Series::count();
        $chaptersCount = Chapter::where('status', 'published')->count();
        $stats = [
            ['label' => 'Séries publiées', 'value' => $seriesCount.'+'],
            ['label' => 'Chapitres publiés', 'value' => $chaptersCount.'+'],
            ['label' => 'Lecteurs actifs', 'value' => ReaderProfile::count().'+'],
            ['label' => 'Créateurs partenaires', 'value' => ProfileCreator::count().'+'],
        ];

        return Inertia::render('public/Home', [
            'hero' => $hero,
            'freeSeries' => $freeSeries,
            'stats' => $stats,
        ]);
    }

    public function about(): Response
    {
        return Inertia::render('public/About');
    }

    public function privacy(): Response
    {
        return Inertia::render('public/Privacy');
    }

    public function terms(): Response
    {
        return Inertia::render('public/Terms');
    }
}
