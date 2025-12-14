<script setup lang="ts">
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import ReaderLayout from '@/layouts/ReaderLayout.vue';

const faq = [
    {
        title: 'Comment trouver une série ?',
        answer: 'Utilisez le catalogue Lecteur > Catalogue, filtrez par type/statut/format. Le premier chapitre est gratuit.',
    },
    {
        title: 'Favoris et historique',
        answer: 'Ajoutez une série en favori depuis sa fiche. L’historique de lecture se met à jour automatiquement.',
    },
    {
        title: 'Gestion de l’abonnement',
        answer: 'Dans Abonnement, choisissez le plan. Vous pouvez résilier avant l’échéance pour stopper le renouvellement.',
    },
    {
        title: 'Problème de lecture ou de page',
        answer: 'Videz le cache puis rechargez. Si le problème persiste, ouvrez un ticket Support avec l’ID du chapitre.',
    },
    {
        title: 'Sécurité du compte',
        answer: 'Activez la double authentification dans Paramètres > Sécurité. Ne partagez pas vos identifiants.',
    },
];
</script>

<template>
    <ReaderLayout
        title="FAQ lecteur"
        description="Questions fréquentes sur la lecture, les favoris et l’abonnement."
        :breadcrumbs="[{ title: 'Support', href: '/reader/support' }, { title: 'FAQ' }]"
    >
        <div class="grid gap-4 md:grid-cols-2">
            <Card v-for="item in faq" :key="item.title" class="bg-card/80 shadow-sm">
                <CardHeader>
                    <CardTitle class="text-base">{{ item.title }}</CardTitle>
                </CardHeader>
                <CardContent class="text-sm text-muted-foreground">
                    {{ item.answer }}
                </CardContent>
            </Card>
        </div>
    </ReaderLayout>
</template>
