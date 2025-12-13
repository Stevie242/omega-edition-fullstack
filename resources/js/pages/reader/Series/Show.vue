<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import ReaderLayout from '@/layouts/ReaderLayout.vue';
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

interface Chapter {
    id: string;
    title: string;
    number: number;
    status: string;
    published_at?: string | null;
    preview_url?: string | null;
}

interface Series {
    id: string;
    title: string;
    slug: string;
    type: string;
    status: string;
    format: string;
    frequency: string;
    language: string;
    synopsis?: string | null;
    rating?: number | null;
    cover_url?: string | null;
    hero_url?: string | null;
    creator?: { id?: string; name?: string | null } | null;
    tags?: { name: string; slug: string }[];
    chapters?: Chapter[];
    chaptersPagination?: {
        current_page: number;
        last_page: number;
        links: { url: string | null; label: string; active: boolean }[];
    } | null;
    is_favorite?: boolean;
    likes_count?: number | null;
    dislikes_count?: number | null;
    last_read_chapter_id?: string | null;
    last_read_chapter_number?: number | null;
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

const startChapterId = computed(() => props.series.last_read_chapter_id ?? props.series.chapters?.[0]?.id ?? null);
const startChapterNumber = computed(() =>
    props.series.last_read_chapter_number ?? props.series.chapters?.[0]?.number ?? null,
);
</script>

<template>
    <ReaderLayout
        :title="series.title"
        description="Détails de la série"
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
                            <Badge variant="outline">{{ series.frequency }}</Badge>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2">
                        <Button class="w-full" :disabled="!startChapterId" as-child>
                            <Link :href="startChapterId ? `/reader/chapters/${startChapterId}` : '#'">
                                <span v-if="series.last_read_chapter_id">
                                    Reprendre au chapitre {{ startChapterNumber ?? '' }}
                                </span>
                                <span v-else>
                                    Commencer la lecture
                                </span>
                            </Link>
                        </Button>
                        <Button
                            variant="secondary"
                            class="w-full"
                            :class="series.is_favorite ? 'bg-emerald-500 text-white hover:bg-emerald-600' : ''"
                            as="button"
                            @click.prevent="$inertia.post(series.is_favorite ? `/reader/series/${series.id}/favorite?_method=delete` : `/reader/series/${series.id}/favorite`)"
                        >
                            {{ series.is_favorite ? 'Retirer des favoris' : 'Ajouter aux favoris' }}
                        </Button>
                        <div class="flex gap-2">
                            <Button
                                variant="outline"
                                class="flex-1"
                                @click.prevent="$inertia.post(`/reader/series/${series.id}/like`)"
                            >
                                👍 {{ series.likes_count ?? 0 }}
                            </Button>
                            <Button
                                variant="outline"
                                class="flex-1"
                                @click.prevent="$inertia.post(`/reader/series/${series.id}/dislike`)"
                            >
                                👎 {{ series.dislikes_count ?? 0 }}
                            </Button>
                        </div>
                    </div>
                </div>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>Synopsis</CardTitle>
                    <CardDescription>Présentation rapide de l'univers.</CardDescription>
                </CardHeader>
                <CardContent class="text-sm leading-relaxed text-muted-foreground">
                    {{ series.synopsis ?? 'Synopsis non fourni.' }}
                </CardContent>
            </Card>

            <Card>
                <CardHeader>
                    <CardTitle>Chapitres</CardTitle>
                    <CardDescription>Derniers chapitres publiés.</CardDescription>
                </CardHeader>
                <CardContent>
                    <div v-if="!series.chapters || series.chapters.length === 0" class="text-sm text-muted-foreground">
                        Aucun chapitre publié pour le moment.
                    </div>
                    <div v-else class="grid gap-3">
                        <div
                            v-for="chap in series.chapters"
                            :key="chap.id"
                            class="flex items-center justify-between rounded-md border p-3 text-sm"
                        >
                            <div class="flex items-center gap-3">
                                <div class="h-16 w-12 overflow-hidden rounded bg-muted">
                                    <img v-if="chap.preview_url" :src="chap.preview_url" :alt="chap.title" class="h-full w-full object-cover" />
                                </div>
                                <div>
                                    <div class="font-medium">Chapitre {{ chap.number }} · {{ chap.title }}</div>
                                    <div class="text-muted-foreground">
                                        {{ chap.published_at ? chap.published_at : 'Non publié' }}
                                    </div>
                                </div>
                            </div>
                            <Button variant="ghost" size="sm" as-child>
                                <Link :href="`/reader/chapters/${chap.id}`">Lire</Link>
                            </Button>
                        </div>
                    </div>
                    <div
                        v-if="series.chaptersPagination?.links?.length"
                        class="mt-4 flex flex-wrap items-center gap-2"
                    >
                        <Button
                            v-for="link in series.chaptersPagination.links"
                            :key="link.label"
                            :variant="link.active ? 'default' : 'ghost'"
                            :disabled="!link.url"
                            size="sm"
                            @click="link.url && $inertia.visit(link.url)"
                        >
                            <span>
                                {{
                                    link.label === 'pagination.previous'
                                        ? 'Précédent'
                                        : link.label === 'pagination.next'
                                          ? 'Suivant'
                                          : link.label
                                }}
                            </span>
                        </Button>
                    </div>
                </CardContent>
            </Card>

            <div class="flex flex-wrap gap-2">
                <Badge v-for="tag in series.tags ?? []" :key="tag.slug" variant="secondary">{{ tag.name }}</Badge>
            </div>
        </div>
    </ReaderLayout>
</template>
