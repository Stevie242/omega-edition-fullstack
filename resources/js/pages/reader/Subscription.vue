<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Separator } from '@/components/ui/separator';
import ReaderLayout from '@/layouts/ReaderLayout.vue';
import { BadgeCheck, ShieldCheck, Wallet, FileText } from 'lucide-vue-next';
import { Link, useForm } from '@inertiajs/vue3';

interface Subscription {
    name: string;
    price: string;
    period: string;
    perks?: string[];
    status?: 'active' | 'inactive';
    next_billing_at?: string | null;
}

interface Invoice {
    id: string;
    number: string;
    period_label: string | null;
    amount_xaf: number;
    status: string;
    paid_at: string | null;
    pdf_url?: string | null;
}

interface Plan {
    id: string;
    name: string;
    slug: string;
    price: string;
    period: string;
    description?: string | null;
    perks?: string[] | null;
    is_default?: boolean;
}

const props = defineProps<{
    subscription?: Subscription | null;
    subscriptionPerks?: string[] | null;
    invoices?: Invoice[];
    plans?: Plan[];
}>();

const form = useForm({
    plan_id: '',
});

const selectPlan = (planId: string) => {
    form.plan_id = planId;
    form.post('/reader/subscription/choose');
};

const formatAmount = (amount: number) =>
    new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'XAF' }).format(amount);
</script>

<template>
    <ReaderLayout
        title="Abonnement"
        description="Pilote ton accès premium et retrouve tes factures."
    >
        <div class="grid gap-6 lg:grid-cols-12">
            <Card class="lg:col-span-7">
                <CardHeader class="flex flex-col gap-2 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <CardTitle>Ton plan</CardTitle>
                        <CardDescription>
                            {{ props.subscription?.status === 'active' ? 'Plan actif' : 'Aucun plan actif. Passe en premium pour tout débloquer.' }}
                        </CardDescription>
                    </div>
                    <div class="flex items-center gap-2 text-sm">
                        <BadgeCheck class="h-4 w-4 text-emerald-500" />
                        <span class="text-muted-foreground">
                            {{ props.subscription?.status === 'active' ? 'Actif' : 'Inactif' }}
                        </span>
                    </div>
                </CardHeader>
                <CardContent class="space-y-4">
                    <div class="flex flex-col gap-3 rounded-lg border border-border/60 bg-muted/40 p-4">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <div class="text-lg font-semibold">{{ props.subscription?.name ?? 'Découverte' }}</div>
                                <div class="text-sm text-muted-foreground">
                                    {{ props.subscription?.period ?? 'Mensuel' }}
                                </div>
                            </div>
                            <div class="text-xl font-semibold text-foreground/90">
                                {{ props.subscription?.price ?? '—' }}
                            </div>
                        </div>
                        <Separator />
                        <div class="grid gap-2 md:grid-cols-2">
                            <div
                                v-if="props.subscriptionPerks?.length"
                                v-for="perk in props.subscriptionPerks"
                                :key="perk"
                                class="flex items-center gap-2 text-sm text-muted-foreground"
                            >
                                <ShieldCheck class="h-4 w-4 text-emerald-400" />
                                <span>{{ perk }}</span>
                            </div>
                            <template v-else>
                                <div class="flex items-center gap-2 text-sm text-muted-foreground">
                                    <ShieldCheck class="h-4 w-4 text-emerald-400" />
                                    Accès aux premiers chapitres gratuits
                                </div>
                                <div class="flex items-center gap-2 text-sm text-muted-foreground">
                                    <Wallet class="h-4 w-4 text-cyan-400" />
                                    Paiement sécurisé pour passer en premium
                                </div>
                            </template>
                        </div>
                    </div>
                </CardContent>
                <CardFooter class="flex flex-wrap gap-3">
                    <Button variant="secondary">Modifier mon plan</Button>
                    <Button variant="ghost">Suspendre</Button>
                </CardFooter>
            </Card>

            <Card class="lg:col-span-5">
                <CardHeader>
                    <CardTitle>Factures</CardTitle>
                    <CardDescription>Historique de tes paiements.</CardDescription>
                </CardHeader>
                <CardContent class="space-y-3">
                    <div
                        v-if="!props.invoices || props.invoices.length === 0"
                        class="rounded-md border border-dashed border-muted-foreground/40 p-4 text-sm text-muted-foreground"
                    >
                        Pas encore de facture.
                    </div>
                    <div v-else class="space-y-3">
                        <div
                            v-for="invoice in props.invoices"
                            :key="invoice.id"
                            class="flex items-center justify-between gap-3 rounded-md border border-border/60 bg-muted/30 px-3 py-2 text-sm"
                        >
                            <div class="flex flex-col">
                                <div class="font-semibold">#{{ invoice.number }}</div>
                                <div class="text-muted-foreground">
                                    {{ invoice.period_label ?? 'Période' }} ·
                                    {{ invoice.paid_at ? new Date(invoice.paid_at).toLocaleDateString('fr-FR') : '—' }}
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="font-semibold">{{ formatAmount(invoice.amount_xaf) }}</span>
                                <Link
                                    v-if="invoice.pdf_url"
                                    :href="invoice.pdf_url"
                                    class="inline-flex items-center gap-1 text-xs text-primary underline"
                                    target="_blank"
                                >
                                    <FileText class="h-4 w-4" /> PDF
                                </Link>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>

        <div class="mt-8 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold">Choisis ta voie</h2>
                    <p class="text-sm text-muted-foreground">Sélectionne un plan pour débloquer plus de contenus.</p>
                </div>
            </div>
            <div class="grid gap-4 md:grid-cols-2">
                <Card
                    v-for="plan in props.plans"
                    :key="plan.id"
                    class="relative overflow-hidden border border-border/80"
                >
                    <CardHeader class="space-y-1">
                        <div class="flex items-center justify-between gap-2">
                            <CardTitle class="text-lg">{{ plan.name }}</CardTitle>
                            <span
                                v-if="plan.is_default"
                                class="rounded-full bg-muted px-2 py-0.5 text-xs text-muted-foreground"
                                >Plan par défaut</span
                            >
                        </div>
                        <CardDescription>{{ plan.description }}</CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-3">
                        <div class="text-2xl font-semibold">
                            {{ plan.price }}
                            <span class="text-sm text-muted-foreground">/ {{ plan.period }}</span>
                        </div>
                        <div class="space-y-2">
                            <div
                                v-for="perk in plan.perks ?? []"
                                :key="perk"
                                class="flex items-center gap-2 text-sm text-muted-foreground"
                            >
                                <BadgeCheck class="h-4 w-4 text-emerald-500" />
                                <span>{{ perk }}</span>
                            </div>
                        </div>
                    </CardContent>
                    <CardFooter>
                        <Button class="w-full" :disabled="form.processing" @click="selectPlan(plan.id)">
                            Choisir {{ plan.name }}
                        </Button>
                    </CardFooter>
                </Card>
            </div>
        </div>
    </ReaderLayout>
</template>
