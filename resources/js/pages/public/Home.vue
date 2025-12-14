<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import PublicLayout from '@/layouts/PublicLayout.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <PublicLayout
        title="Choisis ta voie"
        description="Omega Edition, plateforme hybride pour lecteurs et créateurs. Découvre, lis, publie."
        :minimal="true"
    >
        <div class="space-y-12">
            <section class="grid gap-8 rounded-2xl border bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 px-6 py-10 text-white shadow-xl lg:grid-cols-2 lg:px-10">
                <div class="space-y-5">
                    <Badge variant="outline" class="border-white/30 bg-white/10 text-white">Futurisme narratif</Badge>
                    <h1 class="text-3xl font-bold leading-tight sm:text-4xl">
                        Plonge dans l'univers Omega Edition.
                    </h1>
                    <p class="text-base text-white/80">
                        Deux chemins : lecteur ou créateur. Lis gratuitement les premiers chapitres, ou publie tes œuvres et construis ta communauté.
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <Button as-child class="bg-white text-slate-900 hover:bg-white/90">
                            <Link href="/catalog">Explorer le catalogue</Link>
                        </Button>
                        <Button variant="outline" as-child class="border-white/40 text-white hover:bg-white/10">
                            <Link href="/about">Découvrir les plans</Link>
                        </Button>
                    </div>
                    <div class="flex flex-wrap gap-3 text-sm text-white/70">
                        <span>Lecteurs : plan gratuit, premiers chapitres offerts.</span>
                        <span>Créateurs : publie, monétise, suit tes stats.</span>
                    </div>
                </div>
                <div class="grid gap-4 rounded-xl bg-white/5 p-6 text-sm text-white/80 lg:p-8">
                    <div class="rounded-lg border border-white/15 bg-white/5 p-4">
                        <div class="text-xs uppercase tracking-[0.14em] text-white/60">Lecteurs</div>
                        <div class="mt-2 text-lg font-semibold text-white">Accès gratuit aux premiers chapitres</div>
                        <ul class="mt-3 space-y-1">
                            <li>• Notifications sorties premium</li>
                            <li>• Mode découverte sans compte</li>
                            <li>• Abonnement premium : lecture complète, alertes nouveautés</li>
                        </ul>
                    </div>
                    <div class="rounded-lg border border-white/15 bg-white/5 p-4">
                        <div class="text-xs uppercase tracking-[0.14em] text-white/60">Créateurs</div>
                        <div class="mt-2 text-lg font-semibold text-white">Publie et trace ta destinée</div>
                        <ul class="mt-3 space-y-1">
                            <li>• Profil créateur, portfolio, moodboards</li>
                            <li>• Monétisation via abonnements et likes</li>
                            <li>• Analytics lecture, revenus mensuels</li>
                        </ul>
                    </div>
                </div>
            </section>

            <section class="grid gap-6 md:grid-cols-2">
                <Card class="border-primary/30">
                    <CardHeader>
                        <CardTitle>Voie Lecteur</CardTitle>
                        <CardDescription>Commence gratuitement, upgrade vers le premium.</CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="flex items-baseline gap-2">
                            <span class="text-3xl font-bold">Gratuit</span>
                            <span class="text-muted-foreground">premiers chapitres</span>
                        </div>
                        <ul class="space-y-1 text-sm text-muted-foreground">
                            <li>• Lecture libre des premiers chapitres</li>
                            <li>• Découverte sans compte sur le catalogue public</li>
                            <li>• Premium : accès complet, notifications, favoris synchronisés</li>
                        </ul>
                        <div class="flex gap-2">
                            <Button as-child>
                                <Link href="/register/reader">Choisir Lecteur</Link>
                            </Button>
                            <Button variant="ghost" as-child>
                                <Link href="/catalog">Voir le catalogue</Link>
                            </Button>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Voie Créateur</CardTitle>
                        <CardDescription>Publie, anime ta communauté, suis tes revenus.</CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="flex items-baseline gap-2">
                            <span class="text-3xl font-bold">0 XAF</span>
                            <span class="text-muted-foreground">pour commencer</span>
                        </div>
                        <ul class="space-y-1 text-sm text-muted-foreground">
                            <li>• Profil créateur complet (bio, portfolio, moodboard)</li>
                            <li>• Publication de séries, gestion des chapitres</li>
                            <li>• Outils de monétisation + analytics</li>
                        </ul>
                        <div class="flex gap-2">
                            <Button as-child variant="secondary">
                                <Link href="/register/creator">Choisir Créateur</Link>
                            </Button>
                            <Button variant="ghost" as-child>
                                <Link href="/about">En savoir plus</Link>
                            </Button>
                        </div>
                    </CardContent>
                </Card>
            </section>

            <section class="space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold">Catalogue public</h2>
                        <p class="text-sm text-muted-foreground">Parcourez sans compte, lisez le premier chapitre gratuit.</p>
                    </div>
                    <Button as-child>
                        <Link href="/catalog">Voir tout</Link>
                    </Button>
                </div>
                <div class="rounded-xl border bg-muted/40 p-4 text-sm text-muted-foreground">
                    Cette zone affiche le catalogue. Utilisez le bouton “Voir tout” pour naviguer.
                </div>
            </section>
        </div>
    </PublicLayout>
</template>
