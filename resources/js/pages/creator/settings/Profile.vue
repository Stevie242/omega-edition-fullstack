<script setup lang="ts">
import CreatorLayout from '@/layouts/CreatorLayout.vue';
</script>

<template>
    <CreatorLayout
        title="Profil créateur"
        description="Mettre à jour vos informations, identité et préférences de contact."
        :breadcrumbs="[
            { title: 'Paramètres', href: '/creator/settings/profile' },
            { title: 'Profil' },
        ]"
    >
        <div class="rounded-lg border bg-card p-6 text-sm text-muted-foreground">
            Contenu mock : formulaire profil (nom, email, studio, bio).
        </div>
    </CreatorLayout>
</template>
