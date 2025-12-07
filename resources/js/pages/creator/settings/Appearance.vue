<script setup lang="ts">
import CreatorLayout from '@/layouts/CreatorLayout.vue';
</script>

<template>
    <CreatorLayout
        title="Apparence créateur"
        description="Thème, modes clair/sombre et préférences UI."
        :breadcrumbs="[
            { title: 'Paramètres', href: '/creator/settings/profile' },
            { title: 'Apparence' },
        ]"
    >
        <div class="rounded-lg border bg-card p-6 text-sm text-muted-foreground">
            Contenu mock : choix de thème, accents, options d’interface.
        </div>
    </CreatorLayout>
</template>
