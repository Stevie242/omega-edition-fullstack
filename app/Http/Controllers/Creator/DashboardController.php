<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\ChapterView;
use App\Models\CreatorRevenueMonthly;
use App\Models\Payout;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $user = request()->user();
        $creatorId = $user->id;

        $currentMonth = Carbon::now()->format('Y-m');
        $previousMonth = Carbon::now()->subMonth()->format('Y-m');

        $readsCurrent = ChapterView::query()
            ->where('creator_id', $creatorId)
            ->where('year_month', $currentMonth)
            ->whereNotNull('counted_at')
            ->count();

        $readsPrev = ChapterView::query()
            ->where('creator_id', $creatorId)
            ->where('year_month', $previousMonth)
            ->whereNotNull('counted_at')
            ->count();

        $revenueCurrent = CreatorRevenueMonthly::query()
            ->where('creator_id', $creatorId)
            ->where('year_month', $currentMonth)
            ->first();

        $revenuePrev = CreatorRevenueMonthly::query()
            ->where('creator_id', $creatorId)
            ->where('year_month', $previousMonth)
            ->first();

        $pendingPayout = Payout::query()
            ->where('user_id', $creatorId)
            ->whereIn('status', ['pending', 'processing'])
            ->sum('amount_xaf');

        $recentPayouts = Payout::query()
            ->where('user_id', $creatorId)
            ->whereNotNull('paid_at')
            ->latest('paid_at')
            ->limit(5)
            ->get(['reference', 'amount_xaf', 'paid_at', 'status'])
            ->map(function ($p) {
                return [
                    'ref' => $p->reference ?? $p->id,
                    'amount_xaf' => $p->amount_xaf,
                    'date' => optional($p->paid_at)->toDateString(),
                    'status' => $p->status,
                ];
            });

        $topSeries = ChapterView::query()
            ->select('series_id', DB::raw('count(*) as views'))
            ->where('creator_id', $creatorId)
            ->whereNotNull('counted_at')
            ->groupBy('series_id')
            ->orderByDesc('views')
            ->limit(5)
            ->get()
            ->map(function ($row) {
                return [
                    'title' => $row->series_id ? "Série {$row->series_id}" : 'Série inconnue',
                    'reads' => (int) $row->views,
                    'trend' => 0,
                ];
            });

        $summary = [
            'reads' => $readsCurrent,
            'followers' => 0,
            'net_revenue_xaf' => (int) ($revenueCurrent->net_amount_xaf ?? 0),
            'pending_payout_xaf' => (int) $pendingPayout,
            'change_reads_pct' => $this->percentChange($readsPrev, $readsCurrent),
            'change_revenue_pct' => $this->percentChange(
                (int) ($revenuePrev->net_amount_xaf ?? 0),
                (int) ($revenueCurrent->net_amount_xaf ?? 0),
            ),
        ];

        return Inertia::render('creator/Dashboard', [
            'summary' => $summary,
            'upcoming' => [],
            'alerts' => [],
            'topSeries' => $topSeries,
            'recentPayouts' => $recentPayouts,
        ]);
    }

    private function percentChange(int $previous, int $current): float
    {
        if ($previous === 0) {
            return $current > 0 ? 100.0 : 0.0;
        }

        return round((($current - $previous) / $previous) * 100, 1);
    }
}
