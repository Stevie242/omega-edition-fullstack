<script setup lang="ts">
import CreatorLayout from '@/layouts/CreatorLayout.vue';
</script>

<template>
    <CreatorLayout
        title="Double authentification"
        description="Activer ou gérer la 2FA pour sécuriser votre studio."
        :breadcrumbs="[
            { title: 'Paramètres', href: '/creator/settings/profile' },
            { title: '2FA' },
        ]"
    >
        <div class="rounded-lg border bg-card p-6 text-sm text-muted-foreground">
            Contenu mock : activation/désactivation 2FA, codes de récupération.
        </div>
    </CreatorLayout>
</template>
