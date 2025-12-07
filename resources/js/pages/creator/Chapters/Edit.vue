<script setup lang="ts">
import CreatorLayout from '@/layouts/CreatorLayout.vue';
import { Link } from '@inertiajs/vue3';
import { ArrowLeft, CalendarClock, ChevronDown, ChevronUp, GripVertical, Upload, X } from 'lucide-vue-next';
import { computed, onMounted, ref } from 'vue';

const props = defineProps<{
    chapterId: string;
}>();

type PageItem = {
    id: number;
    name: string;
    size: string;
    preview?: string;
    file?: File;
    order: number;
};

const loading = ref(true);

const form = ref({
    title: 'Chapitre 42',
    number: 42,
    status: 'scheduled',
    scheduledAt: '2025-12-15T10:00',
    pages: [
        {
            id: 1,
            name: 'page-1.png',
            size: '420 KB',
            preview: 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=600&q=60',
            order: 0,
        },
        {
            id: 2,
            name: 'page-2.png',
            size: '388 KB',
            preview: 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=600&q=60',
            order: 1,
        },
        {
            id: 3,
            name: 'page-3.png',
            size: '401 KB',
            preview: 'https://images.unsplash.com/photo-1504274066651-8d31a536b11a?auto=format&fit=crop&w=600&q=60',
            order: 2,
        },
    ] as PageItem[],
});

const sortedPages = computed(() =>
    [...form.value.pages].sort((a, b) => a.order - b.order),
);

const dragSourceId = ref<number | null>(null);

const addFiles = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const files = target.files;
    if (!files) return;

    const startOrder = form.value.pages.length;
    Array.from(files).forEach((file, idx) => {
        const reader = new FileReader();
        reader.onload = (e) => {
            const preview = (e.target?.result as string) ?? undefined;
            form.value.pages.push({
                id: Date.now() + idx,
                name: file.name,
                size: `${Math.round(file.size / 1024)} KB`,
                preview,
                file,
                order: startOrder + idx,
            });
        };
        reader.readAsDataURL(file);
    });
    target.value = '';
};

const removePage = (id: number) => {
    form.value.pages = form.value.pages.filter((p) => p.id !== id);
};

const movePage = (id: number, direction: 'up' | 'down') => {
    const pages = sortedPages.value;
    const index = pages.findIndex((p) => p.id === id);
    if (index === -1) return;
    const targetIndex = direction === 'up' ? index - 1 : index + 1;
    if (targetIndex < 0 || targetIndex >= pages.length) return;
    const current = pages[index];
    const target = pages[targetIndex];
    const tmp = current.order;
    current.order = target.order;
    target.order = tmp;
    form.value.pages = [...pages];
};

const handleDragStart = (id: number) => {
    dragSourceId.value = id;
};

const handleDrop = (id: number) => {
    if (dragSourceId.value === null || dragSourceId.value === id) return;
    const pages = sortedPages.value;
    const current = pages.find((p) => p.id === dragSourceId.value);
    const target = pages.find((p) => p.id === id);
    if (!current || !target) return;
    const tmp = current.order;
    current.order = target.order;
    target.order = tmp;
    form.value.pages = [...pages];
    dragSourceId.value = null;
};

const statusOptions = [
    { value: 'draft', label: 'Brouillon' },
    { value: 'scheduled', label: 'Programmé' },
    { value: 'published', label: 'Publié' },
];

onMounted(() => {
    setTimeout(() => (loading.value = false), 500);
});
</script>

<template>
    <CreatorLayout
        title="Éditer un chapitre"
        :description="`Chapitre #${props.chapterId} — mise à jour contenu, pages et publication.`"
        :breadcrumbs="[
            { title: 'Chapitres', href: '/creator/series' },
            { title: `Chapitre #${props.chapterId}` },
        ]"
    >
        <div class="space-y-4">
            <Link
                class="inline-flex items-center gap-2 rounded-md border px-3 py-2 text-sm hover:border-primary hover:text-primary"
                href="/creator/series"
            >
                <ArrowLeft class="h-4 w-4" />
                Retour
            </Link>

            <div class="grid gap-6 lg:grid-cols-[1.5fr_1fr]">
                <div class="space-y-4">
                    <div class="rounded-xl border bg-card p-6 shadow-sm">
                        <h2 class="text-lg font-semibold mb-4">Informations principales</h2>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="space-y-2 md:col-span-2">
                                <label class="text-sm font-medium">Titre</label>
                                <input
                                    v-model="form.title"
                                    type="text"
                                    class="w-full rounded-md border bg-background px-3 py-2 text-sm outline-none ring-2 ring-transparent transition focus:border-primary focus:ring-primary/20"
                                />
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium">Numéro</label>
                                <input
                                    v-model="form.number"
                                    type="number"
                                    min="1"
                                    class="w-full rounded-md border bg-background px-3 py-2 text-sm outline-none ring-2 ring-transparent transition focus:border-primary focus:ring-primary/20"
                                />
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium">Statut</label>
                                <select
                                    v-model="form.status"
                                    class="w-full rounded-md border bg-background px-3 py-2 text-sm shadow-sm transition focus:border-primary focus:outline-none focus:ring focus:ring-primary/20"
                                >
                                    <option v-for="opt in statusOptions" :key="opt.value" :value="opt.value">
                                        {{ opt.label }}
                                    </option>
                                </select>
                            </div>
                            <div class="space-y-2 md:col-span-2">
                                <label class="text-sm font-medium">Date/heure de publication (si programmé)</label>
                                <div class="relative">
                                    <CalendarClock class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                                    <input
                                        v-model="form.scheduledAt"
                                        type="datetime-local"
                                        class="w-full rounded-md border bg-background py-2 pl-9 pr-3 text-sm outline-none ring-2 ring-transparent transition focus:border-primary focus:ring-primary/20"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-xl border bg-card p-6 shadow-sm space-y-4">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold">Pages du chapitre</h2>
                            <label class="inline-flex cursor-pointer items-center gap-2 rounded-md border border-dashed px-3 py-2 text-sm text-muted-foreground hover:border-primary">
                                <Upload class="h-4 w-4" />
                                Ajouter des pages
                                <input type="file" multiple accept="image/*" class="hidden" @change="addFiles" />
                            </label>
                        </div>

                        <div v-if="loading" class="space-y-3">
                            <div v-for="n in 3" :key="n" class="flex items-center gap-4 rounded-lg border bg-card/80 p-4 shadow-sm">
                                <div class="h-28 w-24 animate-pulse rounded-md bg-muted" />
                                <div class="flex-1 space-y-2">
                                    <div class="h-4 w-40 animate-pulse rounded bg-muted" />
                                    <div class="h-3 w-20 animate-pulse rounded bg-muted" />
                                    <div class="flex gap-2">
                                        <div class="h-7 w-20 animate-pulse rounded bg-muted" />
                                        <div class="h-7 w-24 animate-pulse rounded bg-muted" />
                                    </div>
                                </div>
                                <div class="h-7 w-16 animate-pulse rounded bg-muted" />
                            </div>
                        </div>

                        <div v-else-if="!sortedPages.length" class="rounded-lg border border-dashed bg-muted/40 p-4 text-sm text-muted-foreground">
                            Aucune page. Ajoute des images pour voir un aperçu et réordonner.
                        </div>

                        <div v-else class="flex flex-col gap-3">
                            <div
                                v-for="page in sortedPages"
                                :key="page.id"
                                class="flex items-center gap-4 rounded-lg border bg-card/80 p-4 shadow-sm"
                                draggable="true"
                                @dragstart="handleDragStart(page.id)"
                                @dragover.prevent
                                @drop="handleDrop(page.id)"
                            >
                                <div
                                    class="h-28 w-24 shrink-0 overflow-hidden rounded-md bg-cover bg-center"
                                    :style="page.preview ? { backgroundImage: `url(${page.preview})` } : { backgroundImage: 'linear-gradient(135deg, #1f2937 0%, #0ea5e9 100%)' }"
                                ></div>
                                <div class="flex flex-1 flex-col gap-2 text-sm">
                                    <div class="flex items-start justify-between gap-2">
                                        <div>
                                            <p class="font-semibold">{{ page.name }}</p>
                                            <p class="text-xs text-muted-foreground">{{ page.size }}</p>
                                        </div>
                                        <button
                                            type="button"
                                            class="rounded-md p-1 text-muted-foreground hover:text-destructive"
                                            @click="removePage(page.id)"
                                        >
                                            <X class="h-4 w-4" />
                                        </button>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button
                                            type="button"
                                            class="inline-flex items-center gap-1 rounded-md border px-2 py-1 text-xs hover:border-primary disabled:opacity-50"
                                            :disabled="page.order === sortedPages[0].order"
                                            @click="movePage(page.id, 'up')"
                                        >
                                            <ChevronUp class="h-4 w-4" />
                                            Monter
                                        </button>
                                        <button
                                            type="button"
                                            class="inline-flex items-center gap-1 rounded-md border px-2 py-1 text-xs hover:border-primary disabled:opacity-50"
                                            :disabled="page.order === sortedPages[sortedPages.length - 1].order"
                                            @click="movePage(page.id, 'down')"
                                        >
                                            <ChevronDown class="h-4 w-4" />
                                            Descendre
                                        </button>
                                        <span class="ml-auto inline-flex items-center gap-1 rounded-md bg-muted px-2 py-1 text-[11px] text-muted-foreground">
                                            <GripVertical class="h-3.5 w-3.5" />
                                            Ordre {{ page.order + 1 }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="rounded-xl border bg-card p-6 shadow-sm space-y-3">
                        <h2 class="text-lg font-semibold">Actions</h2>
                        <div class="flex flex-col gap-3">
                            <button
                                type="button"
                                class="inline-flex items-center justify-center gap-2 rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground shadow hover:opacity-90"
                            >
                                Mettre à jour
                            </button>
                            <button
                                type="button"
                                class="inline-flex items-center justify-center gap-2 rounded-md border px-4 py-2 text-sm font-medium transition hover:border-primary"
                            >
                                Programmer
                            </button>
                            <button
                                type="button"
                                class="inline-flex items-center justify-center gap-2 rounded-md border px-4 py-2 text-sm font-medium transition hover:border-primary"
                            >
                                Publier maintenant
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </CreatorLayout>
</template>
