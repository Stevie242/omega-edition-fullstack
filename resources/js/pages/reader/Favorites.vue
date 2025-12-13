<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import ReaderLayout from '@/layouts/ReaderLayout.vue';
import { Link } from '@inertiajs/vue3';

interface Favorite {
    id: string;
    title: string;
    type: string;
    status: string;
    format: string;
    language: string;
    cover_url?: string | null;
    creator?: { id?: string; name?: string | null } | null;
    tags?: { name: string; slug: string }[];
    last_read_chapter_id?: string | null;
    last_read_chapter_number?: number | null;
    start_chapter_id?: string | null;
    start_chapter_number?: number | null;
}

const props = defineProps<{
    favorites: Favorite[];
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
        title="Favoris"
        description="Vos séries suivies, prêtes à reprendre."
    >
        <div v-if="!favorites.length" class="rounded-lg border bg-card p-6 text-sm text-muted-foreground">
            Aucun favori pour l'instant. Parcourez le catalogue et ajoutez vos séries préférées.
        </div>

        <div v-else class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            <Card v-for="fav in favorites" :key="fav.id" class="overflow-hidden">
                <div class="h-48 w-full bg-muted">
                    <img v-if="fav.cover_url" :src="fav.cover_url" :alt="fav.title" class="h-full w-full object-cover" />
                </div>
                <CardHeader class="space-y-3">
                    <div class="flex flex-wrap items-start justify-between gap-2">
                        <div>
                            <CardTitle class="text-lg leading-tight">{{ fav.title }}</CardTitle>
                            <CardDescription class="text-sm">
                                {{ fav.creator?.name ?? 'Auteur inconnu' }}
                            </CardDescription>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <Badge variant="secondary">{{ statusLabel(fav.status) }}</Badge>
                            <Badge variant="outline">{{ fav.format }}</Badge>
                            <Badge variant="outline">{{ fav.type }}</Badge>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <Badge v-for="tag in fav.tags ?? []" :key="tag.slug" variant="secondary">{{ tag.name }}</Badge>
                    </div>
                </CardHeader>
                <CardContent class="flex flex-col gap-2">
                    <div class="flex flex-wrap gap-2 text-sm text-muted-foreground">
                        <span>Langue : {{ fav.language.toUpperCase() }}</span>
                        <span v-if="fav.last_read_chapter_number">· Reprise au ch. {{ fav.last_read_chapter_number }}</span>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <Button class="flex-1" :disabled="!fav.start_chapter_id" as-child>
                            <Link :href="fav.start_chapter_id ? `/reader/chapters/${fav.start_chapter_id}` : '#'">
                                <span v-if="fav.last_read_chapter_id">Reprendre</span>
                                <span v-else>Commencer</span>
                            </Link>
                        </Button>
                        <Button
                            variant="outline"
                            as="button"
                            @click.prevent="$inertia.post(`/reader/series/${fav.id}/favorite?_method=delete`)"
                        >
                            Retirer
                        </Button>
                    </div>
                    <Button variant="ghost" as-child class="justify-start px-0 text-sm">
                        <Link :href="`/reader/series/${fav.id}`">Voir la fiche</Link>
                    </Button>
                </CardContent>
            </Card>
        </div>
    </ReaderLayout>
</template>
