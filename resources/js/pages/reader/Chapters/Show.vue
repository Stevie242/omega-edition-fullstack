<script setup lang="ts">
import ReaderLayout from '@/layouts/ReaderLayout.vue';

const props = defineProps<{
    chapterId: string;
}>();
</script>

<template>
    <ReaderLayout
        title="Lecture"
        :description="`Lecteur pour le chapitre #${props.chapterId}`"
    >
        <div class="rounded-lg border bg-card p-6 text-sm text-muted-foreground">
            Contenu mock : zone de lecture, navigation précédente/suivante, bouton favoris.
        </div>
    </ReaderLayout>
</template>
