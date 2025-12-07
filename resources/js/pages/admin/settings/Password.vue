<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue';
</script>

<template>
    <AdminLayout
        title="Sécurité - Mot de passe"
        description="Mettre à jour votre mot de passe administrateur."
        :breadcrumbs="[
            { title: 'Paramètres', href: '/admin/settings/profile' },
            { title: 'Mot de passe' },
        ]"
    >
        <div class="rounded-lg border bg-card p-6 text-sm text-muted-foreground">
            Contenu mock : formulaire changement de mot de passe.
        </div>
    </AdminLayout>
</template>
