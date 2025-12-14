<script setup lang="ts">
import SupportDesk from '@/components/SupportDesk.vue';
import SupportPage from '@/components/SupportPage.vue';
import ReaderLayout from '@/layouts/ReaderLayout.vue';

interface Ticket {
    id: number;
    subject: string;
    message: string;
    status: string;
    resolution_note?: string | null;
    created_at?: string;
    updated_at?: string;
}

defineProps<{
    tickets: Ticket[];
}>();

const items = [
    {
        title: 'FAQ lecteur',
        description: 'Acces aux series, gestion des favoris et historique, tips de lecture.',
        href: '/reader/faq',
        actionLabel: 'Voir la FAQ',
        badge: 'Docs',
    },
    {
        title: 'Ticket support',
        description: 'Problemes de lecture, abonnement ou paiement. Reponse sous 72h ouvrables.',
        href: 'mailto:support@omega-edition.com?subject=Ticket%20Support%20Lecteur',
        actionLabel: 'Ouvrir un ticket',
        badge: 'Prioritaire',
    },
    {
        title: 'Contact direct',
        description: 'support@omega-edition.com en precisant votre ID lecteur pour un suivi rapide.',
        href: 'mailto:support@omega-edition.com?subject=Support%20Lecteur',
        actionLabel: 'Envoyer un email',
        badge: 'Email',
    },
];
</script>

<template>
    <ReaderLayout
        title="Support lecteur"
        description="Guides, tickets et contact direct pour vos lectures et abonnements."
        :breadcrumbs="[{ title: 'Support' }]"
    >
        <div class="space-y-8">
            <SupportPage
                audience="Espace lecteur"
                subtitle="Une question sur vos lectures, votre abonnement ou vos paiements ?"
                :items="items"
                extra-note="Ajoutez votre ID lecteur et le titre concerne pour accelerer la resolution."
            />
            <SupportDesk submitUrl="/reader/support" :tickets="tickets" />
        </div>
    </ReaderLayout>
</template>
