<script setup lang="ts">
import CreatorLayout from '@/layouts/CreatorLayout.vue';

const props = defineProps<{
    seriesId: string;
}>();
</script>

<template>
    <CreatorLayout
        title="Chapitres"
        :description="`Liste des chapitres pour la série #${props.seriesId}`"
    >
        <div class="rounded-lg border bg-card p-6 text-sm text-muted-foreground">
            Contenu mock : tableau des chapitres, statuts (brouillon/publié), dates de sortie.
        </div>
    </CreatorLayout>
</template>
