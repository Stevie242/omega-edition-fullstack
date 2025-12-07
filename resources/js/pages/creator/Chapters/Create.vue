<script setup lang="ts">
import CreatorLayout from '@/layouts/CreatorLayout.vue';

const props = defineProps<{
    seriesId: string;
}>();
</script>

<template>
    <CreatorLayout
        title="Créer un chapitre"
        :description="`Associer à la série #${props.seriesId}, uploader les pages et planifier la sortie.`"
    >
        <div class="rounded-lg border bg-card p-6 text-sm text-muted-foreground">
            Formulaire mock : titre du chapitre, numéro, upload des pages, date/heure de publication.
        </div>
    </CreatorLayout>
</template>
