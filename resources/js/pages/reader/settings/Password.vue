<script setup lang="ts">
import ReaderLayout from '@/layouts/ReaderLayout.vue';
</script>

<template>
    <ReaderLayout
        title="Sécurité - Mot de passe"
        description="Mettre à jour votre mot de passe."
        :breadcrumbs="[
            { title: 'Paramètres', href: '/reader/settings/profile' },
            { title: 'Mot de passe' },
        ]"
    >
        <div class="rounded-lg border bg-card p-6 text-sm text-muted-foreground">
            Contenu mock : formulaire changement de mot de passe.
        </div>
    </ReaderLayout>
</template>
