<?php

namespace App\Jobs;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

class GenerateInvoiceDocument implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Invoice $invoice)
    {
    }

    public function handle(): void
    {
        $invoice = $this->invoice->fresh();
        if (!$invoice) {
            return;
        }

        $html = View::make('pdf.invoice', [
            'invoice' => $invoice,
            'user' => $invoice->user,
        ])->render();

        $pdf = Pdf::loadHTML($html)->setPaper('a4', 'portrait')->output();

        $path = "invoices/{$invoice->number}.pdf";
        Storage::disk('public')->put($path, $pdf);

        $invoice->update([
            'pdf_url' => Storage::disk('public')->url($path),
        ]);
    }
}
