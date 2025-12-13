<?php

namespace App\Http\Controllers\Reader;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Like;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ChapterActionController extends Controller
{
    public function like(Request $request, Chapter $chapter): RedirectResponse
    {
        $this->setReaction($chapter, $request->user()->id, true);
        return back()->with('success', 'Chapitre liké.');
    }

    public function dislike(Request $request, Chapter $chapter): RedirectResponse
    {
        $this->setReaction($chapter, $request->user()->id, false);
        return back()->with('success', 'Chapitre disliké.');
    }

    protected function setReaction(Chapter $chapter, string $userId, bool $isLike): void
    {
        $existing = Like::where('user_id', $userId)
            ->where('likeable_id', $chapter->id)
            ->where('likeable_type', Chapter::class)
            ->first();

        if (!$existing) {
            Like::create([
                'user_id' => $userId,
                'likeable_id' => $chapter->id,
                'likeable_type' => Chapter::class,
                'is_like' => $isLike,
            ]);

            $isLike ? $chapter->increment('likes_count') : $chapter->increment('dislikes_count');
            return;
        }

        if ($existing->is_like === $isLike) {
            return;
        }

        $existing->update(['is_like' => $isLike]);

        if ($isLike) {
            $chapter->increment('likes_count');
            if ($chapter->dislikes_count > 0) {
                $chapter->decrement('dislikes_count');
            }
        } else {
            $chapter->increment('dislikes_count');
            if ($chapter->likes_count > 0) {
                $chapter->decrement('likes_count');
            }
        }
    }
}
