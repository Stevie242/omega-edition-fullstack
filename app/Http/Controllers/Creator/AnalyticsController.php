<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\ChapterView;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class AnalyticsController extends Controller
{
    public function index(): Response
    {
        $user = request()->user();
        $creatorId = $user->id;

        $readsByMonth = ChapterView::query()
            ->select('year_month', DB::raw('count(*) as views'))
            ->where('creator_id', $creatorId)
            ->whereNotNull('counted_at')
            ->groupBy('year_month')
            ->orderBy('year_month')
            ->get();

        $completionBuckets = ChapterView::query()
            ->select(DB::raw('CASE WHEN completion_ratio >= 90 THEN 90 WHEN completion_ratio >= 50 THEN 50 ELSE 0 END as bucket'), DB::raw('count(*) as views'))
            ->where('creator_id', $creatorId)
            ->whereNotNull('counted_at')
            ->groupBy('bucket')
            ->get();

        $topSeries = ChapterView::query()
            ->select('series_id', DB::raw('count(*) as views'))
            ->where('creator_id', $creatorId)
            ->whereNotNull('counted_at')
            ->groupBy('series_id')
            ->orderByDesc('views')
            ->limit(5)
            ->get();

        return Inertia::render('creator/Analytics', [
            'readsByMonth' => $readsByMonth,
            'completionBuckets' => $completionBuckets,
            'topSeries' => $topSeries,
        ]);
    }
}
