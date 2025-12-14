<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import PublicLayout from '@/layouts/PublicLayout.vue';
</script>

<template>
    <PublicLayout
        title="À propos d’Omega Edition"
        description="Plateforme narrative en mode jeu vidéo : choisis ta voie, Lecteur ou Créateur."
    >
        <div class="space-y-8">
            <Card>
                <CardHeader>
                    <CardTitle>Deux voies, une même galaxie</CardTitle>
                    <CardDescription>Lecteurs et créateurs coexistent, chacun avec ses leviers.</CardDescription>
                </CardHeader>
                <CardContent class="space-y-4 text-sm text-muted-foreground">
                    <p>
                        Omega Edition te propose une expérience “choisis ta destinée” : découvre des séries originales ou
                        publie tes propres univers. Interface futuriste, progression par chapitres, analytics et communauté.
                    </p>
                </CardContent>
            </Card>

            <div class="grid gap-4 md:grid-cols-2">
                <Card class="border-primary/30">
                    <CardHeader>
                        <div class="flex items-center gap-2">
                            <Badge>Lecteur</Badge>
                            <CardTitle>Voie Lecteur</CardTitle>
                        </div>
                        <CardDescription>Commence gratuit, passe en premium pour l’accès complet.</CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-2 text-sm text-muted-foreground">
                        <p>• Accès public : premiers chapitres gratuits, aucune inscription requise.</p>
                        <p>• Plan gratuit inscrit : synchro favoris/historique, mais accès limité aux premiers chapitres.</p>
                        <p>• Plan premium (XAF de base, conversion selon devise) : lecture complète, notifications, favoris, reprises.</p>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader>
                        <div class="flex items-center gap-2">
                            <Badge variant="secondary">Créateur</Badge>
                            <CardTitle>Voie Créateur</CardTitle>
                        </div>
                        <CardDescription>Publie, monétise, analyse tes performances.</CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-2 text-sm text-muted-foreground">
                        <p>• Profil créateur (bio, portfolio, moodboard, avatar/couverture).</p>
                        <p>• Publication de séries, gestion des chapitres, mises en avant.</p>
                        <p>• Revenus via abonnements lecteurs ; analytics lecture/likes.</p>
                    </CardContent>
                </Card>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>Tarifs et conversions</CardTitle>
                    <CardDescription>Base en XAF, conversion dynamique selon la devise préférée.</CardDescription>
                </CardHeader>
                <CardContent class="space-y-2 text-sm text-muted-foreground">
                    <p>• Plan gratuit : premiers chapitres uniquement.</p>
                    <p>• Plan premium : accès complet, notifications, reprise cross-device.</p>
                    <p>• La devise de base est le XAF ; une utilitaire de conversion adapte les montants à la devise de l’utilisateur.</p>
                </CardContent>
            </Card>
        </div>
    </PublicLayout>
</template>
