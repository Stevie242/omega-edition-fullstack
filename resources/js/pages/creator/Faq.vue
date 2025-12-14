<script setup lang="ts">
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import CreatorLayout from '@/layouts/CreatorLayout.vue';

const faq = [
    {
        title: 'Comment publier un chapitre ?',
        answer: 'Depuis Series > selectionnez la serie > Ajouter un chapitre. Uploadez les pages dans l ordre et publiez.',
    },
    {
        title: 'Comment mettre à jour une couverture ou un hero ?',
        answer: 'Ouvrez la serie, modifiez les champs cover/hero, enregistrez. Les lecteurs verront la mise à jour immédiatement.',
    },
    {
        title: 'Paiements : quand et comment ?',
        answer: 'Configurez vos infos fiscales dans Payouts > Tax. Les versements se font selon votre seuil et calendrier défini dans Payouts.',
    },
    {
        title: 'Modération des contenus',
        answer: 'Les contenus illicites, haineux ou sans droits sont retirés. Les comptes peuvent être suspendus en cas de violation.',
    },
    {
        title: 'Support prioritaire',
        answer: 'Ouvrez un ticket depuis la page Support pour un suivi traçable. Incluez l’ID de série/chapitre et des captures.',
    },
];
</script>

<template>
    <CreatorLayout
        title="FAQ createur"
        description="Les réponses rapides pour publier, monétiser et être payé."
        :breadcrumbs="[{ title: 'Support', href: '/creator/support' }, { title: 'FAQ' }]"
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
    </CreatorLayout>
</template>
