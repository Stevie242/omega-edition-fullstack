<script setup lang="ts">
import CreatorLayout from '@/layouts/CreatorLayout.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { formatFromXaf, type CurrencyCode } from '@/lib/currency';
import { ref } from 'vue';

const tabs = [
    { id: 'revenus', label: 'Revenus & reversements' },
    { id: 'factures', label: 'Factures' },
    { id: 'abonnement', label: 'Abonnement' },
];

const activeTab = ref<'revenus' | 'factures' | 'abonnement'>('revenus');
const currency = ref<CurrencyCode>('XAF');
const locale = 'fr-FR';

const summary = {
    gross: 4320000, // XAF
    platform: 1080000, // XAF
    net: 3240000, // XAF
    paid: 2620000, // XAF
    pending: 620000, // XAF
};

const invoices = [
    {
        date: '05 jan 2025',
        number: 'FAC-2025-001',
        period: 'Déc 2024',
        amount: 12500, // XAF
        status: 'payé',
        method: 'Visa **** 4242',
    },
    {
        date: '05 déc 2024',
        number: 'FAC-2024-012',
        period: 'Nov 2024',
        amount: 12500,
        status: 'payé',
        method: 'Visa **** 4242',
    },
    {
        date: '05 nov 2024',
        number: 'FAC-2024-011',
        period: 'Oct 2024',
        amount: 12500,
        status: 'payé',
        method: 'Visa **** 4242',
    },
];

const payouts = [
    {
        date: '28 déc 2024',
        ref: 'TRF-2024-123',
        amount: 620000,
        status: 'versé',
        proof: 'Voir',
    },
    {
        date: '30 nov 2024',
        ref: 'TRF-2024-112',
        amount: 840000,
        status: 'versé',
        proof: 'Voir',
    },
    {
        date: '02 nov 2024',
        ref: 'TRF-2024-098',
        amount: 1160000,
        status: 'versé',
        proof: 'Voir',
    },
];

const plan = {
    name: 'Creator Pro',
    price: 12500, // XAF
    status: 'Actif',
    renewsAt: '15 fév 2025',
    method: 'Visa **** 4242 (expiry 08/27)',
    perks: [
        '70 % de partage créateur',
        'Support prioritaire',
        'Exports et relevés illimités',
        'Stockage HD inclus',
    ],
};

const tabClass = (id: typeof tabs[number]['id']) =>
    [
        'min-w-[200px] rounded-md px-4 py-2 text-sm font-medium transition-colors',
        activeTab.value === id
            ? 'bg-background text-foreground shadow-sm border'
            : 'text-muted-foreground hover:text-foreground',
    ].join(' ');

const currencyOptions: CurrencyCode[] = ['XAF', 'EUR', 'USD'];

const currencyClass = (code: CurrencyCode) =>
    [
        'px-3 py-1.5 text-xs font-medium transition-colors rounded-md border',
        currency.value === code
            ? 'bg-background text-foreground shadow-sm'
            : 'text-muted-foreground hover:text-foreground',
    ].join(' ');

const statusVariant = (status: string) => {
    if (status === 'payé' || status === 'versé') return 'default';
    if (status === 'en attente') return 'secondary';
    return 'outline';
};

const formatAmount = (amountInXaf: number) => formatFromXaf(amountInXaf, currency.value, locale);
</script>

<template>
    <CreatorLayout
        title="Revenus & paiements"
        description="Synthèse des revenus générés, reversements et facturation de l’abonnement créateur."
    >
        <div class="space-y-6">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div class="flex gap-2 overflow-x-auto rounded-lg border bg-muted/50 p-1">
                    <button
                        v-for="tab in tabs"
                        :key="tab.id"
                        type="button"
                        :class="tabClass(tab.id)"
                        @click="activeTab = tab.id"
                    >
                        {{ tab.label }}
                    </button>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-muted-foreground">Devise d’affichage</span>
                    <div class="flex gap-1 rounded-lg border bg-muted/50 p-1">
                        <button
                            v-for="code in currencyOptions"
                            :key="code"
                            type="button"
                            :class="currencyClass(code)"
                            @click="currency = code"
                        >
                            {{ code }}
                        </button>
                    </div>
                </div>
            </div>

            <section v-if="activeTab === 'revenus'" class="space-y-4">
                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                    <Card>
                        <CardHeader>
                            <CardDescription>Revenus bruts</CardDescription>
                            <CardTitle>{{ formatAmount(summary.gross) }}</CardTitle>
                        </CardHeader>
                        <CardContent class="text-sm text-muted-foreground">
                            Lectures totales avant commission plateforme.
                        </CardContent>
                    </Card>
                    <Card>
                        <CardHeader>
                            <CardDescription>Part plateforme</CardDescription>
                            <CardTitle>{{ formatAmount(summary.platform) }}</CardTitle>
                        </CardHeader>
                        <CardContent class="text-sm text-muted-foreground">
                            Commission retenue (hébergement, paiements, support).
                        </CardContent>
                    </Card>
                    <Card>
                        <CardHeader>
                            <CardDescription>Revenus nets (créateur)</CardDescription>
                            <CardTitle>{{ formatAmount(summary.net) }}</CardTitle>
                        </CardHeader>
                        <CardContent class="text-sm text-muted-foreground">
                            Après commission, avant reversements.
                        </CardContent>
                    </Card>
                    <Card>
                        <CardHeader>
                            <CardDescription>Déjà reversé</CardDescription>
                            <CardTitle>{{ formatAmount(summary.paid) }}</CardTitle>
                        </CardHeader>
                        <CardContent class="text-sm text-muted-foreground">
                            Total des virements effectués sur les 90 derniers jours.
                        </CardContent>
                    </Card>
                    <Card class="md:col-span-2 lg:col-span-1">
                        <CardHeader>
                            <CardDescription>En attente de versement</CardDescription>
                            <CardTitle>{{ formatAmount(summary.pending) }}</CardTitle>
                        </CardHeader>
                        <CardContent class="flex items-center justify-between text-sm text-muted-foreground">
                            <span>Versement prévu à la prochaine fenêtre.</span>
                            <Button variant="outline" size="sm">Exporter le relevé</Button>
                        </CardContent>
                    </Card>
                </div>

                <Card>
                    <CardHeader class="flex flex-row items-center justify-between">
                        <div>
                            <CardTitle>Reversements</CardTitle>
                            <CardDescription>Historique des virements effectués.</CardDescription>
                        </div>
                        <Button size="sm" variant="outline">Exporter CSV</Button>
                    </CardHeader>
                    <CardContent>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="text-left text-muted-foreground">
                                    <tr>
                                        <th class="pb-2 font-medium">Date</th>
                                        <th class="pb-2 font-medium">Réf.</th>
                                        <th class="pb-2 font-medium">Montant</th>
                                        <th class="pb-2 font-medium">Statut</th>
                                        <th class="pb-2 font-medium">Preuve</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-border/60">
                                    <tr v-for="item in payouts" :key="item.ref" class="align-middle">
                                        <td class="py-3">{{ item.date }}</td>
                                        <td class="py-3">{{ item.ref }}</td>
                                        <td class="py-3 font-medium text-foreground">
                                            {{ formatAmount(item.amount) }}
                                        </td>
                                        <td class="py-3">
                                            <Badge :variant="statusVariant(item.status)">{{ item.status }}</Badge>
                                        </td>
                                        <td class="py-3">
                                            <Button size="sm" variant="ghost">{{ item.proof }}</Button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </CardContent>
                </Card>
            </section>

            <section v-else-if="activeTab === 'factures'" class="space-y-4">
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between">
                        <div>
                            <CardTitle>Factures d’abonnement</CardTitle>
                            <CardDescription>Historique et téléchargement des factures mensuelles.</CardDescription>
                        </div>
                        <div class="flex gap-2">
                            <Button size="sm" variant="outline">Exporter CSV</Button>
                            <Button size="sm">Télécharger toutes (PDF)</Button>
                        </div>
                    </CardHeader>
                    <CardContent>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="text-left text-muted-foreground">
                                    <tr>
                                        <th class="pb-2 font-medium">Date</th>
                                        <th class="pb-2 font-medium">N° facture</th>
                                        <th class="pb-2 font-medium">Période</th>
                                        <th class="pb-2 font-medium">Montant</th>
                                        <th class="pb-2 font-medium">Statut</th>
                                        <th class="pb-2 font-medium">Paiement</th>
                                        <th class="pb-2 font-medium text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-border/60">
                                    <tr v-for="invoice in invoices" :key="invoice.number" class="align-middle">
                                        <td class="py-3">{{ invoice.date }}</td>
                                        <td class="py-3">{{ invoice.number }}</td>
                                        <td class="py-3">{{ invoice.period }}</td>
                                        <td class="py-3 font-medium text-foreground">
                                            {{ formatAmount(invoice.amount) }}
                                        </td>
                                        <td class="py-3">
                                            <Badge :variant="statusVariant(invoice.status)">{{ invoice.status }}</Badge>
                                        </td>
                                        <td class="py-3 text-muted-foreground">{{ invoice.method }}</td>
                                        <td class="py-3 text-right">
                                            <div class="flex justify-end gap-2">
                                                <Button size="sm" variant="outline">PDF</Button>
                                                <Button size="sm" variant="ghost">Duplicata</Button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </CardContent>
                </Card>
            </section>

            <section v-else class="space-y-4">
                <div class="grid gap-4 lg:grid-cols-3">
                    <Card class="lg:col-span-2">
                        <CardHeader class="flex flex-row items-center justify-between">
                            <div>
                                <CardTitle>Plan actuel</CardTitle>
                                <CardDescription>Abonnement créateur et renouvellement.</CardDescription>
                            </div>
                            <Badge>{{ plan.status }}</Badge>
                        </CardHeader>
                        <CardContent class="space-y-3 text-sm">
                            <div class="flex flex-wrap items-center gap-3">
                                <span class="text-lg font-semibold text-foreground">{{ plan.name }}</span>
                                <span class="rounded-full bg-muted px-3 py-1 text-xs font-medium text-muted-foreground">
                                    {{ formatAmount(plan.price) }} / mois
                                </span>
                            </div>
                            <p class="text-muted-foreground">
                                Renouvellement le {{ plan.renewsAt }} · Paiement : {{ plan.method }}
                            </p>
                            <ul class="grid gap-2 md:grid-cols-2">
                                <li
                                    v-for="perk in plan.perks"
                                    :key="perk"
                                    class="rounded-md border bg-muted/40 px-3 py-2 text-foreground"
                                >
                                    {{ perk }}
                                </li>
                            </ul>
                            <div class="flex flex-wrap gap-2 pt-2">
                                <Button size="sm">Changer de plan</Button>
                                <Button size="sm" variant="outline">Mettre à jour le moyen de paiement</Button>
                                <Button size="sm" variant="ghost">Voir l’historique</Button>
                            </div>
                        </CardContent>
                    </Card>
                    <Card>
                        <CardHeader>
                            <CardTitle>Coordonnées de versement</CardTitle>
                            <CardDescription>Compte bancaire/Mobile Money à vérifier.</CardDescription>
                        </CardHeader>
                        <CardContent class="space-y-3 text-sm">
                            <p class="text-muted-foreground">
                                IBAN / Mobile Money non renseigné. Ajoutez un compte pour recevoir vos virements.
                            </p>
                            <div class="flex gap-2">
                                <Button size="sm">Ajouter un compte</Button>
                                <Button size="sm" variant="outline">Déclarer un litige</Button>
                            </div>
                            <p class="text-xs text-muted-foreground">
                                Les reversements sont effectués après vérification KYC et atteinte du seuil minimal.
                            </p>
                        </CardContent>
                    </Card>
                </div>
            </section>
        </div>
    </CreatorLayout>
</template>
