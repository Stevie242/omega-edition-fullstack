<script setup lang="ts">
import CreatorLayout from '@/layouts/CreatorLayout.vue';
import { Link } from '@inertiajs/vue3';
import { ArrowLeft, CalendarClock, Pencil, Play } from 'lucide-vue-next';
import { computed } from 'vue';

type Chapter = {
    id: string;
    title: string;
    number: number;
    status: 'draft' | 'scheduled' | 'published';
    scheduledFor?: string | null;
    publishedAt?: string | null;
    pagesCount: number;
    views: number;
};

type PageItem = {
    id: string;
    order: number;
    url?: string | null;
    size_kb?: number | null;
};

const props = defineProps<{
    seriesId: string;
    chapter: Chapter & { pages: PageItem[] };
}>();

const previewStyle = (src?: string | null) =>
    src
        ? { backgroundImage: `url(${src})` }
        : { backgroundImage: 'linear-gradient(135deg, #1f2937 0%, #0ea5e9 100%)' };

const statusLabel = computed(() => {
    if (props.chapter.status === 'published') return 'Publié';
    if (props.chapter.status === 'scheduled') return 'Programmé';
    return 'Brouillon';
});

const statusClass = computed(() => {
    if (props.chapter.status === 'published')
        return 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200';
    if (props.chapter.status === 'scheduled')
        return 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-200';
    return 'bg-slate-200 text-slate-800 dark:bg-slate-800 dark:text-slate-200';
});
</script>

<template>
    <CreatorLayout
        title="Détail chapitre"
        :description="`Aperçu et actions du chapitre #${chapter.id}`"
        :breadcrumbs="[
            { title: 'Séries', href: '/creator/series' },
            { title: seriesId, href: `/creator/series/${seriesId}` },
            { title: 'Chapitres', href: `/creator/series/${seriesId}/chapters` },
            { title: `Chapitre #${chapter.id}` },
        ]"
    >
        <div class="space-y-4">
            <Link
                class="inline-flex items-center gap-2 rounded-md border px-3 py-2 text-sm hover:border-primary hover:text-primary"
                :href="`/creator/series/${seriesId}/chapters`"
            >
                <ArrowLeft class="h-4 w-4" />
                Retour
            </Link>

            <div class="grid gap-6 lg:grid-cols-[1.5fr_1fr]">
                <div class="space-y-4">
                    <div class="rounded-xl border bg-card p-6 shadow-sm space-y-3">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-xs text-muted-foreground">Chapitre {{ chapter.number }}</p>
                                <h1 class="text-2xl font-semibold leading-tight">{{ chapter.title }}</h1>
                            </div>
                            <span class="rounded-full px-3 py-1 text-xs font-semibold" :class="statusClass">
                                {{ statusLabel }}
                            </span>
                        </div>
                        <div class="flex flex-wrap gap-2 text-xs text-muted-foreground">
                            <span class="rounded-full bg-muted px-2 py-1">Pages : {{ chapter.pagesCount }}</span>
                            <span class="rounded-full bg-muted px-2 py-1">Vues : {{ chapter.views }}</span>
                            <span class="rounded-full bg-muted px-2 py-1">
                                {{
                                    chapter.status === 'scheduled'
                                        ? `Publication le ${chapter.scheduledFor}`
                                        : chapter.status === 'published'
                                          ? `Publié le ${chapter.publishedAt}`
                                          : 'Non publié'
                                }}
                            </span>
                        </div>
                        <div class="flex flex-wrap gap-3 text-sm">
                            <Link
                                :href="`/creator/chapters/${chapter.id}/edit`"
                                class="inline-flex items-center gap-2 rounded-md border px-3 py-2 transition hover:border-primary"
                            >
                                <Pencil class="h-4 w-4" />
                                Éditer
                            </Link>
                            <button
                                type="button"
                                class="inline-flex items-center gap-2 rounded-md border px-3 py-2 transition hover:border-primary"
                            >
                                <CalendarClock class="h-4 w-4" />
                                Programmer
                            </button>
                            <button
                                type="button"
                                class="inline-flex items-center gap-2 rounded-md border px-3 py-2 transition hover:border-primary"
                            >
                                <Play class="h-4 w-4" />
                                Publier
                            </button>
                        </div>
                    </div>

                    <div class="rounded-xl border bg-card p-6 shadow-sm">
                        <h2 class="text-lg font-semibold mb-3">Pages</h2>
                        <div v-if="!chapter.pages.length" class="rounded-lg border border-dashed bg-muted/30 p-3 text-sm text-muted-foreground">
                            Aucune page trouvée pour ce chapitre.
                        </div>
                        <div v-else class="flex flex-col gap-3">
                            <div
                                v-for="page in chapter.pages"
                                :key="page.id"
                                class="flex items-center gap-3 rounded-lg border bg-card/80 p-3"
                            >
                                <div
                                    class="h-24 w-20 shrink-0 overflow-hidden rounded-md bg-cover bg-center"
                                    :style="previewStyle(page.url)"
                                ></div>
                                <div class="flex flex-1 flex-col gap-1 text-sm">
                                    <p class="font-semibold">Page {{ page.order + 1 }}</p>
                                    <p class="text-xs text-muted-foreground" v-if="page.size_kb">
                                        {{ page.size_kb }} KB
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-xl border bg-card p-6 shadow-sm space-y-3">
                        <h2 class="text-lg font-semibold">Commentaires (mock)</h2>
                        <div class="rounded-lg border bg-muted/30 p-3 text-sm">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold">ReaderX</p>
                                    <p class="text-xs text-muted-foreground">“Super mise en scène !”</p>
                                </div>
                                <div class="text-xs text-muted-foreground">+24 / -2</div>
                            </div>
                            <div class="mt-2 flex flex-wrap gap-2 text-xs">
                                <button class="rounded-md border px-2 py-1 hover:border-primary">Répondre</button>
                                <button class="rounded-md border px-2 py-1 hover:border-primary">Aimer</button>
                                <button class="rounded-md border px-2 py-1 hover:border-primary">Pas d’accord</button>
                            </div>
                            <div class="mt-2 rounded-md border bg-card/60 p-2 text-xs">
                                <p class="font-semibold">Réponse auteur</p>
                                <p class="text-muted-foreground">Merci ! Prochain chapitre le 15/12.</p>
                            </div>
                        </div>
                        <div class="rounded-lg border bg-muted/30 p-3 text-sm">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold">MangaFan</p>
                                    <p class="text-xs text-muted-foreground">“La fin est folle.”</p>
                                </div>
                                <div class="text-xs text-muted-foreground">+12 / -1</div>
                            </div>
                            <div class="mt-2 flex flex-wrap gap-2 text-xs">
                                <button class="rounded-md border px-2 py-1 hover:border-primary">Répondre</button>
                                <button class="rounded-md border px-2 py-1 hover:border-primary">Aimer</button>
                                <button class="rounded-md border px-2 py-1 hover:border-primary">Pas d’accord</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="rounded-xl border bg-card p-6 shadow-sm">
                        <h2 class="text-lg font-semibold">Performances (mock)</h2>
                        <div class="mt-3 grid gap-3 md:grid-cols-2">
                            <div class="rounded-lg bg-muted/40 p-3">
                                <p class="text-xs text-muted-foreground">Vues</p>
                                <p class="text-xl font-semibold">12.4K</p>
                                <p class="text-xs text-emerald-600 dark:text-emerald-300">+4% vs chap. précédent</p>
                            </div>
                            <div class="rounded-lg bg-muted/40 p-3">
                                <p class="text-xs text-muted-foreground">Likes ratio</p>
                                <p class="text-xl font-semibold">—</p>
                                <p class="text-xs text-muted-foreground">Rapport likes/dislikes</p>
                            </div>
                            <div class="rounded-lg bg-muted/40 p-3 md:col-span-2">
                                <p class="text-xs text-muted-foreground">Temps moyen de lecture</p>
                                <p class="text-xl font-semibold">6m42s</p>
                                <p class="text-xs text-muted-foreground">Donnée fictive</p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-xl border bg-card p-6 shadow-sm">
                        <h2 class="text-lg font-semibold">Notes / Rappels (mock)</h2>
                        <p class="text-sm text-muted-foreground">
                            Prévoir vérification des assets avant publication. Vérifier les balises de contenu.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </CreatorLayout>
</template>
