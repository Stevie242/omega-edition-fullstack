<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue';

const props = defineProps<{
    userId: string;
}>();
</script>

<template>
    <AdminLayout
        title="Éditer un utilisateur"
        :description="`Mettre à jour le compte #${props.userId}`"
    >
        <div class="rounded-lg border bg-card p-6 text-sm text-muted-foreground">
            Formulaire mock : rôles, statut, options de sécurité.
        </div>
    </AdminLayout>
</template>
