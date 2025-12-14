<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import PublicLayout from '@/layouts/PublicLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { computed, reactive } from 'vue';

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
        meta?: { total?: number };
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
    if (status === 'completed') return 'Termine';
    return status;
};

const form = reactive({
    search: props.filters.search ?? '',
    creator: props.filters.creator ?? '',
    type: props.filters.type ?? '',
    status: props.filters.status ?? '',
    format: props.filters.format ?? '',
});

const totalResults = computed(() => props.series.meta?.total ?? props.series.data.length);
const activeFiltersCount = computed(
    () => [form.search, form.creator, form.type, form.status, form.format].filter(Boolean).length,
);

const hasResults = computed(() => (props.series.data?.length ?? 0) > 0);

const activeFilterLabels = computed(() => {
    const labels: string[] = [];
    if (form.search) labels.push(`Recherche : ${form.search}`);
    if (form.creator) labels.push(`Createur : ${form.creator}`);
    if (form.type) labels.push(`Type : ${form.type}`);
    if (form.status) labels.push(`Statut : ${statusLabel(form.status)}`);
    if (form.format) labels.push(`Format : ${form.format}`);
    return labels;
});

const applyFilters = () => {
    router.get(
        '/catalog',
        {
            search: form.search || null,
            creator: form.creator || null,
            type: form.type || null,
            status: form.status || null,
            format: form.format || null,
        },
        { preserveScroll: true, preserveState: true },
    );
};

const resetFilters = () => {
    form.search = '';
    form.creator = '';
    form.type = '';
    form.status = '';
    form.format = '';
    applyFilters();
};

const toggleFilter = (key: 'type' | 'status' | 'format', value: string) => {
    form[key] = form[key] === value ? '' : value;
};
</script>

<template>
    <PublicLayout
        title="Catalogue public"
        description="Parcourez les series et lisez gratuitement le premier chapitre."
    >
        <div class="space-y-8">
            <div class="relative overflow-hidden rounded-2xl border bg-gradient-to-r from-primary/12 via-background to-secondary/10 p-6 shadow-sm">
                <div class="pointer-events-none absolute inset-0 opacity-40 [background-image:radial-gradient(circle_at_20%_20%,rgba(99,102,241,0.18),transparent_35%),radial-gradient(circle_at_80%_0%,rgba(236,72,153,0.16),transparent_30%)]"></div>
                <div class="relative grid gap-6 lg:grid-cols-[1.2fr,1fr] lg:items-center">
                    <div class="space-y-3">
                        <p class="text-xs uppercase tracking-[0.18em] text-primary">Explorer</p>
                        <h2 class="text-2xl font-bold tracking-tight sm:text-3xl">Trouvez la prochaine serie a savourer</h2>
                        <p class="max-w-2xl text-sm text-muted-foreground">
                            Recherche fluide, filtres rapides et cartes harmonisees pour limiter les a-coups lors du chargement.
                        </p>
                        <div class="flex flex-wrap gap-2 text-xs text-muted-foreground">
                            <span class="rounded-full border border-border px-3 py-1">Resultats : {{ totalResults }}</span>
                            <span class="rounded-full border border-border px-3 py-1">
                                {{ activeFiltersCount }} filtre{{ activeFiltersCount > 1 ? 's' : '' }} actif{{ activeFiltersCount > 1 ? 's' : '' }}
                            </span>
                        </div>
                    </div>
                    <form class="rounded-xl border bg-background/80 p-4 shadow-sm backdrop-blur" @submit.prevent="applyFilters">
                        <div class="grid gap-3 sm:grid-cols-2">
                            <div class="space-y-1.5">
                                <Label for="search">Recherche</Label>
                                <Input
                                    id="search"
                                    v-model="form.search"
                                    type="text"
                                    placeholder="Titre de serie"
                                />
                            </div>
                            <div class="space-y-1.5">
                                <Label for="creator">Createur</Label>
                                <Input
                                    id="creator"
                                    v-model="form.creator"
                                    type="text"
                                    placeholder="Nom du createur"
                                />
                            </div>
                        </div>
                        <div class="mt-3 space-y-2">
                            <div class="flex items-center justify-between text-xs text-muted-foreground">
                                <span>Type</span>
                                <span class="rounded-full bg-muted px-2 py-1">Choix rapides</span>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <Button
                                    v-for="t in filterOptions.types"
                                    :key="t"
                                    size="sm"
                                    :variant="form.type === t ? 'default' : 'outline'"
                                    :class="form.type === t ? 'shadow-sm' : ''"
                                    type="button"
                                    @click="toggleFilter('type', t)"
                                >
                                    {{ t }}
                                </Button>
                            </div>
                        </div>
                        <div class="mt-3 grid gap-3 sm:grid-cols-2">
                            <div class="space-y-2">
                                <Label>Status</Label>
                                <div class="flex flex-wrap gap-2">
                                    <Button
                                        v-for="s in filterOptions.statuses"
                                        :key="s"
                                        size="sm"
                                        :variant="form.status === s ? 'default' : 'secondary'"
                                        :class="form.status === s ? 'shadow-sm' : ''"
                                        type="button"
                                        @click="toggleFilter('status', s)"
                                    >
                                        {{ statusLabel(s) }}
                                    </Button>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <Label>Format</Label>
                                <div class="flex flex-wrap gap-2">
                                    <Button
                                        v-for="f in filterOptions.formats"
                                        :key="f"
                                        size="sm"
                                        :variant="form.format === f ? 'default' : 'outline'"
                                        :class="form.format === f ? 'shadow-sm' : ''"
                                        type="button"
                                        @click="toggleFilter('format', f)"
                                    >
                                        {{ f }}
                                    </Button>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-end gap-2">
                            <Button variant="ghost" size="sm" type="button" @click="resetFilters">Reinitialiser</Button>
                            <Button size="sm" type="submit">Appliquer</Button>
                        </div>
                        <div v-if="activeFilterLabels.length" class="mt-4 space-y-2 text-xs">
                            <div class="flex items-center justify-between text-muted-foreground">
                                <span>Filtres actifs</span>
                                <span class="rounded-full bg-muted px-2 py-1">{{ activeFiltersCount }}</span>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <Badge v-for="label in activeFilterLabels" :key="label" variant="secondary" class="text-[11px]">
                                    {{ label }}
                                </Badge>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="space-y-5">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <h2 class="text-lg font-semibold">Selection du catalogue</h2>
                        <p class="text-sm text-muted-foreground">Cartes lisibles, transitions douces et visuels stabilises.</p>
                    </div>
                    <div class="hidden items-center gap-2 text-xs text-muted-foreground sm:flex">
                        <span class="rounded-full border border-border px-3 py-1">Resultats : {{ totalResults }}</span>
                        <span class="rounded-full border border-border px-3 py-1">
                            {{ activeFiltersCount }} filtre{{ activeFiltersCount > 1 ? 's' : '' }}
                        </span>
                    </div>
                </div>

                <div v-if="hasResults" class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                    <Card
                        v-for="item in series.data"
                        :key="item.id"
                        class="group overflow-hidden border-border/70 bg-card/90 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:shadow-[0_20px_60px_rgba(0,0,0,0.08)]"
                    >
                        <div class="relative aspect-[3/4] w-full overflow-hidden bg-muted">
                            <img
                                v-if="item.cover_url"
                                :src="item.cover_url"
                                :alt="item.title"
                                class="h-full w-full object-cover transition duration-300 group-hover:scale-[1.03]"
                            />
                            <div v-else class="flex h-full w-full items-center justify-center text-xs text-muted-foreground">
                                Visuel indisponible
                            </div>
                            <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/35 via-black/5 to-transparent"></div>
                            <Badge class="absolute left-3 top-3 bg-background/80 text-xs font-semibold shadow-sm" variant="secondary">
                                {{ statusLabel(item.status) }}
                            </Badge>
                            <div class="absolute bottom-3 left-3 flex flex-wrap gap-2 text-[11px] text-muted-foreground">
                                <Badge variant="outline">{{ item.type }}</Badge>
                                <Badge variant="outline">{{ item.format }}</Badge>
                            </div>
                        </div>
                        <CardContent class="space-y-3 p-4">
                            <div class="space-y-1">
                                <div class="text-base font-semibold leading-tight line-clamp-2">{{ item.title }}</div>
                                <div class="text-xs text-muted-foreground">
                                    {{ item.creator ?? 'Auteur inconnu' }} | Langue {{ item.language.toUpperCase() }}
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-1.5">
                                <Badge v-for="tag in item.tags ?? []" :key="tag.slug" variant="secondary" class="text-[11px]">
                                    {{ tag.name }}
                                </Badge>
                            </div>
                            <Button class="w-full justify-center" as-child>
                                <Link :href="`/catalog/${item.id}`">Voir la serie</Link>
                            </Button>
                        </CardContent>
                    </Card>
                </div>
                <Card v-else class="border-dashed bg-card/70">
                    <CardContent class="flex flex-col items-center gap-3 py-10 text-center">
                        <div class="rounded-full border border-border bg-muted px-3 py-1 text-xs uppercase tracking-[0.12em] text-muted-foreground">
                            Aucun resultat
                        </div>
                        <div class="text-lg font-semibold">Aucune serie ne correspond a ces filtres</div>
                        <p class="max-w-md text-sm text-muted-foreground">
                            Essayez d'elargir votre recherche ou reinitialisez les filtres pour afficher tout le catalogue.
                        </p>
                        <div class="flex flex-wrap justify-center gap-2">
                            <Button variant="outline" size="sm" type="button" @click="resetFilters">Reinitialiser les filtres</Button>
                            <Button size="sm" type="button" @click="applyFilters">Relancer la recherche</Button>
                        </div>
                    </CardContent>
                </Card>

                <div class="flex flex-wrap items-center gap-2">
                    <Button
                        v-for="link in series.links"
                        :key="link.label"
                        :variant="link.active ? 'default' : 'outline'"
                        :disabled="!link.url"
                        size="sm"
                        class="rounded-full"
                        @click="link.url && $inertia.visit(link.url)"
                    >
                        <span>
                            {{
                                link.label === 'pagination.previous'
                                    ? 'Precedent'
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
