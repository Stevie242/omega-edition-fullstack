<?php

namespace App\Http\Controllers;

use App\Models\Series;
use App\Models\Chapter;
use App\Models\ProfileCreator;
use App\Models\ReaderProfile;
use App\Models\SubscriptionPlan;
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
        $seriesCount = Series::count();
        $chaptersCount = Chapter::where('status', 'published')->count();
        $stats = [
            ['label' => 'Séries', 'value' => $seriesCount.'+'],
            ['label' => 'Chapitres', 'value' => $chaptersCount.'+'],
            ['label' => 'Lecteurs', 'value' => ReaderProfile::count().'+'],
            ['label' => 'Créateurs', 'value' => ProfileCreator::count().'+'],
        ];

        $plans = SubscriptionPlan::query()
            ->where('is_active', true)
            ->orderBy('price_xaf')
            ->get(['id', 'name', 'slug', 'price_xaf', 'period_label', 'description', 'perks', 'is_default'])
            ->map(fn (SubscriptionPlan $plan) => [
                'id' => $plan->id,
                'name' => $plan->name,
                'slug' => $plan->slug,
                'price_xaf' => $plan->price_xaf,
                'period_label' => $plan->period_label,
                'description' => $plan->description,
                'perks' => $plan->perks,
                'is_default' => $plan->is_default,
            ]);

        return Inertia::render('public/About', [
            'stats' => $stats,
            'plans' => $plans,
        ]);
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
