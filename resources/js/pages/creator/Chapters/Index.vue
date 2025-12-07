<script setup lang="ts">
import CreatorLayout from '@/layouts/CreatorLayout.vue';
import { Link } from '@inertiajs/vue3';
import { onMounted, ref, computed } from 'vue';
import { ArrowLeft, CalendarClock, LayoutGrid, List, Plus, Search } from 'lucide-vue-next';

const props = defineProps<{
    seriesId: string;
}>();

type Chapter = {
    id: number;
    title: string;
    number: number;
    status: 'draft' | 'scheduled' | 'published';
    scheduledFor?: string;
    publishedAt?: string;
    pages: number;
    views: string;
    preview?: string;
    likes?: number;
    dislikes?: number;
    rating?: number;
};

const loading = ref(true);
const viewMode = ref<'grid' | 'list'>('grid');
const query = ref('');
const statusFilter = ref<'all' | Chapter['status']>('all');
const currentPage = ref(1);
const perPage = ref(8);

const chapters = ref<Chapter[]>([
    { id: 1, title: 'Chapitre 42', number: 42, status: 'scheduled', scheduledFor: '2025-12-15 10:00', pages: 28, views: '12.4K', preview: 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=600&q=60', likes: 820, dislikes: 42, rating: 4.6 },
    { id: 2, title: 'Chapitre 41', number: 41, status: 'published', publishedAt: '2025-12-02', pages: 30, views: '18.1K', preview: 'https://images.unsplash.com/photo-1504274066651-8d31a536b11a?auto=format&fit=crop&w=600&q=60', likes: 1290, dislikes: 64, rating: 4.7 },
    { id: 3, title: 'Chapitre 40', number: 40, status: 'published', publishedAt: '2025-11-25', pages: 26, views: '17.8K', preview: 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=600&q=60', likes: 1175, dislikes: 71, rating: 4.6 },
    { id: 4, title: 'Chapitre 39', number: 39, status: 'draft', pages: 24, views: '—', preview: 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=600&q=60', likes: 0, dislikes: 0, rating: 0 },
    { id: 5, title: 'Chapitre 38', number: 38, status: 'published', publishedAt: '2025-11-11', pages: 25, views: '16.2K', preview: 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=600&q=60', likes: 980, dislikes: 58, rating: 4.5 },
    { id: 6, title: 'Chapitre 37', number: 37, status: 'published', publishedAt: '2025-11-04', pages: 23, views: '15.9K', preview: 'https://images.unsplash.com/photo-1462331940025-496dfbfc7564?auto=format&fit=crop&w=600&q=60', likes: 910, dislikes: 55, rating: 4.5 },
    { id: 7, title: 'Chapitre 36', number: 36, status: 'draft', pages: 21, views: '—', preview: 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=600&q=60', likes: 0, dislikes: 0, rating: 0 },
    { id: 8, title: 'Chapitre 35', number: 35, status: 'published', publishedAt: '2025-10-28', pages: 22, views: '14.3K', preview: 'https://images.unsplash.com/photo-1462331940025-496dfbfc7564?auto=format&fit=crop&w=600&q=60', likes: 840, dislikes: 60, rating: 4.4 },
]);

const filteredChapters = computed(() => {
    const q = query.value.trim().toLowerCase();
    return chapters.value.filter((c) => {
        const matchQuery =
            !q ||
            c.title.toLowerCase().includes(q) ||
            String(c.number).includes(q);
        const matchStatus = statusFilter.value === 'all' || c.status === statusFilter.value;
        return matchQuery && matchStatus;
    });
});

const totalPages = computed(() =>
    Math.max(1, Math.ceil(filteredChapters.value.length / perPage.value)),
);

const paginatedChapters = computed(() => {
    const start = (currentPage.value - 1) * perPage.value;
    return filteredChapters.value.slice(start, start + perPage.value);
});

onMounted(() => {
    setTimeout(() => (loading.value = false), 500);
});

const previewStyle = (src?: string) =>
    src
        ? { backgroundImage: `url(${src})` }
        : { backgroundImage: 'linear-gradient(135deg, #1f2937 0%, #0ea5e9 100%)' };
</script>

<template>
    <CreatorLayout
        title="Chapitres"
        :description="`Liste des chapitres pour la série #${props.seriesId}`"
        :breadcrumbs="[
            { title: 'Séries', href: '/creator/series' },
            { title: props.seriesId, href: `/creator/series/${props.seriesId}` },
            { title: 'Chapitres' },
        ]"
    >
        <div class="space-y-4">
            <div class="flex flex-wrap items-center gap-3 text-sm text-muted-foreground">
                <Link
                    :href="`/creator/series/${props.seriesId}`"
                    class="inline-flex items-center gap-2 rounded-md border px-3 py-2 hover:border-primary hover:text-primary"
                >
                    <ArrowLeft class="h-4 w-4" />
                    Retour à la série
                </Link>
                <Link
                    :href="`/creator/series/${props.seriesId}/chapters/create`"
                    class="inline-flex items-center gap-2 rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground shadow hover:opacity-90"
                >
                    <Plus class="h-4 w-4" />
                    Nouveau chapitre
                </Link>
                <button
                    type="button"
                    class="inline-flex items-center gap-2 rounded-md border px-3 py-2 text-sm transition hover:border-primary"
                >
                    <CalendarClock class="h-4 w-4" />
                    Programmer un chapitre
                </button>
            </div>

            <div class="rounded-xl border bg-card p-4 shadow-sm space-y-3">
                <div class="flex flex-wrap items-center gap-3">
                    <div class="relative w-full md:w-72">
                        <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground">
                            <Search class="h-4 w-4" />
                        </span>
                        <input
                            v-model="query"
                            type="search"
                            placeholder="Rechercher un chapitre..."
                            class="w-full rounded-md border bg-background py-2 pl-9 pr-3 text-sm outline-none ring-2 ring-transparent transition focus:border-primary focus:ring-primary/20"
                        />
                    </div>
                    <select
                        v-model="statusFilter"
                        class="rounded-md border bg-background px-3 py-2 text-sm text-foreground shadow-sm transition focus:border-primary focus:outline-none focus:ring focus:ring-primary/20"
                    >
                        <option value="all">Tous</option>
                        <option value="draft">Brouillon</option>
                        <option value="scheduled">Programmé</option>
                        <option value="published">Publié</option>
                    </select>
                    <div class="ml-auto hidden items-center gap-2 md:flex">
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
            </div>

            <div v-if="loading" class="grid gap-4 sm:grid-cols-2">
                <div v-for="n in perPage" :key="n" class="rounded-xl border bg-card/80 p-4 shadow-sm">
                    <div class="h-16 w-full animate-pulse rounded bg-muted" />
                    <div class="mt-3 space-y-2">
                        <div class="h-4 w-2/3 animate-pulse rounded bg-muted" />
                        <div class="h-3 w-1/2 animate-pulse rounded bg-muted" />
                        <div class="flex gap-2">
                            <div class="h-6 w-16 animate-pulse rounded-full bg-muted" />
                            <div class="h-6 w-16 animate-pulse rounded-full bg-muted" />
                        </div>
                    </div>
                </div>
            </div>

            <template v-else>
                <div v-if="viewMode === 'grid'" class="grid gap-4 sm:grid-cols-2">
                    <div
                        v-for="chapter in paginatedChapters"
                        :key="chapter.id"
                        class="rounded-xl border bg-card/80 p-4 shadow-sm"
                    >
                        <div
                            class="mb-3 h-20 w-full overflow-hidden rounded-lg bg-cover bg-center"
                            :style="previewStyle(chapter.preview)"
                        ></div>
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-muted-foreground">Chapitre {{ chapter.number }}</p>
                                <h3 class="text-lg font-semibold leading-tight">{{ chapter.title }}</h3>
                            </div>
                            <span
                                class="rounded-full px-2 py-1 text-[11px]"
                                :class="{
                                    'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200': chapter.status === 'published',
                                    'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-200': chapter.status === 'scheduled',
                                    'bg-slate-200 text-slate-800 dark:bg-slate-800 dark:text-slate-200': chapter.status === 'draft',
                                }"
                            >
                                {{
                                    chapter.status === 'published'
                                        ? 'Publié'
                                        : chapter.status === 'scheduled'
                                          ? 'Programmé'
                                          : 'Brouillon'
                                }}
                            </span>
                        </div>
                                <div class="mt-2 text-xs text-muted-foreground space-y-1">
                                    <p>Pages : {{ chapter.pages }}</p>
                                    <p>
                                        {{
                                            chapter.status === 'scheduled'
                                                ? `Publication le ${chapter.scheduledFor}`
                                                : chapter.status === 'published'
                                                  ? `Publié le ${chapter.publishedAt}`
                                                  : 'Non publié'
                                        }}
                                    </p>
                                    <p>Vues : {{ chapter.views }}</p>
                                    <p v-if="chapter.likes !== undefined">Likes : {{ chapter.likes }} · Dislikes : {{ chapter.dislikes }} · Note {{ chapter.rating ?? '—' }}</p>
                                </div>
                        <div class="mt-3 flex flex-wrap gap-2 text-xs">
                            <Link
                                class="rounded-md border px-3 py-1 transition hover:border-primary"
                                :href="`/creator/chapters/${chapter.id}/edit`"
                            >
                                Éditer
                            </Link>
                            <Link
                                class="rounded-md border px-3 py-1 transition hover:border-primary"
                                :href="`/creator/chapters/${chapter.id}`"
                            >
                                Détails
                            </Link>
                            <button
                                type="button"
                                class="rounded-md border px-3 py-1 transition hover:border-primary"
                            >
                                Programmer
                            </button>
                            <button
                                type="button"
                                class="rounded-md border px-3 py-1 transition hover:border-primary"
                            >
                                Publier
                            </button>
                        </div>
                    </div>
                </div>

                <div v-else class="overflow-hidden rounded-xl border bg-card/80 shadow-sm">
                    <div class="hidden grid-cols-[auto_1fr_auto_auto] items-center gap-4 border-b px-4 py-3 text-xs font-semibold text-muted-foreground md:grid">
                        <span>#</span>
                        <span>Titre</span>
                        <span>Statut</span>
                        <span>Actions</span>
                    </div>
                    <div
                        v-for="chapter in paginatedChapters"
                        :key="chapter.id"
                        class="grid items-center gap-3 border-b px-4 py-3 last:border-b-0 md:grid-cols-[auto_1fr_auto_auto]"
                    >
                        <div class="text-sm font-semibold">#{{ chapter.number }}</div>
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <p class="text-sm font-semibold">{{ chapter.title }}</p>
                                <span
                                    class="rounded-full px-2 py-1 text-[11px]"
                                    :class="{
                                        'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200': chapter.status === 'published',
                                        'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-200': chapter.status === 'scheduled',
                                        'bg-slate-200 text-slate-800 dark:bg-slate-800 dark:text-slate-200': chapter.status === 'draft',
                                    }"
                                >
                                    {{
                                        chapter.status === 'published'
                                            ? 'Publié'
                                            : chapter.status === 'scheduled'
                                              ? 'Programmé'
                                              : 'Brouillon'
                                    }}
                                </span>
                            </div>
                            <div
                                class="h-16 w-full overflow-hidden rounded-md bg-cover bg-center"
                                :style="previewStyle(chapter.preview)"
                            ></div>
                            <p class="text-xs text-muted-foreground">
                                Pages : {{ chapter.pages }} · Vues : {{ chapter.views }}
                            </p>
                            <p class="text-xs text-muted-foreground">
                                {{
                                    chapter.status === 'scheduled'
                                        ? `Publication le ${chapter.scheduledFor}`
                                        : chapter.status === 'published'
                                          ? `Publié le ${chapter.publishedAt}`
                                          : 'Non publié'
                                }}
                            </p>
                        </div>
                        <div class="text-sm md:text-center">{{ chapter.pages }} pages</div>
                        <div class="flex flex-wrap items-center gap-2 md:justify-end">
                            <Link
                                class="text-xs font-semibold text-primary hover:underline"
                                :href="`/creator/chapters/${chapter.id}/edit`"
                            >
                                Éditer
                            </Link>
                            <button
                                type="button"
                                class="text-xs font-semibold text-primary hover:underline"
                            >
                                Programmer
                            </button>
                            <button
                                type="button"
                                class="text-xs font-semibold text-primary hover:underline"
                            >
                                Publier
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between gap-3 rounded-lg border bg-card/70 px-4 py-3 text-sm text-muted-foreground">
                    <div>
                        Page {{ currentPage }} / {{ totalPages }}
                        · {{ filteredChapters.length }} chapitre(s)
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
