<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue';

const props = defineProps<{
    seriesId: string;
}>();
</script>

<template>
    <AdminLayout
        title="Fiche série (admin)"
        :description="`Validation et contrôles pour la série #${props.seriesId}`"
    >
        <div class="rounded-lg border bg-card p-6 text-sm text-muted-foreground">
            Contenu mock : aperçu contenu, checklist qualité, boutons valider/rejeter.
        </div>
    </AdminLayout>
</template>
