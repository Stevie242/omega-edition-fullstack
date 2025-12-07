<script setup lang="ts">
import ReaderLayout from '@/layouts/ReaderLayout.vue';

const props = defineProps<{
    seriesId: string;
}>();
</script>

<template>
    <ReaderLayout
        title="Fiche série"
        :description="`Détails de la série #${props.seriesId}`"
    >
        <div class="rounded-lg border bg-card p-6 text-sm text-muted-foreground">
            Contenu mock : synopsis, tags, liste des chapitres, CTA “Lire / Ajouter aux favoris”.
        </div>
    </ReaderLayout>
</template>
