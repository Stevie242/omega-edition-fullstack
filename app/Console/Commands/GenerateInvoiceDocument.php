<?php

namespace App\Console\Commands;

use App\Jobs\GenerateInvoiceDocument as GenerateInvoiceJob;
use App\Models\Invoice;
use Illuminate\Console\Command;

class GenerateInvoiceDocument extends Command
{
    protected $signature = 'invoice:generate {invoice_id}';

    protected $description = 'Génère le document (HTML/PDF) pour une facture donnée';

    public function handle(): int
    {
        $invoice = Invoice::find($this->argument('invoice_id'));

        if (!$invoice) {
            $this->error('Invoice not found.');
            return self::FAILURE;
        }

        GenerateInvoiceJob::dispatch($invoice);
        $this->info("Génération déclenchée pour la facture {$invoice->number}");

        return self::SUCCESS;
    }
}
