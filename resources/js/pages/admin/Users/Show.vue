<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue';

const props = defineProps<{
    userId: string;
}>();
</script>

<template>
    <AdminLayout
        title="Profil utilisateur"
        :description="`Fiche de l’utilisateur #${props.userId}`"
    >
        <div class="rounded-lg border bg-card p-6 text-sm text-muted-foreground">
            Contenu mock : infos compte, rôles, historique d’abonnement et activités.
        </div>
    </AdminLayout>
</template>
