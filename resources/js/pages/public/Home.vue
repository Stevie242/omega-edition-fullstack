<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import PublicLayout from '@/layouts/PublicLayout.vue';
import { Link } from '@inertiajs/vue3';

const stats = [
    { value: '500+', label: 'Séries & chapitres' },
    { value: '50K+', label: 'Lecteurs actifs' },
    { value: '100+', label: 'Créateurs partenaires' },
    { value: '4.8★', label: 'Satisfaction moyenne' },
];

const usp = [
    { title: 'Premier chapitre gratuit', desc: 'Testez les séries avant de vous abonner.' },
    { title: 'Soutien aux créateurs', desc: 'Une partie des revenus retourne aux auteurs.' },
    { title: 'Lecture fluide', desc: 'Optimisé mobile, tablette, desktop.' },
    { title: 'Catalogue évolutif', desc: 'Nouvelles sorties chaque semaine.' },
];

const freeTitles = [
    { id: 'free-1', title: 'Shadow Blade', tag: '1er chapitre offert' },
    { id: 'free-2', title: 'Neon Runner', tag: '1er chapitre offert' },
    { id: 'free-3', title: 'Moonlight Princess', tag: '1er chapitre offert' },
];

const plans = [
    {
        name: 'Découverte',
        price: '0 XAF',
        badge: 'Gratuit',
        perks: [
            'Premier chapitre de chaque série',
            'Catalogue public éditorialisé',
            'Sans carte bancaire',
        ],
        cta: 'Commencer',
        href: '/catalog',
        tone: 'neutral',
    },
    {
        name: 'Lecteur Premium',
        price: '4 500 XAF',
        badge: 'Populaire',
        perks: [
            'Accès illimité au catalogue',
            'Lecture sans publicité',
            'Favoris & historique synchronisés',
            'Notifications sorties',
        ],
        cta: 'Choisir Lecteur',
        href: '/register/reader',
        tone: 'primary',
    },
    {
        name: 'Créateur Pro',
        price: 'Partage revenus',
        badge: 'Créateur',
        perks: [
            'Espace studio & publication',
            'Statistiques détaillées',
            'Jusqu’à 70% de revenus partagés',
            'Support prioritaire',
        ],
        cta: 'Devenir Créateur',
        href: '/register/creator',
        tone: 'secondary',
    },
];
</script>

<template>
    <PublicLayout :minimal="true">
        <div class="space-y-16">
            <!-- HERO -->
            <section class="relative overflow-hidden rounded-3xl border bg-gradient-to-br from-primary/10 via-background to-secondary/10 shadow-xl">
                <div class="absolute top-0 right-0 h-64 w-64 rounded-full bg-primary/15 blur-3xl"></div>
                <div class="absolute bottom-0 left-0 h-64 w-64 rounded-full bg-secondary/15 blur-3xl"></div>
                <div class="relative grid items-center gap-8 p-8 md:grid-cols-2 md:p-12">
                    <div class="space-y-6">
                        <Badge variant="outline" class="border-primary/30 bg-primary/10 text-primary">
                            1er chapitre gratuit · Soutien aux créateurs
                        </Badge>
                        <h1 class="text-4xl font-bold leading-tight md:text-5xl">
                            Lisez mangas & webtoons partout, en toute légalité.
                        </h1>
                        <p class="text-base text-muted-foreground md:text-lg">
                            Omega Edition : découvre des séries inédites, lis sans pub, choisis ta voie lecteur ou créateur.
                        </p>
                        <div class="flex flex-col gap-3 sm:flex-row">
                            <Button as-child size="lg">
                                <Link href="/catalog">Commencer à lire</Link>
                            </Button>
                            <Button as-child size="lg" variant="outline" class="border-primary/40 text-primary hover:bg-primary/10">
                                <Link href="/about">Voir les formules</Link>
                            </Button>
                        </div>
                        <div class="flex flex-wrap gap-4 text-xs text-muted-foreground">
                            <span class="inline-flex items-center gap-2">✓ Chapitres gratuits</span>
                            <span class="inline-flex items-center gap-2">✓ Sans publicité intrusive</span>
                            <span class="inline-flex items-center gap-2">✓ Créateurs rémunérés</span>
                        </div>
                    </div>
                    <div class="grid h-72 grid-cols-3 gap-3 md:h-80">
                        <div v-for="idx in 6" :key="idx" class="rounded-xl bg-muted/50 border border-border animate-pulse"></div>
                        <div class="col-span-3 rounded-xl border border-primary/20 bg-primary/10 p-4 text-sm text-primary font-medium">
                            +500 séries à explorer cette semaine
                        </div>
                    </div>
                </div>
            </section>

            <!-- STATS -->
            <section class="grid grid-cols-2 gap-4 md:grid-cols-4">
                <div v-for="item in stats" :key="item.label" class="rounded-xl border bg-card p-4 text-center">
                    <div class="text-2xl font-bold md:text-3xl">{{ item.value }}</div>
                    <div class="text-xs text-muted-foreground">{{ item.label }}</div>
                </div>
            </section>

            <!-- USP -->
            <section class="rounded-2xl border bg-muted/40 p-6 md:p-8">
                <div class="mb-6 text-center">
                    <h2 class="text-2xl font-semibold">Pourquoi Omega Edition ?</h2>
                    <p class="text-sm text-muted-foreground">Expérience fluide, respect des auteurs, esprit futuriste.</p>
                </div>
                <div class="grid gap-4 sm:grid-cols-2 md:grid-cols-4">
                    <Card v-for="u in usp" :key="u.title" class="h-full">
                        <CardHeader>
                            <CardTitle class="text-sm">{{ u.title }}</CardTitle>
                            <CardDescription class="text-xs">{{ u.desc }}</CardDescription>
                        </CardHeader>
                    </Card>
                </div>
            </section>

            <!-- FREE DISCOVERY -->
            <section class="grid gap-8 rounded-2xl border bg-card p-6 md:grid-cols-2 md:p-8">
                <div class="space-y-3">
                    <h2 class="text-xl font-semibold">Découvrez gratuitement</h2>
                    <p class="text-sm text-muted-foreground">
                        Le premier chapitre de chaque série est offert. Pas de compte requis pour tester l’univers.
                    </p>
                    <div class="space-y-2 text-sm text-muted-foreground">
                        <div class="flex items-center gap-2">✓ Accès sans compte</div>
                        <div class="flex items-center gap-2">✓ Sans publicité intrusive</div>
                        <div class="flex items-center gap-2">✓ Passez premium pour tout débloquer</div>
                    </div>
                    <div class="flex gap-2">
                        <Button as-child>
                            <Link href="/catalog">Parcourir le catalogue</Link>
                        </Button>
                        <Button variant="outline" as-child>
                            <Link href="/register/reader">Créer un compte lecteur</Link>
                        </Button>
                    </div>
                </div>
                <div class="grid gap-3 sm:grid-cols-3">
                    <Card v-for="t in freeTitles" :key="t.id" class="h-full border-primary/20 bg-primary/5">
                        <CardContent class="space-y-2 p-4">
                            <div class="h-32 rounded-md bg-muted/60"></div>
                            <div class="text-sm font-semibold">{{ t.title }}</div>
                            <Badge variant="secondary">{{ t.tag }}</Badge>
                        </CardContent>
                    </Card>
                </div>
            </section>

            <!-- CREATOR CTA -->
            <section class="grid gap-8 rounded-2xl border bg-gradient-to-br from-secondary/10 via-background to-secondary/20 p-6 md:grid-cols-2 md:p-8">
                <div class="space-y-4">
                    <Badge variant="secondary">Pour les créateurs</Badge>
                    <h2 class="text-2xl font-semibold">Publie, monétise, suit tes stats.</h2>
                    <p class="text-sm text-muted-foreground">
                        Profil studio, publication de chapitres, analytics détaillés, partage des revenus (jusqu’à 70%) et support prioritaire.
                    </p>
                    <div class="space-y-2 text-sm text-muted-foreground">
                        <div class="flex items-center gap-2">✓ Outils de publication</div>
                        <div class="flex items-center gap-2">✓ Tableau de bord revenus</div>
                        <div class="flex items-center gap-2">✓ Mise en avant dans le catalogue</div>
                    </div>
                    <div class="flex gap-2">
                        <Button as-child variant="secondary">
                            <Link href="/register/creator">Devenir créateur</Link>
                        </Button>
                        <Button variant="outline" as-child>
                            <Link href="/about">En savoir plus</Link>
                        </Button>
                    </div>
                </div>
                <div class="rounded-xl border border-secondary/30 bg-background/60 p-4 text-sm text-muted-foreground shadow-inner">
                    <div class="mb-3 text-base font-semibold text-foreground">Aperçu dashboard créateur</div>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="rounded-lg border bg-muted/50 p-3">
                            <div class="text-xs text-muted-foreground">Lectures ce mois</div>
                            <div class="text-xl font-bold">12 543</div>
                            <div class="text-xs text-emerald-500">+12%</div>
                        </div>
                        <div class="rounded-lg border bg-muted/50 p-3">
                            <div class="text-xs text-muted-foreground">Revenus estimés</div>
                            <div class="text-xl font-bold">1 534 200 XAF</div>
                            <div class="text-xs text-emerald-500">+8%</div>
                        </div>
                    </div>
                    <div class="mt-4 rounded-lg border bg-muted/30 p-3">
                        <div class="text-xs text-muted-foreground mb-1">Séries populaires</div>
                        <div class="space-y-2 text-xs">
                            <div class="flex justify-between"><span>Shadow Blade</span><span>4.8★</span></div>
                            <div class="flex justify-between"><span>Neon Runner</span><span>4.7★</span></div>
                            <div class="flex justify-between"><span>Moonlight</span><span>4.6★</span></div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- PLANS -->
            <section class="space-y-6">
                <div class="text-center space-y-2">
                    <h2 class="text-2xl font-semibold">Choisis ta voie</h2>
                    <p class="text-sm text-muted-foreground">Tarifs base XAF, conversion selon ta devise en interne.</p>
                </div>
                <div class="grid gap-4 md:grid-cols-3">
                    <Card v-for="plan in plans" :key="plan.name" class="h-full" :class="plan.tone === 'primary' ? 'border-primary' : plan.tone === 'secondary' ? 'border-secondary/50' : ''">
                        <CardHeader>
                            <div class="flex items-center justify-between">
                                <CardTitle>{{ plan.name }}</CardTitle>
                                <Badge v-if="plan.badge" :variant="plan.tone === 'primary' ? 'secondary' : 'outline'">{{ plan.badge }}</Badge>
                            </div>
                            <CardDescription>{{ plan.price }} / mois</CardDescription>
                        </CardHeader>
                        <CardContent class="space-y-3">
                            <ul class="space-y-2 text-sm text-muted-foreground">
                                <li v-for="p in plan.perks" :key="p" class="flex gap-2">
                                    <span>✓</span>
                                    <span>{{ p }}</span>
                                </li>
                            </ul>
                            <Button as-child class="w-full" :variant="plan.tone === 'primary' ? 'default' : plan.tone === 'secondary' ? 'secondary' : 'outline'">
                                <Link :href="plan.href">{{ plan.cta }}</Link>
                            </Button>
                        </CardContent>
                    </Card>
                </div>
            </section>

            <!-- CTA FINAL -->
            <section class="relative overflow-hidden rounded-3xl border bg-gradient-to-br from-primary/20 via-primary/10 to-secondary/15 p-10 text-center">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(255,255,255,0.12),transparent_45%)]"></div>
                <div class="relative space-y-4">
                    <h2 class="text-3xl font-bold">Prêt à plonger dans l’univers Omega Edition ?</h2>
                    <p class="text-sm text-muted-foreground">
                        Rejoins des milliers de lecteurs et créateurs. Aucune carte requise pour démarrer.
                    </p>
                    <div class="flex flex-col justify-center gap-3 sm:flex-row sm:items-center">
                        <Button as-child size="lg">
                            <Link href="/catalog">Commencer gratuitement</Link>
                        </Button>
                        <Button as-child size="lg" variant="outline" class="border-primary/40 text-primary hover:bg-primary/10">
                            <Link href="/about">Comparer les plans</Link>
                        </Button>
                    </div>
                    <p class="text-xs text-muted-foreground">Annulable à tout moment • Chapitres gratuits inclus</p>
                </div>
            </section>
        </div>
    </PublicLayout>
</template>
