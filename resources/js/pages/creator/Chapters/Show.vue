<script setup lang="ts">
import CreatorLayout from '@/layouts/CreatorLayout.vue';

const props = defineProps<{
    chapterId: string;
}>();
</script>

<template>
    <CreatorLayout
        title="Détail chapitre"
        :description="`Vue détaillée du chapitre #${props.chapterId}`"
    >
        <div class="rounded-lg border bg-card p-6 text-sm text-muted-foreground">
            Contenu mock : aperçu des pages, statut de publication, indicateurs de performance.
        </div>
    </CreatorLayout>
</template>
