<script setup lang="ts">
import ReaderLayout from '@/layouts/ReaderLayout.vue';
</script>

<template>
    <ReaderLayout
        title="Double authentification"
        description="Activer ou gérer la 2FA pour sécuriser votre compte."
        :breadcrumbs="[
            { title: 'Paramètres', href: '/reader/settings/profile' },
            { title: '2FA' },
        ]"
    >
        <div class="rounded-lg border bg-card p-6 text-sm text-muted-foreground">
            Contenu mock : activation/désactivation 2FA, codes de récupération.
        </div>
    </ReaderLayout>
</template>
