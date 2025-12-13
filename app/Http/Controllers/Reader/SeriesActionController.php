<?php

namespace App\Http\Controllers\Reader;

use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Models\ReaderFavorite;
use App\Models\Series;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SeriesActionController extends Controller
{
    public function favorite(Request $request, Series $series): RedirectResponse
    {
        ReaderFavorite::firstOrCreate([
            'user_id' => $request->user()->id,
            'series_id' => $series->id,
        ]);

        return back()->with('success', 'Ajouté aux favoris.');
    }

    public function unfavorite(Request $request, Series $series): RedirectResponse
    {
        ReaderFavorite::where('user_id', $request->user()->id)
            ->where('series_id', $series->id)
            ->delete();

        return back()->with('success', 'Retiré des favoris.');
    }

    public function like(Request $request, Series $series): RedirectResponse
    {
        $this->setReaction($series, $request->user()->id, true);
        return back()->with('success', 'Série likée.');
    }

    public function dislike(Request $request, Series $series): RedirectResponse
    {
        $this->setReaction($series, $request->user()->id, false);
        return back()->with('success', 'Série dislikée.');
    }

    protected function setReaction(Series $series, string $userId, bool $isLike): void
    {
        $existing = Like::where('user_id', $userId)
            ->where('likeable_id', $series->id)
            ->where('likeable_type', Series::class)
            ->first();

        if (!$existing) {
            Like::create([
                'user_id' => $userId,
                'likeable_id' => $series->id,
                'likeable_type' => Series::class,
                'is_like' => $isLike,
            ]);

            $isLike ? $series->increment('likes_count') : $series->increment('dislikes_count');
            return;
        }

        // Si la réaction est la même, ne rien faire.
        if ($existing->is_like === $isLike) {
            return;
        }

        // Basculer like <-> dislike et ajuster les compteurs
        $existing->update(['is_like' => $isLike]);

        if ($isLike) {
            $series->increment('likes_count');
            if ($series->dislikes_count > 0) {
                $series->decrement('dislikes_count');
            }
        } else {
            $series->increment('dislikes_count');
            if ($series->likes_count > 0) {
                $series->decrement('likes_count');
            }
        }
    }
}
