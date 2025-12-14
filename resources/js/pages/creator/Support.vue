<script setup lang="ts">
import SupportDesk from '@/components/SupportDesk.vue';
import SupportPage from '@/components/SupportPage.vue';
import CreatorLayout from '@/layouts/CreatorLayout.vue';

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
        title: 'Centre d aide',
        description: 'Guides rapides : publier un chapitre, gerer les payoffs, configurer les taxes.',
        href: '/creator/faq',
        actionLabel: 'Ouvrir les guides',
        badge: 'Docs',
    },
    {
        title: 'Ticket support',
        description: 'Signalez un bug ou demandez une moderation. Reponse sous 72h ouvrables.',
        href: 'mailto:support@omega-edition.com?subject=Ticket%20Support%20Createur',
        actionLabel: 'Ouvrir un ticket',
        badge: 'Prioritaire',
    },
    {
        title: 'Contact direct',
        description: 'support@omega-edition.com avec votre ID createur pour un suivi plus rapide.',
        href: 'mailto:support@omega-edition.com?subject=Support%20Createur',
        actionLabel: 'Envoyer un email',
        badge: 'Email',
    },
];
</script>

<template>
    <CreatorLayout
        title="Support createur"
        description="Acces aux guides, tickets et contact direct pour publier et encaisser sereinement."
        :breadcrumbs="[{ title: 'Support' }]"
    >
        <div class="space-y-8">
            <SupportPage
                audience="Espace createur"
                subtitle="Questions sur la publication, la monetisation ou la moderation ?"
                :items="items"
                extra-note="Incluez vos ID de serie/chapitre et des captures pour accelerer la resolution."
            />
            <SupportDesk submitUrl="/creator/support" :tickets="tickets" />
        </div>
    </CreatorLayout>
</template>
