<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import PublicLayout from '@/layouts/PublicLayout.vue';
import { Link } from '@inertiajs/vue3';

interface HeroItem {
    id: string;
    title: string;
    cover_url?: string | null;
}

interface FreeItem {
    id: string;
    title: string;
    tag: string;
    cover_url?: string | null;
    free_chapter_id?: string | null;
}

interface StatItem {
    label: string;
    value: string;
}

const props = defineProps<{
    hero: HeroItem[];
    freeSeries: FreeItem[];
    stats: StatItem[];
}>();

const usp = [
    { title: 'Premier chapitre gratuit', desc: 'Testez les séries avant de vous abonner.' },
    { title: 'Soutien aux créateurs', desc: 'Une partie des revenus retourne aux auteurs.' },
    { title: 'Lecture fluide', desc: 'Optimisé mobile, tablette, desktop.' },
    { title: 'Catalogue évolutif', desc: 'Nouvelles sorties chaque semaine.' },
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
            <section class="relative overflow-hidden rounded-3xl border bg-gradient-to-br from-slate-900/80 via-background to-primary/10 shadow-2xl">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_20%,rgba(99,102,241,0.25),transparent_30%),radial-gradient(circle_at_80%_0%,rgba(14,165,233,0.2),transparent_30%),radial-gradient(circle_at_50%_100%,rgba(244,63,94,0.2),transparent_35%)]"></div>
                <div class="relative grid items-center gap-10 p-8 md:grid-cols-[1.1fr,0.9fr] md:p-12">
                    <div class="space-y-6">
                        <Badge variant="outline" class="border-primary/30 bg-primary/10 text-primary">
                            1er chapitre gratuit · Créateurs rémunérés
                        </Badge>
                        <h1 class="text-4xl font-bold leading-tight text-foreground sm:text-5xl">
                            Lis. Soutiens. Publie. Choisis ta voie.
                        </h1>
                        <p class="text-base text-muted-foreground md:text-lg">
                            Omega Edition te met aux commandes : découvre des séries inédites sans pub, lis gratuitement le premier chapitre, ou publie tes propres histoires et fais-les grandir.
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
                    <div class="relative flex justify-end">
                        <div class="relative h-80 w-full max-w-xl">
                            <div class="absolute inset-0 rounded-3xl border border-primary/30 bg-background/50 backdrop-blur shadow-[0_25px_80px_rgba(0,0,0,0.25)]"></div>
                            <div class="absolute inset-4 grid grid-cols-3 gap-3">
                                <div
                                    v-for="(item, idx) in hero"
                                    :key="item.id"
                                    class="overflow-hidden rounded-xl border border-border bg-muted/50 shadow-lg"
                                    :class="{
                                        'translate-y-2': idx % 3 === 1,
                                        'translate-y-4': idx % 3 === 2,
                                    }"
                                >
                                    <img v-if="item.cover_url" :src="item.cover_url" :alt="item.title" class="h-full w-full object-cover" />
                                    <div v-else class="flex h-full items-center justify-center text-xs text-muted-foreground">Cover</div>
                                </div>
                                <div
                                    class="col-span-3 mt-2 rounded-xl border border-primary/40 bg-primary/10 p-4 text-sm font-medium text-primary"
                                >
                                    +{{ hero.length }} séries à explorer cette semaine
                                </div>
                            </div>
                            <div class="absolute -right-4 -top-4 h-14 w-14 rounded-full border border-primary/40 bg-primary/20 blur-xl"></div>
                            <div class="absolute -left-6 bottom-10 h-16 w-16 rounded-full border border-secondary/30 bg-secondary/20 blur-xl"></div>
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
                    <h2 class="text-2xl font-semibold">Pourquoi rester ici ?</h2>
                    <p class="text-sm text-muted-foreground">Accroche-toi dès le premier chapitre, soutiens les auteurs et garde une expérience fluide.</p>
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
                    <h2 class="text-xl font-semibold">Découvre avant de t’engager</h2>
                    <p class="text-sm text-muted-foreground">
                        Le premier chapitre de chaque série est offert, sans compte. Goûte l’univers, puis passe premium pour tout débloquer.
                    </p>
                    <div class="space-y-2 text-sm text-muted-foreground">
                        <div class="flex items-center gap-2">✓ Accès sans compte</div>
                        <div class="flex items-center gap-2">✓ Zéro pub intrusive</div>
                        <div class="flex items-center gap-2">✓ Upgrade premium pour la suite</div>
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
                    <Card v-for="t in freeSeries" :key="t.id" class="h-full border-primary/20 bg-primary/5">
                        <CardContent class="space-y-2 p-4">
                            <div class="h-32 overflow-hidden rounded-md bg-muted/60">
                                <img v-if="t.cover_url" :src="t.cover_url" :alt="t.title" class="h-full w-full object-cover" />
                            </div>
                            <div class="text-sm font-semibold">{{ t.title }}</div>
                            <Badge variant="secondary">{{ t.tag }}</Badge>
                            <Button v-if="t.free_chapter_id" variant="ghost" size="sm" class="w-full" as-child>
                                <Link :href="`/catalog/${t.id}/chapters/${t.free_chapter_id}`">Lire le chapitre</Link>
                            </Button>
                        </CardContent>
                    </Card>
                </div>
            </section>

            <!-- CREATOR CTA -->
            <section class="grid gap-8 rounded-2xl border bg-gradient-to-br from-secondary/10 via-background to-secondary/20 p-6 md:grid-cols-2 md:p-8">
                <div class="space-y-4">
                    <Badge variant="secondary">Pour les créateurs</Badge>
                    <h2 class="text-2xl font-semibold">Publie, monétise, suis tes stats.</h2>
                    <p class="text-sm text-muted-foreground">
                        Monte ton studio : publie tes chapitres, mesure l’engagement, partage jusqu’à 70% des revenus et profite d’un support prioritaire.
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
                    <p class="text-sm text-muted-foreground">Base XAF, conversion auto selon ta devise. Passe à l’action en un clic.</p>
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
                    <h2 class="text-3xl font-bold">Prêt à passer à l’action ?</h2>
                    <p class="text-sm text-muted-foreground">
                        Rejoins des milliers de lecteurs et créateurs. Démarre sans carte, dès maintenant.
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
