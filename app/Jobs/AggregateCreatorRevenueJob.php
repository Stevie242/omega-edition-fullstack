<?php

namespace App\Jobs;

use App\Models\ChapterView;
use App\Models\CreatorRevenueMonthly;
use App\Models\TaxProfile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AggregateCreatorRevenueJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        protected string $yearMonth,
        protected int $valuePerViewXaf = 25,
        protected int $platformFeeBps = 2500, // 25% = 2500 bps
    ) {
    }

    public function handle(): void
    {
        // On agrège uniquement les vues validées (counted_at non null)
        $views = ChapterView::query()
            ->select('creator_id', DB::raw('count(*) as views'))
            ->whereNotNull('counted_at')
            ->where('year_month', $this->yearMonth)
            ->whereNotNull('creator_id')
            ->groupBy('creator_id')
            ->get();

        $taxProfiles = TaxProfile::query()
            ->whereIn('user_id', $views->pluck('creator_id'))
            ->get()
            ->keyBy('user_id');

        $records = $views->map(function ($row) use ($taxProfiles) {
            /** @var TaxProfile|null $tax */
            $tax = $taxProfiles->get($row->creator_id);
            $gross = (int) $row->views * $this->valuePerViewXaf;
            $platformFee = (int) floor($gross * $this->platformFeeBps / 10000);
            $taxRateBps = (int) ($tax?->tax_rate_bps ?? 0);
            $taxWithheld = (int) floor($gross * $taxRateBps / 10000);
            $net = max($gross - $platformFee - $taxWithheld, 0);

            return [
                'creator_id' => $row->creator_id,
                'year_month' => $this->yearMonth,
                'validated_views' => (int) $row->views,
                'gross_amount_xaf' => $gross,
                'platform_fee_xaf' => $platformFee,
                'tax_withheld_xaf' => $taxWithheld,
                'net_amount_xaf' => $net,
                'meta' => [
                    'value_per_view_xaf' => $this->valuePerViewXaf,
                    'platform_fee_bps' => $this->platformFeeBps,
                    'tax_rate_bps' => $taxRateBps,
                ],
                'updated_at' => now(),
                'created_at' => now(),
            ];
        });

        $this->upsertMonthly($records);
    }

    protected function upsertMonthly(Collection $records): void
    {
        if ($records->isEmpty()) {
            return;
        }

        CreatorRevenueMonthly::query()->upsert(
            $records->all(),
            ['creator_id', 'year_month'],
            [
                'validated_views',
                'gross_amount_xaf',
                'platform_fee_xaf',
                'tax_withheld_xaf',
                'net_amount_xaf',
                'meta',
                'updated_at',
            ],
        );
    }
}
