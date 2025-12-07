<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue';
</script>

<template>
    <AdminLayout
        title="Monitoring plateforme"
        description="Santé du service, stats techniques, journaux clés."
    >
        <div class="rounded-lg border bg-card p-6 text-sm text-muted-foreground">
            Contenu mock : uptime, erreurs récentes, queues, vérifs manuelles.
        </div>
    </AdminLayout>
</template>
