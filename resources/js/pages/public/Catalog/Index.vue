<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import PublicLayout from '@/layouts/PublicLayout.vue';
import { Link } from '@inertiajs/vue3';

interface SeriesItem {
    id: string;
    title: string;
    type: string;
    status: string;
    format: string;
    language: string;
    cover_url?: string | null;
    creator?: string | null;
    tags?: { name: string; slug: string }[];
}

const props = defineProps<{
    series: {
        data: SeriesItem[];
        links: { url: string | null; label: string; active: boolean }[];
    };
    filters: Record<string, any>;
    filterOptions: {
        types: string[];
        statuses: string[];
        formats: string[];
        tags: { name: string; slug: string }[];
    };
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
        title="Catalogue public"
        description="Parcourez les séries et lisez gratuitement le premier chapitre."
    >
        <div class="grid gap-6 lg:grid-cols-[320px,1fr]">
            <Card>
                <CardHeader>
                    <CardTitle>Filtres</CardTitle>
                    <CardDescription>Ajustez votre recherche.</CardDescription>
                </CardHeader>
                <CardContent class="space-y-3 text-sm text-muted-foreground">
                    <div>Recherche en cours… (entrée dans l’URL).</div>
                    <div>Types : {{ filterOptions.types.join(', ') }}</div>
                    <div>Formats : {{ filterOptions.formats.join(', ') }}</div>
                    <div>Statuts : {{ filterOptions.statuses.join(', ') }}</div>
                </CardContent>
            </Card>

            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold">Séries</h2>
                        <p class="text-sm text-muted-foreground">Cliquez pour voir le chapitre gratuit.</p>
                    </div>
                    <Button as-child>
                        <Link href="/register/reader">Créer un compte lecteur</Link>
                    </Button>
                </div>
                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                    <Card v-for="item in series.data" :key="item.id" class="overflow-hidden">
                        <div class="h-44 w-full bg-muted">
                            <img v-if="item.cover_url" :src="item.cover_url" :alt="item.title" class="h-full w-full object-cover" />
                        </div>
                        <CardContent class="space-y-2 p-4">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <div class="text-base font-semibold leading-tight">{{ item.title }}</div>
                                    <div class="text-xs text-muted-foreground">
                                        {{ item.creator ?? 'Auteur inconnu' }}
                                    </div>
                                </div>
                                <Badge variant="secondary">{{ statusLabel(item.status) }}</Badge>
                            </div>
                            <div class="flex flex-wrap gap-2 text-xs text-muted-foreground">
                                <Badge variant="outline">{{ item.type }}</Badge>
                                <Badge variant="outline">{{ item.format }}</Badge>
                                <span>Langue {{ item.language.toUpperCase() }}</span>
                            </div>
                            <div class="flex flex-wrap gap-1">
                                <Badge v-for="tag in item.tags ?? []" :key="tag.slug" variant="secondary" class="text-[11px]">
                                    {{ tag.name }}
                                </Badge>
                            </div>
                            <Button class="w-full" as-child>
                                <Link :href="`/catalog/${item.id}`">Voir la série</Link>
                            </Button>
                        </CardContent>
                    </Card>
                </div>
                <div class="flex flex-wrap gap-2">
                    <Button
                        v-for="link in series.links"
                        :key="link.label"
                        :variant="link.active ? 'default' : 'outline'"
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
            </div>
        </div>
    </PublicLayout>
</template>
