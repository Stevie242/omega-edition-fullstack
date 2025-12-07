<script setup lang="ts">
import CreatorLayout from '@/layouts/CreatorLayout.vue';

const props = defineProps<{
    seriesId: string;
}>();
</script>

<template>
    <CreatorLayout
        title="Détail série"
        :description="`Vue détaillée de la série #${props.seriesId}`"
    >
        <div class="rounded-lg border bg-card p-6 text-sm text-muted-foreground">
            Contenu mock : résumé, performances, derniers chapitres, boutons publier/planifier.
        </div>
    </CreatorLayout>
</template>
