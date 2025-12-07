<script setup lang="ts">
import CreatorLayout from '@/layouts/CreatorLayout.vue';
</script>

<template>
    <CreatorLayout
        title="Créer une série"
        description="Définir le titre, type (manga/webtoon), genres, statut et calendrier."
    >
        <div class="rounded-lg border bg-card p-6 text-sm text-muted-foreground">
            Formulaire mock : champs titre, synopsis, tags, langue, statut, planning de sorties.
        </div>
    </CreatorLayout>
</template>
