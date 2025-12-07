<script setup lang="ts">
import CreatorLayout from '@/layouts/CreatorLayout.vue';
import { Link } from '@inertiajs/vue3';
import { ArrowRight, LayoutGrid, List, Plus, Search } from 'lucide-vue-next';
import { computed, onMounted, ref, watch } from 'vue';

type ViewMode = 'grid' | 'list';

type Series = {
    id: string;
    title: string;
    type: 'manga' | 'webtoon';
    status: 'ongoing' | 'hiatus' | 'completed';
    chapters: number;
    nextRelease?: string;
    updatedAt: string;
    views: string;
    cover?: string;
    tags: string[];
};

const viewMode = ref<ViewMode>('grid');
const query = ref('');
const statusFilter = ref<'all' | Series['status']>('all');
const loading = ref(true);
const currentPage = ref(1);
const perPage = ref(6);

const series = ref<Series[]>([
    {
        id: 'omega-rebirth',
        title: 'Omega Rebirth',
        type: 'manga',
        status: 'ongoing',
        chapters: 42,
        nextRelease: '15 déc',
        updatedAt: '05 déc 2025',
        views: '1.2M',
        cover: 'https://images.unsplash.com/photo-1504274066651-8d31a536b11a?auto=format&fit=crop&w=600&q=60',
        tags: ['Action', 'Dark fantasy'],
    },
    {
        id: 'lumiere-noire',
        title: 'Lumière Noire',
        type: 'webtoon',
        status: 'ongoing',
        chapters: 28,
        nextRelease: '18 déc',
        updatedAt: '03 déc 2025',
        views: '840K',
        cover: 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=600&q=60',
        tags: ['Mystère', 'Thriller'],
    },
    {
        id: 'nagara',
        title: 'Nagara',
        type: 'manga',
        status: 'hiatus',
        chapters: 19,
        updatedAt: '12 nov 2025',
        views: '510K',
        tags: ['Aventure', 'Spiritualité'],
    },
    {
        id: 'khumbu-chronicles',
        title: 'Khumbu Chronicles',
        type: 'webtoon',
        status: 'completed',
        chapters: 62,
        updatedAt: '28 oct 2025',
        views: '1.8M',
        cover: 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=600&q=60',
        tags: ['Aventure', 'Survie'],
    },
    {
        id: 'voix-du-nil',
        title: 'Voix du Nil',
        type: 'manga',
        status: 'ongoing',
        chapters: 12,
        nextRelease: '22 déc',
        updatedAt: '02 déc 2025',
        views: '210K',
        cover: 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=600&q=60',
        tags: ['Historique', 'Drame'],
    },
    {
        id: 'astra-guardians',
        title: 'Astra Guardians',
        type: 'webtoon',
        status: 'ongoing',
        chapters: 34,
        updatedAt: '01 déc 2025',
        views: '970K',
        cover: 'https://images.unsplash.com/photo-1462331940025-496dfbfc7564?auto=format&fit=crop&w=600&q=60',
        tags: ['Sci-fi', 'Equipe'],
    },
]);

const filteredSeries = computed(() => {
    const q = query.value.toLowerCase().trim();
    return series.value.filter((s) => {
        const matchesQuery =
            !q ||
            s.title.toLowerCase().includes(q) ||
            s.tags.some((tag) => tag.toLowerCase().includes(q));
        const matchesStatus = statusFilter.value === 'all' || s.status === statusFilter.value;
        return matchesQuery && matchesStatus;
    });
});

const totalPages = computed(() =>
    Math.max(1, Math.ceil(filteredSeries.value.length / perPage.value)),
);

const paginatedSeries = computed(() => {
    const start = (currentPage.value - 1) * perPage.value;
    return filteredSeries.value.slice(start, start + perPage.value);
});

watch([filteredSeries, statusFilter, query], () => {
    currentPage.value = 1;
});

onMounted(() => {
    setTimeout(() => {
        loading.value = false;
    }, 500);
});

const statusClass = (status: Series['status']) => {
    switch (status) {
        case 'ongoing':
            return 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-200';
        case 'hiatus':
            return 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-200';
        case 'completed':
            return 'bg-sky-100 text-sky-800 dark:bg-sky-900/40 dark:text-sky-200';
    }
};

const typeClass = (type: Series['type']) =>
    type === 'manga'
        ? 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-200'
        : 'bg-purple-100 text-purple-800 dark:bg-purple-900/40 dark:text-purple-200';

const coverStyle = (cover?: string) =>
    cover
        ? { backgroundImage: `url(${cover})` }
        : { backgroundImage: 'linear-gradient(135deg, #1f2937 0%, #0ea5e9 100%)' };
</script>

<template>
    <CreatorLayout
        title="Séries"
        description="Vue rapide des séries publiées ou en cours avec navigation directe."
    >
        <div class="space-y-4">
            <div class="flex flex-col gap-3 rounded-lg border bg-card p-4 shadow-sm md:flex-row md:items-center md:justify-between">
                <div class="flex flex-1 items-center gap-2">
                    <div class="relative w-full md:w-80">
                        <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground">
                            <Search class="h-4 w-4" />
                        </span>
                        <input
                            v-model="query"
                            type="search"
                            placeholder="Rechercher une série..."
                            class="w-full rounded-md border bg-background py-2 pl-9 pr-3 text-sm outline-none ring-2 ring-transparent transition focus:border-primary focus:ring-primary/20"
                        />
                    </div>
                    <select
                        v-model="statusFilter"
                        class="hidden rounded-md border bg-background px-3 py-2 text-sm text-foreground shadow-sm transition focus:border-primary focus:outline-none focus:ring focus:ring-primary/20 md:inline-block"
                    >
                        <option value="all">Tous les statuts</option>
                        <option value="ongoing">En cours</option>
                        <option value="hiatus">Pause</option>
                        <option value="completed">Terminé</option>
                    </select>
                    <div class="hidden items-center gap-2 md:flex">
                        <button
                            type="button"
                            class="inline-flex items-center gap-2 rounded-md border px-3 py-2 text-sm transition hover:border-primary"
                            :class="viewMode === 'grid' ? 'bg-primary text-primary-foreground' : 'bg-muted/40'"
                            @click="viewMode = 'grid'"
                        >
                            <LayoutGrid class="h-4 w-4" />
                            Grille
                        </button>
                        <button
                            type="button"
                            class="inline-flex items-center gap-2 rounded-md border px-3 py-2 text-sm transition hover:border-primary"
                            :class="viewMode === 'list' ? 'bg-primary text-primary-foreground' : 'bg-muted/40'"
                            @click="viewMode = 'list'"
                        >
                            <List class="h-4 w-4" />
                            Liste
                        </button>
                    </div>
                </div>
                <Link
                    href="/creator/series/create"
                    class="inline-flex items-center justify-center gap-2 rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground shadow hover:opacity-90"
                >
                    <Plus class="h-4 w-4" />
                    Créer une série
                </Link>
            </div>

            <div v-if="loading" class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                <div
                    v-for="n in perPage"
                    :key="n"
                    class="rounded-xl border bg-card/70 p-4 shadow-sm"
                >
                    <div class="mb-4 h-36 w-full animate-pulse rounded-lg bg-muted" />
                    <div class="space-y-2">
                        <div class="h-4 w-2/3 animate-pulse rounded bg-muted" />
                        <div class="h-3 w-1/2 animate-pulse rounded bg-muted" />
                        <div class="flex gap-2">
                            <div class="h-6 w-16 animate-pulse rounded-full bg-muted" />
                            <div class="h-6 w-16 animate-pulse rounded-full bg-muted" />
                        </div>
                        <div class="h-3 w-1/3 animate-pulse rounded bg-muted" />
                    </div>
                </div>
            </div>

            <template v-else>
                <div v-if="viewMode === 'grid'" class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                    <div
                        v-for="serie in paginatedSeries"
                        :key="serie.id"
                        class="group relative overflow-hidden rounded-xl border bg-card/80 shadow-sm transition hover:-translate-y-0.5 hover:border-primary/60 hover:shadow-md"
                    >
                        <div
                            class="h-40 w-full bg-cover bg-center transition group-hover:scale-[1.01]"
                            :style="coverStyle(serie.cover)"
                        ></div>
                        <div class="space-y-3 p-4">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <h3 class="text-lg font-semibold leading-tight">{{ serie.title }}</h3>
                                    <p class="text-xs text-muted-foreground">
                                        {{ serie.chapters }} chapitres · {{ serie.views }} vues
                                    </p>
                                </div>
                                <Link
                                    class="inline-flex items-center gap-1 text-xs font-semibold text-primary hover:underline"
                                    :href="`/creator/series/${serie.id}`"
                                >
                                    Ouvrir
                                    <ArrowRight class="h-3.5 w-3.5" />
                                </Link>
                            </div>
                            <div class="flex flex-wrap items-center gap-2 text-xs">
                                <span class="rounded-full px-2 py-1" :class="statusClass(serie.status)">
                                    {{
                                        serie.status === 'ongoing'
                                            ? 'En cours'
                                            : serie.status === 'hiatus'
                                              ? 'Pause'
                                              : 'Terminé'
                                    }}
                                </span>
                                <span class="rounded-full px-2 py-1" :class="typeClass(serie.type)">
                                    {{ serie.type === 'manga' ? 'Manga' : 'Webtoon' }}
                                </span>
                                <span
                                    v-if="serie.nextRelease"
                                    class="rounded-full bg-muted px-2 py-1 text-muted-foreground"
                                >
                                    Prochaine sortie {{ serie.nextRelease }}
                                </span>
                            </div>
                            <div class="flex flex-wrap gap-1 text-xs text-muted-foreground">
                                <span
                                    v-for="tag in serie.tags"
                                    :key="tag"
                                    class="rounded-full bg-muted px-2 py-1"
                                >
                                    {{ tag }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between text-xs text-muted-foreground">
                                <span>Mis à jour {{ serie.updatedAt }}</span>
                                <Link
                                    class="inline-flex items-center gap-1 text-primary hover:underline"
                                    :href="`/creator/series/${serie.id}/edit`"
                                >
                                    Éditer
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    v-else
                    class="overflow-hidden rounded-xl border bg-card/80 shadow-sm"
                >
                    <div class="hidden grid-cols-[auto_1fr_auto_auto] items-center gap-4 border-b px-4 py-3 text-xs font-semibold text-muted-foreground md:grid">
                        <span>Couverture</span>
                        <span>Série</span>
                        <span>Chapitres</span>
                        <span>Statut</span>
                    </div>
                    <div
                        v-for="serie in paginatedSeries"
                        :key="serie.id"
                        class="grid items-center gap-3 border-b px-4 py-3 last:border-b-0 md:grid-cols-[auto_1fr_auto_auto]"
                    >
                        <div
                            class="h-16 w-12 overflow-hidden rounded-md bg-cover bg-center"
                            :style="coverStyle(serie.cover)"
                        ></div>
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <Link
                                    class="text-sm font-semibold hover:text-primary"
                                    :href="`/creator/series/${serie.id}`"
                                >
                                    {{ serie.title }}
                                </Link>
                                <span class="rounded-full px-2 py-1 text-[11px]" :class="typeClass(serie.type)">
                                    {{ serie.type === 'manga' ? 'Manga' : 'Webtoon' }}
                                </span>
                            </div>
                            <p class="text-xs text-muted-foreground">
                                {{ serie.tags.join(' • ') }}
                            </p>
                            <p class="text-[11px] text-muted-foreground">
                                Mis à jour {{ serie.updatedAt }}
                                <span v-if="serie.nextRelease"> · Prochaine sortie {{ serie.nextRelease }}</span>
                            </p>
                        </div>
                        <div class="text-sm font-medium md:text-center">
                            {{ serie.chapters }} chapitres
                        </div>
                        <div class="flex items-center gap-2 md:justify-end">
                            <span class="rounded-full px-2 py-1 text-[11px]" :class="statusClass(serie.status)">
                                {{
                                    serie.status === 'ongoing'
                                        ? 'En cours'
                                        : serie.status === 'hiatus'
                                          ? 'Pause'
                                          : 'Terminé'
                                }}
                            </span>
                            <Link
                                class="text-xs font-semibold text-primary hover:underline"
                                :href="`/creator/series/${serie.id}/edit`"
                            >
                                Éditer
                            </Link>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between gap-3 rounded-lg border bg-card/70 px-4 py-3 text-sm text-muted-foreground">
                    <div>
                        Page {{ currentPage }} / {{ totalPages }}
                        · {{ filteredSeries.length }} série(s)
                    </div>
                    <div class="flex items-center gap-2">
                        <button
                            type="button"
                            class="rounded-md border px-3 py-1 transition hover:border-primary disabled:cursor-not-allowed disabled:opacity-50"
                            :disabled="currentPage === 1"
                            @click="currentPage--"
                        >
                            Précédent
                        </button>
                        <button
                            type="button"
                            class="rounded-md border px-3 py-1 transition hover:border-primary disabled:cursor-not-allowed disabled:opacity-50"
                            :disabled="currentPage === totalPages"
                            @click="currentPage++"
                        >
                            Suivant
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </CreatorLayout>
</template>
