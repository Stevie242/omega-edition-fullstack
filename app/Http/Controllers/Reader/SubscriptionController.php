<?php

namespace App\Http\Controllers\Reader;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Models\ReaderSubscription;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

        $subscription = null;
        $subscriptionPerks = null;

        if ($user->readerSubscription) {
            $subscription = [
                'name' => $user->readerSubscription->plan_name,
                'price' => number_format($user->readerSubscription->price_xaf, 0, '.', ' ').' XAF',
                'period' => $user->readerSubscription->period_label,
                'status' => $user->readerSubscription->status,
                'next_billing_at' => optional($user->readerSubscription->next_billing_at)?->toDateString(),
            ];

            $matchedPlan = SubscriptionPlan::where('name', $user->readerSubscription->plan_name)->first();
            $subscriptionPerks = $matchedPlan?->perks;
        } elseif ($plans->isNotEmpty()) {
            // Fallback: afficher le plan par défaut (gratuit) s'il n'y a pas de souscription.
            $defaultPlan = $plans->first(fn ($p) => $p['is_default']) ?? $plans->first();
            if ($defaultPlan) {
                $subscription = [
                    'name' => $defaultPlan['name'],
                    'price' => $defaultPlan['price'],
                    'period' => $defaultPlan['period'],
                    'status' => 'inactive',
                    'next_billing_at' => null,
                ];
                $subscriptionPerks = $defaultPlan['perks'] ?? [];
            }
        }

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
            'subscriptionPerks' => $subscriptionPerks,
            'invoices' => $invoices,
            'plans' => $plans,
        ]);
    }

    public function choose(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'plan_id' => ['required', 'exists:subscription_plans,id'],
        ]);

        $plan = SubscriptionPlan::where('id', $data['plan_id'])
            ->where('is_active', true)
            ->firstOrFail();

        $user = $request->user();
        $nextBilling = $plan->price_xaf > 0 ? Carbon::now()->addMonth() : null;

        ReaderSubscription::updateOrCreate(
            ['user_id' => $user->id],
            [
                'plan_name' => $plan->name,
                'price_xaf' => $plan->price_xaf,
                'period_label' => $plan->period_label,
                'status' => 'active',
                'next_billing_at' => $nextBilling,
                'meta' => ['selected_plan_id' => $plan->id],
            ],
        );

        // Créer une facture si plan payant.
        if ($plan->price_xaf > 0) {
            $invoiceNumber = 'INV-'.Carbon::now()->format('Ymd').'-'.strtoupper(substr($user->id, 0, 6)).'-'.random_int(100, 999);
            $invoice = Invoice::create([
                'user_id' => $user->id,
                'number' => $invoiceNumber,
                'period_label' => $plan->period_label,
                'amount_xaf' => $plan->price_xaf,
                'status' => 'paid', // ajuster si on doit attendre le paiement
                'paid_at' => Carbon::now(),
                'payment_method' => 'manual',
                'meta' => ['plan_id' => $plan->id],
            ]);

            GenerateInvoiceDocument::dispatch($invoice);
        }

        return back()->with('success', 'Plan mis à jour.');
    }
}
