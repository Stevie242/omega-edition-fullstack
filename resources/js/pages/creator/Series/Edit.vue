<script setup lang="ts">
import CreatorLayout from '@/layouts/CreatorLayout.vue';

const props = defineProps<{
    seriesId: string;
}>();
</script>

<template>
    <CreatorLayout
        title="Éditer une série"
        :description="`Série #${props.seriesId} — modifier métadonnées, visuels et planning.`"
    >
        <div class="rounded-lg border bg-card p-6 text-sm text-muted-foreground">
            Formulaire mock : mise à jour des infos de la série, couverture, accès aux chapitres.
        </div>
    </CreatorLayout>
</template>
