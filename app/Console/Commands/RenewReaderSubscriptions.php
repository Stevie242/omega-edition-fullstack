<?php

namespace App\Console\Commands;

use App\Jobs\GenerateInvoiceDocument;
use App\Models\Invoice;
use App\Models\ReaderSubscription;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RenewReaderSubscriptions extends Command
{
    protected $signature = 'reader:renew-subscriptions';

    protected $description = 'Renouvelle les abonnements actifs arrivés à échéance, génère facture et repousse la prochaine échéance.';

    public function handle(): int
    {
        $now = Carbon::now();
        $subs = ReaderSubscription::query()
            ->where('status', 'active')
            ->whereNotNull('next_billing_at')
            ->where('next_billing_at', '<=', $now)
            ->get();

        $processed = 0;

        foreach ($subs as $sub) {
            DB::transaction(function () use ($sub, &$processed) {
                $invoiceNumber = 'INV-'.Carbon::now()->format('Ymd').'-'.strtoupper(substr($sub->user_id, 0, 6)).'-'.random_int(100, 999);

                $invoice = Invoice::create([
                    'user_id' => $sub->user_id,
                    'number' => $invoiceNumber,
                    'period_label' => $sub->period_label,
                    'amount_xaf' => $sub->price_xaf,
                    'status' => 'paid', // à ajuster si besoin d'attendre paiement
                    'paid_at' => Carbon::now(),
                    'payment_method' => 'auto-renew',
                    'meta' => ['plan_name' => $sub->plan_name],
                ]);

                GenerateInvoiceDocument::dispatch($invoice);

                $sub->update([
                    'next_billing_at' => Carbon::now()->addMonth(),
                ]);

                $processed++;
            });
        }

        $this->info("Renouvellements traités : {$processed}");
        return self::SUCCESS;
    }
}
