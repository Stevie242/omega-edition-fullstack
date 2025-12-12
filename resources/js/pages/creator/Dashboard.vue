<script setup lang="ts">
import CreatorLayout from '@/layouts/CreatorLayout.vue';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { formatFromXaf, type CurrencyCode } from '@/lib/currency';
import { Link, usePage } from '@inertiajs/vue3';

const props = defineProps<{
    summary: {
        reads: number;
        followers: number;
        net_revenue_xaf: number;
        pending_payout_xaf: number;
        change_reads_pct: number;
        change_revenue_pct: number;
    };
    upcoming: Array<{ series: string; chapter: string; date: string }>;
    alerts: Array<{ type: 'info' | 'warning' | string; message: string }>;
    topSeries: Array<{ title: string; reads: number; trend: number }>;
    recentPayouts: Array<{ ref: string; amount_xaf: number; date: string; status: string }>;
}>();

const page = usePage();
const currency = ((page.props.preference as any)?.currency as CurrencyCode | undefined) ?? 'XAF';
const locale = 'fr-FR';
const formatAmount = (amountInXaf: number) => formatFromXaf(amountInXaf, currency, locale);
</script>

<template>
    <CreatorLayout
        title="Tableau de bord créateur"
        description="Aperçu rapide des séries, sorties à venir et stats clés."
    >
        <div class="grid gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2 space-y-6">
                <div class="grid gap-4 md:grid-cols-2">
                    <Card>
                        <CardHeader class="flex flex-row items-center justify-between">
                            <div>
                                <CardDescription>Lectures 30 derniers jours</CardDescription>
                                <CardTitle>{{ props.summary.reads.toLocaleString('fr-FR') }}</CardTitle>
                            </div>
                            <Badge :variant="props.summary.change_reads_pct >= 0 ? 'secondary' : 'outline'">
                                {{ props.summary.change_reads_pct }} %
                            </Badge>
                        </CardHeader>
                    </Card>
                    <Card>
                        <CardHeader class="flex flex-row items-center justify-between">
                            <div>
                                <CardDescription>Revenus nets (XAF)</CardDescription>
                                <CardTitle>{{ formatAmount(props.summary.net_revenue_xaf) }}</CardTitle>
                            </div>
                            <Badge :variant="props.summary.change_revenue_pct >= 0 ? 'secondary' : 'outline'">
                                {{ props.summary.change_revenue_pct }} %
                            </Badge>
                        </CardHeader>
                        <CardContent class="text-sm text-muted-foreground">
                            Avant reversement, après commission plateforme et taxes.
                        </CardContent>
                    </Card>
                    <Card>
                        <CardHeader>
                            <CardDescription>En attente de versement</CardDescription>
                            <CardTitle>{{ formatAmount(props.summary.pending_payout_xaf) }}</CardTitle>
                        </CardHeader>
                        <CardContent class="text-sm text-muted-foreground">
                            Prochain virement programmé si seuil atteint.
                        </CardContent>
                    </Card>
                    <Card>
                        <CardHeader>
                            <CardDescription>Abonnés / followers</CardDescription>
                            <CardTitle>{{ props.summary.followers.toLocaleString('fr-FR') }}</CardTitle>
                        </CardHeader>
                        <CardContent class="text-sm text-muted-foreground">
                            Lecteurs fidèles (favoris + abonnements actifs).
                        </CardContent>
                    </Card>
                </div>

                <Card>
                    <CardHeader class="flex flex-row items-center justify-between">
                        <div>
                            <CardTitle>Sorties à venir</CardTitle>
                            <CardDescription>Chapitres planifiés sur les 10 prochains jours.</CardDescription>
                        </div>
                        <Button size="sm" variant="outline">Programmer une sortie</Button>
                    </CardHeader>
                    <CardContent class="divide-y divide-border/60">
                        <div
                            v-for="item in props.upcoming"
                            :key="item.series + item.chapter"
                            class="flex items-center justify-between py-3 text-sm"
                        >
                            <div>
                                <p class="font-medium text-foreground">{{ item.series }}</p>
                                <p class="text-muted-foreground">{{ item.chapter }}</p>
                            </div>
                            <div class="text-muted-foreground">
                                {{ new Date(item.date).toLocaleDateString('fr-FR') }}
                            </div>
                        </div>
                        <p v-if="props.upcoming.length === 0" class="py-2 text-sm text-muted-foreground">
                            Aucune sortie planifiée pour le moment.
                        </p>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="flex flex-row items-center justify-between">
                        <div>
                            <CardTitle>Alertes qualité</CardTitle>
                            <CardDescription>Points à vérifier avant publication.</CardDescription>
                        </div>
                        <Button size="sm" variant="outline">Tout marquer comme lu</Button>
                    </CardHeader>
                    <CardContent class="space-y-3">
                        <div
                            v-for="(alert, idx) in props.alerts"
                            :key="idx"
                            class="rounded-md border bg-muted/40 px-3 py-2 text-sm"
                        >
                            <Badge class="mr-2" :variant="alert.type === 'warning' ? 'outline' : 'secondary'">
                                {{ alert.type }}
                            </Badge>
                            {{ alert.message }}
                        </div>
                        <p v-if="props.alerts.length === 0" class="text-sm text-muted-foreground">
                            Rien à signaler pour l’instant.
                        </p>
                    </CardContent>
                </Card>
            </div>

            <div class="space-y-6">
                <Card>
                    <CardHeader>
                        <CardTitle>Top séries</CardTitle>
                        <CardDescription>Lectures récentes et tendance.</CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-3 text-sm">
                        <div
                            v-for="serie in props.topSeries"
                            :key="serie.title"
                            class="flex items-center justify-between rounded-md border px-3 py-2"
                        >
                            <div>
                                <p class="font-medium text-foreground">{{ serie.title }}</p>
                                <p class="text-muted-foreground">{{ serie.reads.toLocaleString('fr-FR') }} lectures</p>
                            </div>
                            <Badge :variant="serie.trend >= 0 ? 'secondary' : 'outline'">
                                {{ serie.trend }} %
                            </Badge>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="flex flex-row items-center justify-between">
                        <div>
                            <CardTitle>Derniers reversements</CardTitle>
                            <CardDescription>Montants versés récemment.</CardDescription>
                        </div>
                        <Button size="sm" variant="outline" as-child>
                            <Link href="/creator/payouts">Voir tout</Link>
                        </Button>
                    </CardHeader>
                    <CardContent class="space-y-2 text-sm">
                        <div
                            v-for="item in props.recentPayouts"
                            :key="item.ref"
                            class="flex items-center justify-between rounded-md border px-3 py-2"
                        >
                            <div>
                                <p class="font-medium text-foreground">{{ formatAmount(item.amount_xaf) }}</p>
                                <p class="text-muted-foreground text-xs">{{ item.ref }}</p>
                            </div>
                            <div class="text-right text-muted-foreground">
                                <p>{{ new Date(item.date).toLocaleDateString('fr-FR') }}</p>
                                <Badge :variant="item.status === 'paid' ? 'secondary' : 'outline'"> {{ item.status }} </Badge>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </CreatorLayout>
</template>
