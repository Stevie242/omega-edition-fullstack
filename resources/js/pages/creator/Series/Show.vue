<script setup lang="ts">
import CreatorLayout from '@/layouts/CreatorLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ArrowLeft, LayoutGrid, List, Pencil, Plus, Tag, BookOpen } from 'lucide-vue-next';
import { computed, ref } from 'vue';

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

type Series = {
    id: string;
    title: string;
    type: 'manga' | 'webtoon';
    status: 'ongoing' | 'hiatus' | 'completed';
    format: 'oneshot' | 'series' | 'miniseries';
    frequency: 'weekly' | 'biweekly' | 'monthly' | 'irregular';
    language?: string;
    synopsis?: string | null;
    cover?: string | null;
    hero?: string | null;
    likes?: number;
    dislikes?: number;
    rating?: number;
    chapters?: number;
    views?: number;
    tags: string[];
    updatedAt?: string | null;
};

const props = defineProps<{
    seriesId: string;
    series: Series;
    chapters: Chapter[];
    chaptersMeta: { current_page: number; last_page: number; total: number };
}>();

const viewMode = ref<'grid' | 'list'>('grid');
const loading = ref(false);

const statusLabel = (status: Series['status']) =>
    status === 'ongoing' ? 'En cours' : status === 'hiatus' ? 'Pause' : 'Terminée';
const typeLabel = (type: Series['type']) => (type === 'manga' ? 'Manga' : 'Webtoon');
const formatLabel = (format: Series['format']) =>
    format === 'oneshot' ? 'One-shot' : format === 'miniseries' ? 'Mini-série' : 'Série longue';
const frequencyLabel = (frequency: Series['frequency']) => {
    switch (frequency) {
        case 'weekly':
            return 'Hebdomadaire';
        case 'biweekly':
            return 'Bi-hebdomadaire';
        case 'monthly':
            return 'Mensuel';
        default:
            return 'Irrégulier';
    }
};

const previewStyle = (src?: string | null) =>
    src
        ? { backgroundImage: `url(${src})` }
        : { backgroundImage: 'linear-gradient(135deg, #1f2937 0%, #0ea5e9 100%)' };

const coverStyle = computed(() =>
    props.series.cover
        ? { backgroundImage: `linear-gradient(180deg, rgba(0,0,0,.45), rgba(0,0,0,.75)), url(${props.series.cover})` }
        : { backgroundImage: 'linear-gradient(180deg, rgba(0,0,0,.65), rgba(0,0,0,.85))' },
);

const goToPage = (page: number) => {
    if (page < 1 || page > props.chaptersMeta.last_page) return;
    loading.value = true;
    router.get(
        `/creator/series/${props.seriesId}`,
        { page },
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

const statusClass = (status: Chapter['status']) => {
    if (status === 'published')
        return 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200';
    if (status === 'scheduled')
        return 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-200';
    return 'bg-slate-200 text-slate-800 dark:bg-slate-800 dark:text-slate-200';
};
</script>

<template>
    <CreatorLayout
        :title="series.title"
        :description="`Vue détaillée de la série #${props.seriesId}`"
        :breadcrumbs="[
            { title: 'Séries', href: '/creator/series' },
            { title: series.title },
        ]"
    >
        <div class="space-y-6">
            <div
                class="overflow-hidden rounded-xl border bg-cover bg-center shadow-sm"
                :style="coverStyle"
            >
                <div class="bg-gradient-to-r from-black/70 to-black/30 p-6 lg:p-8">
                    <div class="flex flex-wrap items-center gap-3 text-sm text-muted-foreground">
                        <Link
                            href="/creator/series"
                            class="inline-flex items-center gap-2 rounded-md border border-white/20 px-3 py-2 text-white hover:border-primary"
                        >
                            <ArrowLeft class="h-4 w-4" />
                            Retour aux séries
                        </Link>
                        <Link
                            :href="`/creator/series/${props.seriesId}/edit`"
                            class="inline-flex items-center gap-2 rounded-md border border-white/20 px-3 py-2 text-white hover:border-primary"
                        >
                            <Pencil class="h-4 w-4" />
                            Éditer
                        </Link>
                        <Link
                            :href="`/creator/series/${props.seriesId}/chapters/create`"
                            class="inline-flex items-center gap-2 rounded-md bg-primary px-3 py-2 text-sm font-semibold text-primary-foreground shadow hover:opacity-90"
                        >
                            <Plus class="h-4 w-4" />
                            Nouveau chapitre
                        </Link>
                    </div>

                    <div class="mt-6 grid gap-6 lg:grid-cols-[2fr_1fr]">
                        <div class="space-y-3 text-white">
                            <h1 class="text-3xl font-semibold leading-tight">{{ series.title }}</h1>
                            <p class="max-w-3xl text-sm text-white/80" v-if="series.synopsis">
                                {{ series.synopsis }}
                            </p>
                            <div class="flex flex-wrap gap-2 text-xs">
                                <span class="rounded-full bg-white/10 px-2 py-1">{{ typeLabel(series.type) }}</span>
                                <span class="rounded-full bg-white/10 px-2 py-1">{{ statusLabel(series.status) }}</span>
                                <span class="rounded-full bg-white/10 px-2 py-1">{{ formatLabel(series.format) }}</span>
                                <span class="rounded-full bg-white/10 px-2 py-1">{{ frequencyLabel(series.frequency) }}</span>
                                <span
                                    v-if="series.language"
                                    class="rounded-full bg-white/10 px-2 py-1"
                                >
                                    Langue : {{ series.language?.toUpperCase() }}
                                </span>
                            </div>
                            <div class="flex flex-wrap gap-2 text-xs">
                                <span v-for="tag in series.tags" :key="tag" class="inline-flex items-center gap-1 rounded-full bg-white/10 px-2 py-1">
                                    <Tag class="h-3 w-3" />
                                    {{ tag }}
                                </span>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3 text-sm text-white/90 sm:grid-cols-3">
                            <div class="rounded-lg bg-white/10 p-3">
                                <p class="text-xs text-white/60">Chapitres</p>
                                <p class="text-xl font-semibold">{{ series.chapters ?? 0 }}</p>
                            </div>
                            <div class="rounded-lg bg-white/10 p-3">
                                <p class="text-xs text-white/60">Vues</p>
                                <p class="text-xl font-semibold">{{ series.views ?? 0 }}</p>
                            </div>
                            <div class="rounded-lg bg-white/10 p-3">
                                <p class="text-xs text-white/60">Note</p>
                                <p class="text-xl font-semibold">{{ series.rating ?? '—' }}</p>
                            </div>
                            <div class="rounded-lg bg-white/10 p-3">
                                <p class="text-xs text-white/60">Likes</p>
                                <p class="text-xl font-semibold">{{ series.likes ?? 0 }}</p>
                            </div>
                            <div class="rounded-lg bg-white/10 p-3">
                                <p class="text-xs text-white/60">Dislikes</p>
                                <p class="text-xl font-semibold">{{ series.dislikes ?? 0 }}</p>
                            </div>
                            <div class="rounded-lg bg-white/10 p-3">
                                <p class="text-xs text-white/60">Maj</p>
                                <p class="text-xs">{{ series.updatedAt ?? '—' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-[2fr_1fr]">
                <div class="space-y-4">
                    <div class="flex items-center justify-between gap-3 rounded-xl border bg-card p-4 shadow-sm">
                        <div class="flex items-center gap-2 text-sm text-muted-foreground">
                            <BookOpen class="h-4 w-4" />
                            Chapitres
                        </div>
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

                    <div v-if="loading" class="grid gap-4 sm:grid-cols-2">
                        <div v-for="n in 6" :key="n" class="rounded-xl border bg-card/80 p-4 shadow-sm">
                            <div class="h-20 w-full animate-pulse rounded bg-muted" />
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
                            <div class="mt-3">
                                <Link
                                    :href="`/creator/series/${props.seriesId}/chapters/create`"
                                    class="inline-flex items-center gap-2 rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground shadow hover:opacity-90"
                                >
                                    <Plus class="h-4 w-4" />
                                    Créer le premier
                                </Link>
                            </div>
                        </div>

                        <div v-else>
                            <div v-if="viewMode === 'grid'" class="grid gap-4 sm:grid-cols-2">
                                <div
                                    v-for="chapter in chapters"
                                    :key="chapter.id"
                                    class="rounded-xl border bg-card/80 p-4 shadow-sm"
                                >
                                    <div class="mb-3 flex items-center justify-between text-xs text-muted-foreground">
                                        <span>#{{ chapter.number }}</span>
                                        <span class="rounded-full px-2 py-1" :class="statusClass(chapter.status)">
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
                                        class="mb-3 h-24 w-full overflow-hidden rounded-lg bg-cover bg-center"
                                        :style="previewStyle(chapter.preview)"
                                    ></div>
                                    <h3 class="text-lg font-semibold leading-tight">{{ chapter.title }}</h3>
                                    <p class="mt-1 text-xs text-muted-foreground">
                                        {{ chapter.pages }} page(s) · Vues {{ chapter.views ?? '—' }}
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
                                    <div class="mt-3 flex flex-wrap gap-2 text-xs">
                                        <Link
                                            class="rounded-md border px-3 py-1 transition hover:border-primary"
                                            :href="`/creator/chapters/${chapter.id}`"
                                        >
                                            Ouvrir
                                        </Link>
                                        <Link
                                            class="rounded-md border px-3 py-1 transition hover:border-primary"
                                            :href="`/creator/chapters/${chapter.id}/edit`"
                                        >
                                            Éditer
                                        </Link>
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
                                            <span class="rounded-full px-2 py-1 text-[11px]" :class="statusClass(chapter.status)">
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
                                            Pages : {{ chapter.pages }} · Vues : {{ chapter.views ?? '—' }}
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
                                            :href="`/creator/chapters/${chapter.id}`"
                                        >
                                            Ouvrir
                                        </Link>
                                        <Link
                                            class="text-xs font-semibold text-primary hover:underline"
                                            :href="`/creator/chapters/${chapter.id}/edit`"
                                        >
                                            Éditer
                                        </Link>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3 flex items-center justify-between gap-3 rounded-lg border bg-card/70 px-4 py-3 text-sm text-muted-foreground">
                                <div>
                                    Page {{ chaptersMeta.current_page }} / {{ chaptersMeta.last_page }}
                                    · {{ chaptersMeta.total }} chapitre(s)
                                </div>
                                <div class="flex items-center gap-2">
                                    <button
                                        type="button"
                                        class="rounded-md border px-3 py-1 transition hover:border-primary disabled:cursor-not-allowed disabled:opacity-50"
                                        :disabled="chaptersMeta.current_page === 1"
                                        @click="goToPage(chaptersMeta.current_page - 1)"
                                    >
                                        Précédent
                                    </button>
                                    <button
                                        type="button"
                                        class="rounded-md border px-3 py-1 transition hover:border-primary disabled:cursor-not-allowed disabled:opacity-50"
                                        :disabled="chaptersMeta.current_page === chaptersMeta.last_page"
                                        @click="goToPage(chaptersMeta.current_page + 1)"
                                    >
                                        Suivant
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="space-y-4">
                    <div class="overflow-hidden rounded-xl border bg-card shadow-sm">
                        <div
                            class="h-32 w-full bg-cover bg-center"
                            :style="previewStyle(series.hero || series.cover)"
                        ></div>
                        <div class="space-y-3 p-6 text-sm text-muted-foreground">
                            <p v-if="series.synopsis">{{ series.synopsis }}</p>
                            <div class="flex flex-wrap gap-2 text-xs">
                                <span class="rounded-full bg-muted px-2 py-1">{{ typeLabel(series.type) }}</span>
                                <span class="rounded-full bg-muted px-2 py-1">{{ statusLabel(series.status) }}</span>
                                <span class="rounded-full bg-muted px-2 py-1">{{ formatLabel(series.format) }}</span>
                                <span class="rounded-full bg-muted px-2 py-1">{{ frequencyLabel(series.frequency) }}</span>
                                <span v-if="series.language" class="rounded-full bg-muted px-2 py-1">
                                    Langue : {{ series.language?.toUpperCase() }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </CreatorLayout>
</template>
