<?php

namespace App\Http\Controllers\Reader;

use App\Http\Controllers\Controller;
use App\Models\Series;
use App\Models\User;
use App\Services\MediaService;
use Inertia\Inertia;
use Inertia\Response;

class CreatorController extends Controller
{
    public function show(User $creator, MediaService $media): Response
    {
        if (!$creator->isCreator()) {
            abort(404);
        }

        $creator->load('creatorProfile');
        $profile = $creator->creatorProfile;

        $series = Series::query()
            ->where('creator_id', $creator->id)
            ->with(['tags:id,name,slug'])
            ->latest()
            ->get()
            ->map(function (Series $serie) use ($media) {
                return [
                    'id' => $serie->id,
                    'title' => $serie->title,
                    'cover_url' => $media->url($serie->cover_path),
                    'type' => $serie->type,
                    'status' => $serie->status,
                    'format' => $serie->format,
                    'language' => $serie->language,
                    'likes_count' => $serie->likes_count,
                    'tags' => $serie->tags->map(fn ($tag) => [
                        'name' => $tag->name,
                        'slug' => $tag->slug,
                    ]),
                ];
            });

        $creatorData = [
            'id' => $creator->id,
            'name' => $creator->name,
            'display_name' => $profile->display_name ?? $creator->name,
            'headline' => $profile->headline,
            'bio' => $profile->bio,
            'languages' => $profile->languages,
            'nationality' => $profile->nationality,
            'location' => $profile->location,
            'website' => $profile->website,
            'avatar_url' => $this->resolveUrl($profile->avatar_url ?? null, $media),
            'cover_url' => $this->resolveUrl($profile->cover_url ?? null, $media),
            'stats' => [
                'series_count' => $series->count(),
                'likes_total' => $series->sum(fn ($s) => $s['likes_count'] ?? 0),
            ],
        ];

        return Inertia::render('reader/Creators/Show', [
            'creator' => $creatorData,
            'series' => $series,
        ]);
    }

    protected function resolveUrl(?string $value, MediaService $media): ?string
    {
        if (!$value) {
            return null;
        }

        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }

        return $media->url($value);
    }
}
