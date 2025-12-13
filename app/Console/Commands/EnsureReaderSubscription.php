<?php

namespace App\Console\Commands;

use App\Models\ReaderSubscription;
use App\Models\SubscriptionPlan;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class EnsureReaderSubscription extends Command
{
    protected $signature = 'reader:ensure-subscriptions';

    protected $description = 'Ensure readers with inactive/expired subscription are switched to the free plan.';

    public function handle(): int
    {
        $freePlan = SubscriptionPlan::where('is_default', true)
            ->where('is_active', true)
            ->first();

        if (!$freePlan) {
            $this->error('No active default (free) plan found. Aborting.');
            return self::FAILURE;
        }

        $now = Carbon::now();
        $count = 0;

        // Find subscriptions that are inactive/expired.
        $subs = ReaderSubscription::query()
            ->where(function ($q) use ($now) {
                $q->where('status', '!=', 'active')
                    ->orWhere(function ($q2) use ($now) {
                        $q2->whereNotNull('next_billing_at')
                            ->where('next_billing_at', '<', $now);
                    });
            })
            ->get();

        foreach ($subs as $sub) {
            DB::transaction(function () use ($sub, $freePlan, &$count) {
                $sub->update([
                    'plan_name' => $freePlan->name,
                    'price_xaf' => $freePlan->price_xaf,
                    'period_label' => $freePlan->period_label,
                    'status' => 'active',
                    'next_billing_at' => null,
                    'meta' => ['auto_switched' => true],
                ]);
                $count++;
            });
        }

        $this->info("Processed {$subs->count()} subscriptions. Switched {$count} to free plan.");
        return self::SUCCESS;
    }
}
