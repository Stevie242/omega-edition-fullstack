<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue';

const props = defineProps<{
    creatorId: string;
}>();
</script>

<template>
    <AdminLayout
        title="Fiche créateur"
        :description="`Détails du créateur/studio #${props.creatorId}`"
    >
        <div class="rounded-lg border bg-card p-6 text-sm text-muted-foreground">
            Contenu mock : identité, séries, revenus, actions de supervision.
        </div>
    </AdminLayout>
</template>
