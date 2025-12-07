<script setup lang="ts">
import ReaderLayout from '@/layouts/ReaderLayout.vue';
</script>

<template>
    <ReaderLayout
        title="Profil lecteur"
        description="Mettre à jour vos informations et préférences de contact."
        :breadcrumbs="[
            { title: 'Paramètres', href: '/reader/settings/profile' },
            { title: 'Profil' },
        ]"
    >
        <div class="rounded-lg border bg-card p-6 text-sm text-muted-foreground">
            Contenu mock : formulaire profil (nom, email, pseudo).
        </div>
    </ReaderLayout>
</template>
