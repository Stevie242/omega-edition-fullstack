<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import PublicLayout from '@/layouts/PublicLayout.vue';
import { Link } from '@inertiajs/vue3';

interface StatItem {
    label: string;
    value: string;
}

interface Plan {
    id: string;
    name: string;
    slug: string;
    price_xaf: number;
    period_label?: string | null;
    description?: string | null;
    perks?: string[] | null;
    is_default?: boolean;
}

const props = defineProps<{
    stats: StatItem[];
    plans: Plan[];
}>();

const roles = [
    {
        title: 'Voie Lecteur',
        badge: 'Lecteur',
        desc: 'Découvre gratuitement les premiers chapitres, passe en premium pour tout lire.',
        perks: [
            'Accès public aux 1ers chapitres sans compte',
            'Plan gratuit inscrit : favoris/historique limités',
            'Plan premium : accès complet, notifications, synchronisation multi-appareils',
        ],
    },
    {
        title: 'Voie Créateur',
        badge: 'Créateur',
        desc: 'Publie tes séries, monétise, analyse tes performances.',
        perks: [
            'Profil créateur : bio, portfolio, moodboard, avatar/couverture',
            'Publication chapitres, mise en avant, communauté',
            'Analytics lecture + revenus, partage jusqu’à 70%',
        ],
    },
];

const steps = [
    { title: 'Choisis ta voie', desc: 'Lecteur (lecture) ou Créateur (publication).' },
    { title: 'Crée ton compte', desc: 'Inscription simple, email + mot de passe.' },
    { title: 'Onboarding', desc: 'Lecteur : profil basique. Créateur : profil studio complet.' },
    { title: 'Profite', desc: 'Lis gratuitement ou publie, puis passe premium si besoin.' },
];
</script>

<template>
    <PublicLayout minimal>
        <div class="space-y-12">
            <!-- HERO -->
            <section class="relative overflow-hidden rounded-3xl border bg-gradient-to-br from-slate-900/80 via-background to-primary/10 shadow-2xl">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_20%,rgba(99,102,241,0.25),transparent_30%),radial-gradient(circle_at_80%_0%,rgba(14,165,233,0.2),transparent_30%),radial-gradient(circle_at_50%_100%,rgba(244,63,94,0.2),transparent_35%)]"></div>
                <div class="relative grid items-center gap-10 p-8 md:grid-cols-[1.2fr,0.8fr] md:p-12">
                    <div class="space-y-4">
                        <Badge variant="secondary">À propos</Badge>
                        <h1 class="text-4xl font-bold leading-tight sm:text-5xl">
                            Omega Edition : le carrefour des lecteurs et créateurs.
                        </h1>
                        <p class="text-base text-muted-foreground md:text-lg">
                            Lis légalement, soutiens les auteurs, publie tes œuvres avec des outils studio. Chapitre public gratuit (sans compte), plans premium pour tout débloquer, revenus partagés pour les créateurs.
                        </p>
                        <div class="flex flex-wrap gap-3 text-xs text-muted-foreground">
                            <span>Sans pub intrusive.</span>
                            <span>Premium = accès complet + notifications.</span>
                            <span>Créateurs rémunérés (jusqu’à 70%).</span>
                        </div>
                    </div>
                    <div class="relative">
                        <div class="overflow-hidden rounded-2xl border border-primary/30 bg-background/60 p-6 shadow-[0_20px_80px_rgba(0,0,0,0.35)]">
                            <div class="grid grid-cols-2 gap-4">
                                <div v-for="s in props.stats" :key="s.label" class="rounded-xl border bg-muted/40 p-4 text-center">
                                    <div class="text-2xl font-bold">{{ s.value }}</div>
                                    <div class="text-xs text-muted-foreground">{{ s.label }}</div>
                                </div>
                            </div>
                            <div class="mt-4 rounded-xl border border-primary/30 bg-primary/5 p-4 text-sm text-primary">
                                Lecteurs : Free / Classique / Premium. Créateurs : outils studio + partage revenus.
                            </div>
                        </div>
                        <div class="absolute -right-6 -top-6 h-20 w-20 rounded-full border border-primary/40 bg-primary/20 blur-2xl"></div>
                        <div class="absolute -left-8 bottom-6 h-16 w-16 rounded-full border border-secondary/30 bg-secondary/20 blur-2xl"></div>
                    </div>
                </div>
            </section>

            <!-- ROLES -->
            <section class="grid gap-4 md:grid-cols-2">
                <Card v-for="role in roles" :key="role.title">
                    <CardHeader>
                        <div class="flex items-center justify-between">
                            <CardTitle>{{ role.title }}</CardTitle>
                            <Badge variant="outline">{{ role.badge }}</Badge>
                        </div>
                        <CardDescription>{{ role.desc }}</CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-3 text-sm text-muted-foreground">
                        <ul class="space-y-2">
                            <li v-for="perk in role.perks" :key="perk" class="flex gap-2">
                                <span>✓</span>
                                <span>{{ perk }}</span>
                            </li>
                        </ul>
                    </CardContent>
                </Card>
            </section>

            <!-- PLANS (données réelles) -->
            <section class="space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold">Plans & tarifs</h2>
                        <p class="text-sm text-muted-foreground">Base XAF, conversion automatique selon la devise préférée.</p>
                    </div>
                </div>
                <div class="grid gap-4 md:grid-cols-3">
                    <Card v-for="plan in plans" :key="plan.id" class="h-full">
                        <CardHeader>
                            <div class="flex items-center justify-between">
                                <CardTitle>{{ plan.name }}</CardTitle>
                                <Badge v-if="plan.is_default" variant="secondary">Par défaut</Badge>
                            </div>
                            <CardDescription>{{ plan.period_label || 'Mensuel' }}</CardDescription>
                        </CardHeader>
                        <CardContent class="space-y-3 text-sm text-muted-foreground">
                            <div class="text-2xl font-bold text-foreground">{{ plan.price_xaf.toLocaleString('fr-FR') }} XAF</div>
                            <p>{{ plan.description }}</p>
                            <ul class="space-y-2">
                                <li v-for="perk in plan.perks || []" :key="perk" class="flex gap-2">
                                    <span>•</span>
                                    <span>{{ perk }}</span>
                                </li>
                            </ul>
                        </CardContent>
                    </Card>
                </div>
            </section>

            <!-- FLOW -->
            <section class="rounded-2xl border bg-muted/40 p-6 md:p-8">
                <div class="mb-4">
                    <h2 class="text-xl font-semibold">Parcours de compte</h2>
                    <p class="text-sm text-muted-foreground">De l’inscription à la lecture ou la publication.</p>
                </div>
                <div class="grid gap-4 md:grid-cols-4">
                    <Card v-for="step in steps" :key="step.title" class="h-full">
                        <CardHeader>
                            <CardTitle class="text-base">{{ step.title }}</CardTitle>
                            <CardDescription class="text-xs">{{ step.desc }}</CardDescription>
                        </CardHeader>
                    </Card>
                </div>
            </section>

            <!-- MODEL & ENGAGEMENT -->
            <section class="grid gap-6 lg:grid-cols-[1.1fr,0.9fr]">
                <Card>
                    <CardHeader>
                        <CardTitle>Modèle</CardTitle>
                        <CardDescription>Accès public + plans premium.</CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-3 text-sm text-muted-foreground">
                        <p>Lecture gratuite du premier chapitre sans compte.</p>
                        <p>Plans payants pour débloquer tout le catalogue et les fonctionnalités avancées.</p>
                        <p>Conversion des tarifs selon la devise préférée de l’utilisateur.</p>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader>
                        <CardTitle>Engagement créateur</CardTitle>
                        <CardDescription>Outils, revenus, accompagnement.</CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-3 text-sm text-muted-foreground">
                        <p>Publication : upload chapitres, covers, heroes, tags.</p>
                        <p>Revenus : basés sur abonnements et engagement, visibles dans le dashboard (payouts).</p>
                        <p>Support : mise en avant éditoriale, accompagnement, priorité support.</p>
                    </CardContent>
                </Card>
            </section>
        </div>
    </PublicLayout>
</template>
