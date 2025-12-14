<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import PublicLayout from '@/layouts/PublicLayout.vue';
import { Link } from '@inertiajs/vue3';

interface Series {
    id: string;
    title: string;
    type: string;
    status: string;
    format: string;
    language: string;
    synopsis?: string | null;
    cover_url?: string | null;
    hero_url?: string | null;
    creator?: { id?: string; name?: string | null } | null;
    tags?: { name: string; slug: string }[];
    free_chapter_id?: string | null;
    free_chapter_number?: number | null;
    free_chapter_preview?: string | null;
}

const props = defineProps<{
    series: Series;
}>();

const statusLabel = (status: string) => {
    if (status === 'ongoing') return 'En cours';
    if (status === 'hiatus') return 'En pause';
    if (status === 'completed') return 'Terminé';
    return status;
};
</script>

<template>
    <PublicLayout
        :title="series.title"
        description="Fiche publique de la série."
    >
        <div class="space-y-6">
            <div class="overflow-hidden rounded-xl border bg-card">
                <div
                    class="h-48 w-full bg-gradient-to-r from-slate-900/90 to-slate-700/60"
                    :style="series.hero_url ? `background-image: linear-gradient(90deg, rgba(15,23,42,0.85), rgba(15,23,42,0.45)), url(${series.hero_url}); background-size: cover; background-position: center;` : ''"
                ></div>
                <div class="-mt-16 flex flex-col gap-4 px-6 pb-6 md:flex-row md:items-end">
                    <div class="h-40 w-32 overflow-hidden rounded-lg border bg-muted shadow-lg">
                        <img v-if="series.cover_url" :src="series.cover_url" :alt="series.title" class="h-full w-full object-cover" />
                        <div v-else class="flex h-full items-center justify-center text-sm text-muted-foreground">Couverture</div>
                    </div>
                    <div class="flex-1 space-y-2">
                        <h1 class="text-2xl font-semibold leading-tight">{{ series.title }}</h1>
                        <p class="text-sm text-muted-foreground">
                            {{ series.creator?.name ?? 'Auteur inconnu' }} · {{ series.type }} · {{ series.language.toUpperCase() }}
                        </p>
                        <div class="flex flex-wrap gap-2">
                            <Badge variant="secondary">{{ statusLabel(series.status) }}</Badge>
                            <Badge variant="outline">{{ series.format }}</Badge>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2">
                        <Button class="w-full" :disabled="!series.free_chapter_id" as-child>
                            <Link :href="series.free_chapter_id ? `/catalog/${series.id}/chapters/${series.free_chapter_id}` : '#'">
                                Lire le chapitre gratuit
                            </Link>
                        </Button>
                        <Button variant="outline" class="w-full" as-child>
                            <Link href="/register/reader">Créer un compte pour tout lire</Link>
                        </Button>
                    </div>
                </div>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>Synopsis</CardTitle>
                    <CardDescription>Présentation rapide.</CardDescription>
                </CardHeader>
                <CardContent class="text-sm leading-relaxed text-muted-foreground">
                    {{ series.synopsis ?? 'Synopsis non fourni.' }}
                </CardContent>
            </Card>

            <Card v-if="series.free_chapter_preview">
                <CardHeader>
                    <CardTitle>Aperçu du chapitre gratuit</CardTitle>
                    <CardDescription>Première page disponible.</CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="overflow-hidden rounded-lg border bg-muted">
                        <img :src="series.free_chapter_preview" alt="Aperçu" class="w-full" />
                    </div>
                </CardContent>
            </Card>

            <div class="flex flex-wrap gap-2">
                <Badge v-for="tag in series.tags ?? []" :key="tag.slug" variant="secondary">{{ tag.name }}</Badge>
            </div>
        </div>
    </PublicLayout>
</template>
