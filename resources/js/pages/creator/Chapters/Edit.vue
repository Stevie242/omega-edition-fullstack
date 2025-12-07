<script setup lang="ts">
import CreatorLayout from '@/layouts/CreatorLayout.vue';

const props = defineProps<{
    chapterId: string;
}>();
</script>

<template>
    <CreatorLayout
        title="Éditer un chapitre"
        :description="`Chapitre #${props.chapterId} — mise à jour contenu, pages et publication.`"
    >
        <div class="rounded-lg border bg-card p-6 text-sm text-muted-foreground">
            Formulaire mock : remplacement des pages, notes éditoriales, planification.
        </div>
    </CreatorLayout>
</template>
