<script setup lang="ts">
import CreatorLayout from '@/layouts/CreatorLayout.vue';
import { Link } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';

const props = defineProps<{
    seriesId: string;
}>();
</script>

<template>
    <CreatorLayout
        title="Créer un chapitre"
        :description="`Associer à la série #${props.seriesId}, uploader les pages et planifier la sortie.`"
    >
        <div class="space-y-3">
            <Link
                class="inline-flex items-center gap-2 rounded-md border px-3 py-2 text-sm hover:border-primary hover:text-primary"
                :href="`/creator/series/${props.seriesId}/chapters`"
            >
                <ArrowLeft class="h-4 w-4" />
                Retour à la liste des chapitres
            </Link>
            <div class="rounded-lg border bg-card p-6 text-sm text-muted-foreground">
                Formulaire mock : titre du chapitre, numéro, upload des pages, date/heure de publication.
            </div>
        </div>
    </CreatorLayout>
</template>
