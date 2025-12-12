<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PayoutsController extends Controller
{
    public function index(Request $request): Response
    {
        $user = $request->user();

        $accounts = $user->payoutAccounts()
            ->select(['id', 'type', 'label', 'holder_first_name', 'holder_last_name', 'details', 'is_default', 'status'])
            ->latest()
            ->get();

        $payouts = $user->payouts()
            ->with('account:id,label')
            ->latest()
            ->get(['id', 'reference', 'amount_xaf', 'status', 'paid_at', 'payout_account_id']);

        $invoices = $user->invoices()
            ->latest()
            ->get(['id', 'number', 'period_label', 'amount_xaf', 'status', 'payment_method', 'paid_at']);

        $summary = [
            'gross' => (int) $user->payouts()->sum('gross_amount_xaf'),
            'platform' => (int) $user->payouts()->sum('platform_fee_xaf'),
            'net' => (int) $user->payouts()->sum('net_amount_xaf'),
            'paid' => (int) $user->payouts()->where('status', 'paid')->sum('amount_xaf'),
            'pending' => (int) $user->payouts()->whereIn('status', ['pending', 'processing'])->sum('amount_xaf'),
        ];

        return Inertia::render('creator/Payouts/Index', [
            'accounts' => $accounts,
            'payouts' => $payouts,
            'invoices' => $invoices,
            'summary' => $summary,
        ]);
    }
}
