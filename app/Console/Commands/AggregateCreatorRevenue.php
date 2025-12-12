<?php

namespace App\Console\Commands;

use App\Jobs\AggregateCreatorRevenueJob;
use Illuminate\Console\Command;

class AggregateCreatorRevenue extends Command
{
    protected $signature = 'revenue:aggregate {--month=} {--value=25} {--platform-bps=2500}';
    protected $description = 'Agrège les vues validées par créateur et calcule le revenu mensuel.';

    public function handle(): int
    {
        $month = $this->option('month') ?: now()->format('Y-m');
        $valuePerView = (int) $this->option('value');
        $platformFeeBps = (int) $this->option('platform-bps');

        AggregateCreatorRevenueJob::dispatch($month, $valuePerView, $platformFeeBps);

        $this->info("Agrégation lancée pour $month (valeur/vue={$valuePerView} XAF, fee={$platformFeeBps} bps)");

        return Command::SUCCESS;
    }
}
