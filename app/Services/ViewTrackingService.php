<?php

namespace App\Services;

use App\Models\ChapterView;
use App\Models\User;
use Carbon\Carbon;

class ViewTrackingService
{
    public function recordView(
        User $user,
        string $chapterId,
        ?string $seriesId,
        ?string $creatorId,
        int $durationSeconds,
        int $completionRatio,
        array $meta = []
    ): bool {
        $now = Carbon::now();
        $yearMonth = $now->format('Y-m');

        $view = ChapterView::query()->updateOrCreate(
            [
                'user_id' => $user->id,
                'chapter_id' => $chapterId,
                'year_month' => $yearMonth,
            ],
            [
                'series_id' => $seriesId,
                'creator_id' => $creatorId,
            ],
        );

        $view->duration_seconds = max($view->duration_seconds, $durationSeconds);
        $view->completion_ratio = max($view->completion_ratio, min($completionRatio, 100));
        $view->first_viewed_at = $view->first_viewed_at ?? $now;
        $view->last_viewed_at = $now;
        if (!empty($meta)) {
            $view->meta = array_merge($view->meta ?? [], $meta);
        }

        $validated = $this->meetsThreshold($view->duration_seconds, $view->completion_ratio);
        if ($validated && !$view->counted_at) {
            $view->counted_at = $now;
        }

        $view->save();

        return (bool) $validated;
    }

    protected function meetsThreshold(int $durationSeconds, int $completionRatio): bool
    {
        // Exigence minimale : 30s + 50% du chapitre.
        return $durationSeconds >= 30 && $completionRatio >= 50;
    }
}
