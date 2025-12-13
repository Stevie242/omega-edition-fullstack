<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import ReaderLayout from '@/layouts/ReaderLayout.vue';
import { Link } from '@inertiajs/vue3';

interface HistoryItem {
    id: string;
    title: string;
    type: string;
    status: string;
    format: string;
    language: string;
    cover_url?: string | null;
    last_read_at?: string | null;
    last_chapter_id?: string | null;
    last_chapter_number?: number | null;
    start_chapter_id?: string | null;
    start_chapter_number?: number | null;
}

const props = defineProps<{
    history: HistoryItem[];
}>();

const statusLabel = (status: string) => {
    if (status === 'ongoing') return 'En cours';
    if (status === 'hiatus') return 'En pause';
    if (status === 'completed') return 'Terminé';
    return status;
};
</script>

<template>
    <ReaderLayout
        title="Historique"
        description="Vos dernières lectures et où les reprendre."
    >
        <div v-if="!history.length" class="rounded-lg border bg-card p-6 text-sm text-muted-foreground">
            Aucun historique pour l'instant. Commencez une série pour la voir apparaître ici.
        </div>

        <div v-else class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            <Card v-for="item in history" :key="item.id" class="overflow-hidden">
                <div class="h-44 w-full bg-muted">
                    <img v-if="item.cover_url" :src="item.cover_url" :alt="item.title" class="h-full w-full object-cover" />
                </div>
                <CardHeader class="space-y-2">
                    <div class="flex flex-wrap items-start justify-between gap-2">
                        <div>
                            <CardTitle class="text-lg leading-tight">{{ item.title }}</CardTitle>
                            <CardDescription class="text-sm">
                                Dernière lecture : {{ item.last_read_at ? item.last_read_at : '—' }}
                            </CardDescription>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <Badge variant="secondary">{{ statusLabel(item.status) }}</Badge>
                            <Badge variant="outline">{{ item.format }}</Badge>
                            <Badge variant="outline">{{ item.type }}</Badge>
                        </div>
                    </div>
                </CardHeader>
                <CardContent class="flex flex-col gap-2">
                    <div class="text-sm text-muted-foreground">
                        Langue : {{ item.language.toUpperCase() }}
                        <span v-if="item.last_chapter_number"> · Chapitre {{ item.last_chapter_number }}</span>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <Button class="flex-1" :disabled="!item.start_chapter_id" as-child>
                            <Link :href="item.start_chapter_id ? `/reader/chapters/${item.start_chapter_id}` : '#'">
                                <span v-if="item.last_chapter_id">Reprendre</span>
                                <span v-else>Commencer</span>
                            </Link>
                        </Button>
                        <Button variant="outline" as-child>
                            <Link :href="`/reader/series/${item.id}`">Voir la série</Link>
                        </Button>
                    </div>
                </CardContent>
            </Card>
        </div>
    </ReaderLayout>
</template>
