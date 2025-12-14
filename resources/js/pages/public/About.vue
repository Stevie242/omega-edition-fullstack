<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import PublicLayout from '@/layouts/PublicLayout.vue';
import { Link } from '@inertiajs/vue3';

const stats = [
    { label: 'Créateurs', value: '100+' },
    { label: 'Lecteurs', value: '50K+' },
    { label: 'Œuvres', value: '500+' },
    { label: 'Satisfaction', value: '4.8★' },
];

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
        cta: { label: 'Choisir Lecteur', href: '/register/reader' },
        tone: 'primary',
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
        cta: { label: 'Devenir Créateur', href: '/register/creator' },
        tone: 'secondary',
    },
];
</script>

<template>
    <PublicLayout
        title="À propos d’Omega Edition"
        description="Plateforme narrative futuriste : lecture légale, publication et monétisation pour créateurs."
    >
        <div class="space-y-10">
            <section class="grid gap-8 lg:grid-cols-[1.2fr,0.8fr] items-center">
                <div class="space-y-4">
                    <Badge variant="secondary">Choisis ta voie</Badge>
                    <h1 class="text-3xl font-semibold leading-tight sm:text-4xl">
                        Lecteur ou créateur, forge ta destinée.
                    </h1>
                    <p class="text-sm text-muted-foreground">
                        Omega Edition relie les univers de lecture et de création. Lis gratuitement les premiers chapitres, passe en premium pour tout débloquer, ou publie tes œuvres avec des outils studio et un partage de revenus.
                    </p>
                    <div class="flex flex-wrap gap-3 text-sm text-muted-foreground">
                        <span>Base tarifs en XAF, conversion selon ta devise.</span>
                        <span>Chapitre gratuit public : aucune rémunération (pas de compte).</span>
                    </div>
                    <div class="flex gap-3">
                        <Button as-child>
                            <Link href="/catalog">Explorer le catalogue</Link>
                        </Button>
                        <Button as-child variant="outline" class="border-primary/40 text-primary hover:bg-primary/10">
                            <Link href="/register">Créer un compte</Link>
                        </Button>
                    </div>
                </div>
                <div class="rounded-2xl border bg-card p-6 shadow-inner">
                    <div class="grid grid-cols-2 gap-4">
                        <div v-for="s in stats" :key="s.label" class="rounded-xl border bg-muted/40 p-4 text-center">
                            <div class="text-2xl font-bold">{{ s.value }}</div>
                            <div class="text-xs text-muted-foreground">{{ s.label }}</div>
                        </div>
                    </div>
                    <div class="mt-4 rounded-xl border border-primary/30 bg-primary/5 p-4 text-sm text-primary">
                        Tarifs lecteurs : Free (chapitres gratuits), Classique, Premium. Tarifs créateurs : outils studio + partage revenus (jusqu’à 70%).
                    </div>
                </div>
            </section>

            <section class="grid gap-4 md:grid-cols-2">
                <Card v-for="role in roles" :key="role.title" :class="role.tone === 'primary' ? 'border-primary/50' : 'border-secondary/50'">
                    <CardHeader>
                        <div class="flex items-center justify-between">
                            <CardTitle>{{ role.title }}</CardTitle>
                            <Badge :variant="role.tone === 'primary' ? 'outline' : 'secondary'">{{ role.badge }}</Badge>
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
                        <Button as-child :variant="role.tone === 'secondary' ? 'secondary' : 'default'">
                            <Link :href="role.cta.href">{{ role.cta.label }}</Link>
                        </Button>
                    </CardContent>
                </Card>
            </section>

            <section class="grid gap-6 lg:grid-cols-[1.1fr,0.9fr]">
                <Card>
                    <CardHeader>
                        <CardTitle>Modèle et conversion</CardTitle>
                        <CardDescription>Base XAF, conversion via l’utilitaire existant.</CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-3 text-sm text-muted-foreground">
                        <p>Les prix sont définis en XAF (plans Free, Classique, Premium). Une utilitaire convertit selon la devise préférée de l’utilisateur.</p>
                        <p>Les chapitres publics gratuits ne déclenchent pas de rémunération, car la lecture se fait sans compte.</p>
                        <p>Les plans créateurs reposent sur les règles de revenus (payouts) déjà définies : outils studio, analytics, partage jusqu’à 70% selon les accords.</p>
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

            <section class="rounded-2xl border bg-gradient-to-br from-primary/15 via-background to-secondary/15 p-10 text-center">
                <h2 class="text-2xl font-semibold">Prêt à rejoindre l’univers Omega Edition ?</h2>
                <p class="mt-2 text-sm text-muted-foreground">
                    Lecteurs : premiers chapitres gratuits. Créateurs : publiez, monétisez, suivez vos stats.
                </p>
                <div class="mt-4 flex flex-col justify-center gap-3 sm:flex-row sm:items-center">
                    <Button as-child size="lg">
                        <Link href="/catalog">Commencer la lecture</Link>
                    </Button>
                    <Button as-child size="lg" variant="outline" class="border-primary/40 text-primary hover:bg-primary/10">
                        <Link href="/register/creator">Devenir créateur</Link>
                    </Button>
                </div>
            </section>
        </div>
    </PublicLayout>
</template>
