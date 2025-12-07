<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue';
</script>

<template>
    <AdminLayout
        title="Apparence"
        description="Thème et préférences d’affichage pour l’interface admin."
        :breadcrumbs="[
            { title: 'Paramètres', href: '/admin/settings/profile' },
            { title: 'Apparence' },
        ]"
    >
        <div class="rounded-lg border bg-card p-6 text-sm text-muted-foreground">
            Contenu mock : choix de thème, réglages UI.
        </div>
    </AdminLayout>
</template>
