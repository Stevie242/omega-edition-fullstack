<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue';
</script>

<template>
    <AdminLayout
        title="Sécurité avancée"
        description="Journal d’accès, rôles et contrôles additionnels."
        :breadcrumbs="[
            { title: 'Paramètres', href: '/admin/settings/profile' },
            { title: 'Sécurité' },
        ]"
    >
        <div class="rounded-lg border bg-card p-6 text-sm text-muted-foreground">
            Contenu mock : audit log, rôles, réglages sécurité avancés.
        </div>
    </AdminLayout>
</template>
