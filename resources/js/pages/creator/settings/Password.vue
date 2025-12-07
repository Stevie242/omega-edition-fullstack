<script setup lang="ts">
import CreatorLayout from '@/layouts/CreatorLayout.vue';
</script>

<template>
    <CreatorLayout
        title="Sécurité - Mot de passe"
        description="Mettre à jour votre mot de passe pour sécuriser l’accès créateur."
        :breadcrumbs="[
            { title: 'Paramètres', href: '/creator/settings/profile' },
            { title: 'Mot de passe' },
        ]"
    >
        <div class="rounded-lg border bg-card p-6 text-sm text-muted-foreground">
            Contenu mock : formulaire changement de mot de passe (actuel/nouveau).
        </div>
    </CreatorLayout>
</template>
