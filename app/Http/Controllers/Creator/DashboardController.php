<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $summary = [
            'reads' => 12400,
            'followers' => 860,
            'net_revenue_xaf' => 3240000,
            'pending_payout_xaf' => 620000,
            'change_reads_pct' => 12.4,
            'change_revenue_pct' => 8.1,
        ];

        $upcoming = [
            ['series' => 'Akoni', 'chapter' => 'Ch. 12', 'date' => now()->addDays(2)->toDateString()],
            ['series' => 'Noir Brazzaville', 'chapter' => 'Ch. 7', 'date' => now()->addDays(5)->toDateString()],
            ['series' => 'Traverse', 'chapter' => 'Ch. 3', 'date' => now()->addDays(9)->toDateString()],
        ];

        $alerts = [
            ['type' => 'info', 'message' => 'Publiez une couverture HD pour "Akoni" avant la sortie.'],
            ['type' => 'warning', 'message' => 'Vérifiez vos métadonnées sur "Noir Brazzaville" (genre, tags).'],
        ];

        $topSeries = [
            ['title' => 'Akoni', 'reads' => 5400, 'trend' => 14.2],
            ['title' => 'Noir Brazzaville', 'reads' => 3100, 'trend' => 6.5],
            ['title' => 'Traverse', 'reads' => 2200, 'trend' => -2.1],
        ];

        $recentPayouts = [
            ['ref' => 'TRF-2025-004', 'amount_xaf' => 620000, 'date' => now()->subDays(5)->toDateString(), 'status' => 'paid'],
            ['ref' => 'TRF-2025-003', 'amount_xaf' => 540000, 'date' => now()->subDays(34)->toDateString(), 'status' => 'paid'],
        ];

        return Inertia::render('creator/Dashboard', [
            'summary' => $summary,
            'upcoming' => $upcoming,
            'alerts' => $alerts,
            'topSeries' => $topSeries,
            'recentPayouts' => $recentPayouts,
        ]);
    }
}
