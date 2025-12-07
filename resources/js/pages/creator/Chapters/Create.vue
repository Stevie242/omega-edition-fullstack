<script setup lang="ts">
import CreatorLayout from '@/layouts/CreatorLayout.vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import { ArrowLeft, CalendarClock, ChevronDown, ChevronUp, GripVertical, Loader2, Upload, X } from 'lucide-vue-next';
import { computed, onUnmounted, reactive, ref, watch } from 'vue';

const props = defineProps<{
    seriesId: string;
}>();

type PageItem = {
    id: number;
    name: string;
    size: string;
    preview?: string;
    file?: File;
    order: number;
};

type ChapterForm = {
    title: string;
    number: string;
    status: 'draft' | 'scheduled' | 'published';
    scheduled_for: string;
    pages: PageItem[];
};

const form = reactive<ChapterForm>({
    title: '',
    number: '',
    status: 'draft',
    scheduled_for: '',
    pages: [],
});

const processing = ref(false);

const sortedPages = computed(() => [...form.pages].sort((a, b) => a.order - b.order));
const dragSourceId = ref<number | null>(null);

const pageProps = usePage();
const errors = computed<Record<string, string | string[]>>(
    () => (pageProps.props.errors as Record<string, string | string[]>) ?? {},
);
const errorFor = (key: string): string => {
    const value = errors.value?.[key];
    if (!value) return '';
    return Array.isArray(value) ? value.join(' ') : value;
};

const addFiles = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const files = target.files;
    if (!files) return;

    const startOrder = form.pages.length;
    Array.from(files).forEach((file, idx) => {
        const preview = URL.createObjectURL(file);
        form.pages.push({
            id: Date.now() + idx,
            name: file.name,
            size: `${Math.round(file.size / 1024)} KB`,
            preview,
            file,
            order: startOrder + idx,
        });
    });
    target.value = '';
};

const removePage = (id: number) => {
    form.pages = form.pages.filter((p) => p.id !== id);
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
    form.pages = [...pages];
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
    form.pages = [...pages];
    dragSourceId.value = null;
};

const submit = () => {
    const formData = new FormData();
    formData.append('title', form.title);
    formData.append('number', form.number);
    formData.append('status', form.status);
    if (form.status === 'scheduled' && form.scheduled_for) {
        formData.append('scheduled_for', form.scheduled_for);
    }

    sortedPages.value.forEach((page, idx) => {
        if (page.file) {
            formData.append('pages[]', page.file);
            formData.append('orders[]', String(page.order ?? idx));
        }
    });

    processing.value = true;
    router.post(`/creator/series/${props.seriesId}/chapters`, formData, {
        forceFormData: true,
        preserveScroll: true,
        onFinish: () => {
            processing.value = false;
        },
    });
};

watch(
    () => form.status,
    (val) => {
        if (val !== 'scheduled') {
            form.scheduled_for = '';
        }
    },
);

onUnmounted(() => {
    form.pages.forEach((p) => {
        if (p.preview?.startsWith('blob:')) URL.revokeObjectURL(p.preview);
    });
});
</script>

<template>
    <CreatorLayout
        title="Créer un chapitre"
        :description="`Associer à la série #${props.seriesId}, uploader les pages et planifier la sortie.`"
        :breadcrumbs="[
            { title: 'Séries', href: '/creator/series' },
            { title: props.seriesId, href: `/creator/series/${props.seriesId}` },
            { title: 'Chapitres', href: `/creator/series/${props.seriesId}/chapters` },
            { title: 'Créer' },
        ]"
    >
        <form class="space-y-4" @submit.prevent="submit">
            <Link
                class="inline-flex items-center gap-2 rounded-md border px-3 py-2 text-sm hover:border-primary hover:text-primary"
                :href="`/creator/series/${props.seriesId}/chapters`"
            >
                <ArrowLeft class="h-4 w-4" />
                Retour à la liste des chapitres
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
                                    placeholder="Titre du chapitre"
                                />
                                <p v-if="errorFor('title')" class="text-xs text-destructive">{{ errorFor('title') }}</p>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium">Numéro</label>
                                <input
                                    v-model="form.number"
                                    type="number"
                                    min="1"
                                    class="w-full rounded-md border bg-background px-3 py-2 text-sm outline-none ring-2 ring-transparent transition focus:border-primary focus:ring-primary/20"
                                    placeholder="42"
                                />
                                <p v-if="errorFor('number')" class="text-xs text-destructive">{{ errorFor('number') }}</p>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium">Statut</label>
                                <select
                                    v-model="form.status"
                                    class="w-full rounded-md border bg-background px-3 py-2 text-sm shadow-sm transition focus:border-primary focus:outline-none focus:ring focus:ring-primary/20"
                                >
                                    <option value="draft">Brouillon</option>
                                    <option value="scheduled">Programmé</option>
                                    <option value="published">Publié</option>
                                </select>
                                <p v-if="errorFor('status')" class="text-xs text-destructive">{{ errorFor('status') }}</p>
                            </div>
                            <div class="space-y-2 md:col-span-2">
                                <label class="text-sm font-medium">Date/heure de publication (si programmé)</label>
                                <div class="relative">
                                    <CalendarClock class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                                    <input
                                        v-model="form.scheduled_for"
                                        type="datetime-local"
                                        :disabled="form.status !== 'scheduled'"
                                        class="w-full rounded-md border bg-background py-2 pl-9 pr-3 text-sm outline-none ring-2 ring-transparent transition focus:border-primary focus:ring-primary/20 disabled:cursor-not-allowed disabled:opacity-60"
                                    />
                                </div>
                                <p v-if="errorFor('scheduled_for')" class="text-xs text-destructive">
                                    {{ errorFor('scheduled_for') }}
                                </p>
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

                        <div v-if="!sortedPages.length" class="rounded-lg border border-dashed bg-muted/40 p-4 text-sm text-muted-foreground">
                            Aucune page pour l'instant. Ajoute des images pour voir un aperçu et réordonner.
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
                        <p v-if="errorFor('pages') || errorFor('pages.0')" class="text-xs text-destructive">
                            {{ errorFor('pages') || errorFor('pages.0') }}
                        </p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="rounded-xl border bg-card p-6 shadow-sm space-y-3">
                        <h2 class="text-lg font-semibold">Actions</h2>
                        <div class="flex flex-col gap-3">
                            <button
                                type="submit"
                                class="inline-flex items-center justify-center gap-2 rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground shadow hover:opacity-90 disabled:cursor-not-allowed disabled:opacity-70"
                                :disabled="processing"
                            >
                                <Loader2 v-if="processing" class="h-4 w-4 animate-spin" />
                                <span>{{ processing ? 'Envoi...' : 'Enregistrer' }}</span>
                            </button>
                            <Link
                                :href="`/creator/series/${props.seriesId}/chapters`"
                                class="inline-flex items-center justify-center gap-2 rounded-md border px-4 py-2 text-sm font-medium transition hover:border-primary"
                            >
                                Annuler
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </CreatorLayout>
</template>
