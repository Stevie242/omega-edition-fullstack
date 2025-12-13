<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import ReaderLayout from '@/layouts/ReaderLayout.vue';
import { Link } from '@inertiajs/vue3';

interface ContinueItem {
    id: string;
    title: string;
    cover_url?: string | null;
    type: string;
    status: string;
    format: string;
    language: string;
    last_chapter_id?: string | null;
    last_chapter_number?: number | null;
    start_chapter_id?: string | null;
    start_chapter_number?: number | null;
}

interface FavoriteItem {
    id: string;
    title: string;
    cover_url?: string | null;
    type: string;
    status: string;
    format: string;
    language: string;
}

interface LatestChapterItem {
    id: string;
    title: string;
    number: number;
    published_at?: string | null;
    series?: { id?: string; title?: string | null; cover_url?: string | null } | null;
}

interface TrendingItem {
    id: string;
    title: string;
    cover_url?: string | null;
    type: string;
    status: string;
    format: string;
    language: string;
    likes_count?: number | null;
}

const props = defineProps<{
    continue: ContinueItem[];
    favorites: FavoriteItem[];
    latestChapters: LatestChapterItem[];
    trending: TrendingItem[];
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
        title="Accueil lecteur"
        description="Reprenez vos lectures, découvrez les nouveautés et les tendances."
    >
        <div class="grid gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2 space-y-6">
                <Card>
                    <CardHeader>
                        <CardTitle>Reprendre la lecture</CardTitle>
                        <CardDescription>Vos séries récemment consultées.</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div v-if="!props.continue.length" class="text-sm text-muted-foreground">
                            Rien à reprendre pour l'instant. Lancez une lecture !
                        </div>
                        <div v-else class="grid gap-4 md:grid-cols-2">
                            <div
                                v-for="item in props.continue"
                                :key="item.id"
                                class="flex gap-3 rounded-lg border p-3"
                            >
                                <div class="h-24 w-16 overflow-hidden rounded bg-muted">
                                    <img v-if="item.cover_url" :src="item.cover_url" :alt="item.title" class="h-full w-full object-cover" />
                                </div>
                                <div class="flex flex-col gap-1">
                                    <div class="font-semibold leading-tight">{{ item.title }}</div>
                                    <div class="text-xs text-muted-foreground">
                                        {{ item.type }} · {{ item.language.toUpperCase() }}
                                    </div>
                                    <div class="flex flex-wrap gap-1 text-xs text-muted-foreground">
                                        <Badge variant="secondary">{{ statusLabel(item.status) }}</Badge>
                                        <Badge variant="outline">{{ item.format }}</Badge>
                                    </div>
                                    <div class="flex gap-2 pt-1">
                                        <Button size="sm" :disabled="!item.start_chapter_id" as-child>
                                            <Link :href="item.start_chapter_id ? `/reader/chapters/${item.start_chapter_id}` : '#'">
                                                <span v-if="item.last_chapter_id">Reprendre (ch. {{ item.start_chapter_number }})</span>
                                                <span v-else>Commencer</span>
                                            </Link>
                                        </Button>
                                        <Button size="sm" variant="ghost" as-child>
                                            <Link :href="`/reader/series/${item.id}`">Voir</Link>
                                        </Button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Nouveaux chapitres</CardTitle>
                        <CardDescription>Ce qui vient de sortir.</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div v-if="!latestChapters.length" class="text-sm text-muted-foreground">
                            Aucun chapitre récent.
                        </div>
                        <div v-else class="grid gap-3">
                            <div
                                v-for="chap in latestChapters"
                                :key="chap.id"
                                class="flex items-center justify-between rounded-md border p-3 text-sm"
                            >
                                <div class="flex items-center gap-3">
                                    <div class="h-14 w-14 overflow-hidden rounded bg-muted">
                                        <img v-if="chap.series?.cover_url" :src="chap.series.cover_url" :alt="chap.series?.title ?? ''" class="h-full w-full object-cover" />
                                    </div>
                                    <div>
                                        <div class="font-medium">
                                            {{ chap.series?.title ?? 'Série' }} · Chapitre {{ chap.number }}
                                        </div>
                                        <div class="text-muted-foreground">
                                            {{ chap.title }} · {{ chap.published_at ?? 'Non publié' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <Button variant="ghost" size="sm" as-child>
                                        <Link :href="`/reader/series/${chap.series?.id}`">Fiche</Link>
                                    </Button>
                                    <Button size="sm" as-child>
                                        <Link :href="`/reader/chapters/${chap.id}`">Lire</Link>
                                    </Button>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <div class="space-y-6">
                <Card>
                    <CardHeader>
                        <CardTitle>Vos favoris</CardTitle>
                        <CardDescription>Accès rapide aux séries suivies.</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div v-if="!favorites.length" class="text-sm text-muted-foreground">
                            Aucun favori. Ajoutez-en depuis les fiches séries.
                        </div>
                        <div v-else class="grid gap-3">
                            <div
                                v-for="fav in favorites"
                                :key="fav.id"
                                class="flex items-center gap-3 rounded-md border p-2"
                            >
                                <div class="h-14 w-12 overflow-hidden rounded bg-muted">
                                    <img v-if="fav.cover_url" :src="fav.cover_url" :alt="fav.title" class="h-full w-full object-cover" />
                                </div>
                                <div class="flex-1">
                                    <div class="text-sm font-semibold leading-tight">{{ fav.title }}</div>
                                    <div class="text-xs text-muted-foreground">
                                        {{ fav.type }} · {{ fav.language.toUpperCase() }}
                                    </div>
                                </div>
                                <Button size="sm" variant="ghost" as-child>
                                    <Link :href="`/reader/series/${fav.id}`">Ouvrir</Link>
                                </Button>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Tendances</CardTitle>
                        <CardDescription>Plus likées en ce moment.</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div v-if="!trending.length" class="text-sm text-muted-foreground">
                            Pas encore de tendances.
                        </div>
                        <div v-else class="space-y-3">
                            <div
                                v-for="serie in trending"
                                :key="serie.id"
                                class="flex items-center gap-3 rounded-md border p-2"
                            >
                                <div class="h-14 w-12 overflow-hidden rounded bg-muted">
                                    <img v-if="serie.cover_url" :src="serie.cover_url" :alt="serie.title" class="h-full w-full object-cover" />
                                </div>
                                <div class="flex-1">
                                    <div class="text-sm font-semibold leading-tight">{{ serie.title }}</div>
                                    <div class="text-xs text-muted-foreground">
                                        {{ serie.type }} · {{ statusLabel(serie.status) }}
                                    </div>
                                </div>
                                <div class="text-xs text-muted-foreground">
                                    👍 {{ serie.likes_count ?? 0 }}
                                </div>
                                <Button size="sm" variant="ghost" as-child>
                                    <Link :href="`/reader/series/${serie.id}`">Voir</Link>
                                </Button>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </ReaderLayout>
</template>
