<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue';
</script>

<template>
    <AdminLayout
        title="Profil admin"
        description="Mettre à jour vos informations d’administrateur."
        :breadcrumbs="[
            { title: 'Paramètres', href: '/admin/settings/profile' },
            { title: 'Profil' },
        ]"
    >
        <div class="rounded-lg border bg-card p-6 text-sm text-muted-foreground">
            Contenu mock : formulaire profil (nom, email, rôle).
        </div>
    </AdminLayout>
</template>
