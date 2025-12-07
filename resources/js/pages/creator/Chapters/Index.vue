<script setup lang="ts">
import CreatorLayout from '@/layouts/CreatorLayout.vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import { ArrowLeft, CalendarClock, LayoutGrid, List, Plus, Search } from 'lucide-vue-next';
import { useToast } from 'primevue/usetoast';

type Chapter = {
    id: string;
    title: string;
    number: number;
    status: 'draft' | 'scheduled' | 'published';
    scheduledFor?: string | null;
    publishedAt?: string | null;
    pages: number;
    views: number;
    preview?: string | null;
};

const props = defineProps<{
    seriesId: string;
    chapters: Chapter[];
    meta: { current_page: number; last_page: number; total: number };
    filters?: { status?: string; search?: string };
}>();

const viewMode = ref<'grid' | 'list'>('grid');
const query = ref(props.filters?.search ?? '');
const statusFilter = ref<'all' | Chapter['status']>(
    (props.filters?.status as any) ?? 'all',
);
const loading = ref(false);

const toast = useToast();
const flash = usePage().props.flash as { success?: string };

const previewStyle = (src?: string | null) =>
    src
        ? { backgroundImage: `url(${src})` }
        : { backgroundImage: 'linear-gradient(135deg, #1f2937 0%, #0ea5e9 100%)' };

const fetchPage = (page = 1) => {
    loading.value = true;
    router.get(
        `/creator/series/${props.seriesId}/chapters`,
        {
            page,
            search: query.value || undefined,
            status: statusFilter.value === 'all' ? undefined : statusFilter.value,
        },
        {
            preserveState: true,
            replace: true,
            preserveScroll: true,
            onFinish: () => {
                loading.value = false;
            },
        },
    );
};

watch([query, statusFilter], () => {
    fetchPage(1);
});

const nextPage = () => {
    if (props.meta.current_page < props.meta.last_page) {
        fetchPage(props.meta.current_page + 1);
    }
};

const prevPage = () => {
    if (props.meta.current_page > 1) {
        fetchPage(props.meta.current_page - 1);
    }
};

if (flash?.success) {
    toast.add({ severity: 'success', summary: flash.success, life: 2500 });
}
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
                <div v-for="n in 8" :key="n" class="rounded-xl border bg-card/80 p-4 shadow-sm">
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
                <div v-if="!chapters.length" class="rounded-xl border bg-card/80 p-6 text-center text-sm text-muted-foreground">
                    Aucun chapitre pour le moment.
                </div>

                <div v-else-if="viewMode === 'grid'" class="grid gap-4 sm:grid-cols-2">
                    <div
                        v-for="chapter in chapters"
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
                        v-for="chapter in chapters"
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
                        Page {{ meta.current_page }} / {{ meta.last_page }}
                        · {{ meta.total }} chapitre(s)
                    </div>
                    <div class="flex items-center gap-2">
                        <button
                            type="button"
                            class="rounded-md border px-3 py-1 transition hover:border-primary disabled:cursor-not-allowed disabled:opacity-50"
                            :disabled="meta.current_page === 1"
                            @click="prevPage"
                        >
                            Précédent
                        </button>
                        <button
                            type="button"
                            class="rounded-md border px-3 py-1 transition hover:border-primary disabled:cursor-not-allowed disabled:opacity-50"
                            :disabled="meta.current_page === meta.last_page"
                            @click="nextPage"
                        >
                            Suivant
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </CreatorLayout>
</template>
