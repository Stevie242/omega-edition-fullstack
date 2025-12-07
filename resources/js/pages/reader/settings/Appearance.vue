<script setup lang="ts">
import ReaderLayout from '@/layouts/ReaderLayout.vue';
</script>

<template>
    <ReaderLayout
        title="Apparence"
        description="Thème clair/sombre et préférences d’affichage pour la lecture."
        :breadcrumbs="[
            { title: 'Paramètres', href: '/reader/settings/profile' },
            { title: 'Apparence' },
        ]"
    >
        <div class="rounded-lg border bg-card p-6 text-sm text-muted-foreground">
            Contenu mock : sélection de thème, taille de police, mode lecture.
        </div>
    </ReaderLayout>
</template>
