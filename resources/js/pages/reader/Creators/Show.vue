<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import ReaderLayout from '@/layouts/ReaderLayout.vue';
import { Link } from '@inertiajs/vue3';

interface SeriesItem {
    id: string;
    title: string;
    cover_url?: string | null;
    type: string;
    status: string;
    format: string;
    language: string;
    likes_count?: number | null;
    tags?: { name: string; slug: string }[];
}

interface Creator {
    id: string;
    name: string;
    display_name?: string | null;
    headline?: string | null;
    bio?: string | null;
    languages?: string | null;
    nationality?: string | null;
    location?: string | null;
    website?: string | null;
    avatar_url?: string | null;
    cover_url?: string | null;
    stats?: {
        series_count?: number;
        likes_total?: number;
    };
}

const props = defineProps<{
    creator: Creator;
    series: SeriesItem[];
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
        :title="creator.display_name || creator.name"
        description="Profil du créateur et œuvres publiées."
    >
        <div class="space-y-6">
            <div class="overflow-hidden rounded-xl border bg-card">
                <div
                    class="h-48 w-full bg-gradient-to-r from-slate-900/90 to-slate-700/60"
                    :style="creator.cover_url ? `background-image: linear-gradient(90deg, rgba(15,23,42,0.85), rgba(15,23,42,0.45)), url(${creator.cover_url}); background-size: cover; background-position: center;` : ''"
                ></div>
                <div class="-mt-12 flex flex-col gap-4 px-6 pb-6 md:flex-row md:items-end md:gap-6">
                    <div class="h-24 w-24 overflow-hidden rounded-full border-4 border-background bg-muted shadow-lg">
                        <img v-if="creator.avatar_url" :src="creator.avatar_url" :alt="creator.display_name || creator.name" class="h-full w-full object-cover" />
                        <div v-else class="flex h-full items-center justify-center text-sm text-muted-foreground">Avatar</div>
                    </div>
                    <div class="flex-1 space-y-2">
                        <div class="text-sm text-muted-foreground">Créateur</div>
                        <h1 class="text-2xl font-semibold leading-tight">{{ creator.display_name || creator.name }}</h1>
                        <p class="text-sm text-muted-foreground">
                            {{ creator.headline || 'Auteur inspiré' }}
                        </p>
                        <div class="flex flex-wrap gap-2 text-xs text-muted-foreground">
                            <span v-if="creator.location">📍 {{ creator.location }}</span>
                            <span v-if="creator.languages">· Langues : {{ creator.languages }}</span>
                            <span v-if="creator.nationality">· Nationalité : {{ creator.nationality }}</span>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <Button v-if="creator.website" variant="outline" as-child>
                            <a :href="creator.website" target="_blank" rel="noreferrer">Portfolio</a>
                        </Button>
                        <Button variant="secondary" as-child>
                            <Link href="/reader/series">Découvrir plus</Link>
                        </Button>
                    </div>
                </div>
            </div>

            <Card v-if="creator.bio || creator.stats">
                <CardHeader class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                    <div>
                        <CardTitle>À propos</CardTitle>
                        <CardDescription>Bio et chiffres clés.</CardDescription>
                    </div>
                    <div class="flex flex-wrap gap-3 text-sm">
                        <div class="rounded-lg border bg-muted px-3 py-2">
                            <div class="text-xs text-muted-foreground">Œuvres</div>
                            <div class="text-base font-semibold">{{ creator.stats?.series_count ?? 0 }}</div>
                        </div>
                        <div class="rounded-lg border bg-muted px-3 py-2">
                            <div class="text-xs text-muted-foreground">Likes cumulés</div>
                            <div class="text-base font-semibold">{{ creator.stats?.likes_total ?? 0 }}</div>
                        </div>
                    </div>
                </CardHeader>
                <CardContent class="text-sm leading-relaxed text-muted-foreground whitespace-pre-line">
                    {{ creator.bio || 'Aucune bio fournie.' }}
                </CardContent>
            </Card>

            <Card>
                <CardHeader>
                    <CardTitle>Œuvres</CardTitle>
                    <CardDescription>Les séries créées par cet auteur.</CardDescription>
                </CardHeader>
                <CardContent>
                    <div v-if="!series.length" class="text-sm text-muted-foreground">
                        Aucune œuvre publiée pour le moment.
                    </div>
                    <div v-else class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                        <div
                            v-for="item in series"
                            :key="item.id"
                            class="overflow-hidden rounded-lg border bg-card"
                        >
                            <div class="h-48 w-full bg-muted">
                                <img v-if="item.cover_url" :src="item.cover_url" :alt="item.title" class="h-full w-full object-cover" />
                            </div>
                            <div class="space-y-2 p-3">
                                <div class="flex items-start justify-between gap-2">
                                    <div>
                                        <div class="text-base font-semibold leading-tight">{{ item.title }}</div>
                                        <div class="text-xs text-muted-foreground">
                                            {{ item.type }} · {{ item.language.toUpperCase() }}
                                        </div>
                                    </div>
                                    <Badge variant="secondary">{{ statusLabel(item.status) }}</Badge>
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    <Badge variant="outline">{{ item.format }}</Badge>
                                    <Badge variant="outline">👍 {{ item.likes_count ?? 0 }}</Badge>
                                </div>
                                <div class="flex flex-wrap gap-1">
                                    <Badge
                                        v-for="tag in item.tags ?? []"
                                        :key="tag.slug"
                                        variant="secondary"
                                        class="text-[11px]"
                                    >
                                        {{ tag.name }}
                                    </Badge>
                                </div>
                                <div class="flex gap-2 pt-1">
                                    <Button class="flex-1" as-child>
                                        <Link :href="`/reader/series/${item.id}`">Voir la série</Link>
                                    </Button>
                                </div>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </ReaderLayout>
</template>
