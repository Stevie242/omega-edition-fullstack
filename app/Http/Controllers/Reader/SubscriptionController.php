<?php

namespace App\Http\Controllers\Reader;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class SubscriptionController extends Controller
{
    public function index(): Response
    {
        $user = Auth::user()->load(['readerSubscription', 'invoices' => function ($query) {
            $query->latest()->limit(10);
        }]);

        $plans = SubscriptionPlan::query()
            ->where('is_active', true)
            ->orderByDesc('is_default')
            ->orderBy('price_xaf')
            ->get()
            ->map(fn ($plan) => [
                'id' => $plan->id,
                'name' => $plan->name,
                'slug' => $plan->slug,
                'price' => number_format($plan->price_xaf, 0, '.', ' ').' XAF',
                'period' => $plan->period_label,
                'description' => $plan->description,
                'perks' => $plan->perks,
                'is_default' => $plan->is_default,
            ]);

        $subscription = $user->readerSubscription
            ? [
                'name' => $user->readerSubscription->plan_name,
                'price' => number_format($user->readerSubscription->price_xaf, 0, '.', ' ').' XAF',
                'period' => $user->readerSubscription->period_label,
                'status' => $user->readerSubscription->status,
                'next_billing_at' => optional($user->readerSubscription->next_billing_at)?->toDateString(),
            ]
            : null;

        $invoices = $user->invoices->map(function ($invoice) {
            return [
                'id' => $invoice->id,
                'number' => $invoice->number,
                'period_label' => $invoice->period_label,
                'amount_xaf' => $invoice->amount_xaf,
                'status' => $invoice->status,
                'paid_at' => optional($invoice->paid_at)?->toDateString(),
                'pdf_url' => $invoice->pdf_url,
            ];
        });

        return Inertia::render('reader/Subscription', [
            'subscription' => $subscription,
            'invoices' => $invoices,
            'plans' => $plans,
        ]);
    }
}
