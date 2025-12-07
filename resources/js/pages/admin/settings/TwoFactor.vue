<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue';
</script>

<template>
    <AdminLayout
        title="Double authentification"
        description="Gérer la 2FA pour votre compte administrateur."
        :breadcrumbs="[
            { title: 'Paramètres', href: '/admin/settings/profile' },
            { title: '2FA' },
        ]"
    >
        <div class="rounded-lg border bg-card p-6 text-sm text-muted-foreground">
            Contenu mock : activation/désactivation 2FA, codes de récupération.
        </div>
    </AdminLayout>
</template>
