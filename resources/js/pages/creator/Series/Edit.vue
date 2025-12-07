<script setup lang="ts">
import CreatorLayout from '@/layouts/CreatorLayout.vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import { ArrowLeft, ImagePlus, Loader2, Upload } from 'lucide-vue-next';
import MultiSelect from 'primevue/multiselect';
import { computed, onMounted, onUnmounted, reactive, ref, watch } from 'vue';
import { useToast } from 'primevue/usetoast';

type TagOption = { id: string; label: string };

type SeriesProps = {
    id: string;
    title: string;
    type: 'manga' | 'webtoon';
    status: 'ongoing' | 'hiatus' | 'completed';
    frequency: 'weekly' | 'biweekly' | 'monthly' | 'irregular';
    format: 'oneshot' | 'series' | 'miniseries';
    synopsis?: string | null;
    cover?: string | null;
    hero?: string | null;
    tagIds?: string[];
};

const props = defineProps<{
    seriesId: string;
    series: SeriesProps;
    tags: TagOption[];
}>();

type SeriesForm = {
    title: string;
    type: SeriesProps['type'];
    status: SeriesProps['status'];
    frequency: SeriesProps['frequency'];
    is_one_shot: boolean;
    synopsis: string;
    tags: string[];
    cover: File | null;
    hero: File | null;
};

const form = reactive<SeriesForm>({
    title: props.series.title,
    type: props.series.type,
    status: props.series.status,
    frequency: props.series.frequency,
    is_one_shot: props.series.format === 'oneshot',
    synopsis: props.series.synopsis ?? '',
    tags: props.series.tagIds ?? [],
    cover: null,
    hero: null,
});

const coverPreview = ref<string | null>(props.series.cover ?? null);
const heroPreview = ref<string | null>(props.series.hero ?? null);
const processing = ref(false);

const page = usePage();
const toast = useToast();
const flashSuccess = computed(() => (page.props.flash as { success?: string })?.success);
const errors = computed<Record<string, string | string[]>>(
    () => (page.props.errors as Record<string, string | string[]>) ?? {},
);

const errorFor = (key: string): string => {
    const value = errors.value?.[key];
    if (!value) return '';
    return Array.isArray(value) ? value.join(' ') : value;
};

const revokePreview = (preview: typeof coverPreview) => {
    if (preview.value && preview.value.startsWith('blob:')) {
        URL.revokeObjectURL(preview.value);
    }
    preview.value = preview.value ?? null;
};

const handleFileChange = (event: Event, key: 'cover' | 'hero') => {
    const input = event.target as HTMLInputElement;
    const file = input.files?.[0] ?? null;
    form[key] = file;

    const targetPreview = key === 'cover' ? coverPreview : heroPreview;
    revokePreview(targetPreview);
    if (file) {
        targetPreview.value = URL.createObjectURL(file);
    } else {
        targetPreview.value = key === 'cover' ? props.series.cover ?? null : props.series.hero ?? null;
    }
};

const submit = () => {
    const formData = new FormData();
    formData.append('title', form.title);
    formData.append('type', form.type);
    formData.append('status', form.status);
    formData.append('frequency', form.frequency);
    formData.append('is_one_shot', form.is_one_shot ? '1' : '0');
    formData.append('_method', 'put');
    if (form.synopsis) formData.append('synopsis', form.synopsis);
    form.tags.forEach((tagId) => formData.append('tags[]', tagId));
    if (form.cover) formData.append('cover', form.cover);
    if (form.hero) formData.append('hero', form.hero);

    processing.value = true;
    router.post(`/creator/series/${props.seriesId}`, formData, {
        forceFormData: true,
        preserveScroll: true,
        onFinish: () => {
            processing.value = false;
        },
    });
};

onMounted(() => {
    if (flashSuccess.value) {
        toast.add({ severity: 'success', summary: flashSuccess.value, life: 2500 });
    }
});

watch(
    () => flashSuccess.value,
    (val) => {
        if (val) toast.add({ severity: 'success', summary: val, life: 2500 });
    },
);

onUnmounted(() => {
    revokePreview(coverPreview);
    revokePreview(heroPreview);
});
</script>

<template>
    <CreatorLayout
        title="Éditer une série"
        :description="`Série #${props.seriesId} — modifier métadonnées et visuels.`"
        :breadcrumbs="[
            { title: 'Séries', href: '/creator/series' },
            { title: series.title },
        ]"
    >
        <form class="space-y-6" @submit.prevent="submit">
            <div class="flex flex-wrap items-center gap-3 text-sm text-muted-foreground">
                <Link
                    href="/creator/series"
                    class="inline-flex items-center gap-2 rounded-md border px-3 py-2 hover:border-primary hover:text-primary"
                >
                    <ArrowLeft class="h-4 w-4" />
                    Retour à la liste
                </Link>
                <Link
                    :href="`/creator/series/${props.seriesId}`"
                    class="inline-flex items-center gap-2 rounded-md border px-3 py-2 hover:border-primary hover:text-primary"
                >
                    <ArrowLeft class="h-4 w-4 rotate-180" />
                    Voir la fiche série
                </Link>
            </div>

            <div class="grid gap-6 lg:grid-cols-[2fr_1fr]">
                <div class="space-y-6">
                    <div class="rounded-xl border bg-card p-6 shadow-sm space-y-4">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold">Métadonnées</h2>
                            <label class="inline-flex items-center gap-2 text-xs font-medium text-muted-foreground">
                                <input
                                    v-model="form.is_one_shot"
                                    type="checkbox"
                                    class="h-4 w-4 rounded border-muted-foreground/40 text-primary focus:ring-primary/30"
                                />
                                One-shot
                            </label>
                        </div>
                        <div class="space-y-4">
                            <div class="space-y-2">
                                <label class="text-sm font-medium">Titre</label>
                                <input
                                    v-model="form.title"
                                    type="text"
                                    class="w-full rounded-md border bg-background px-3 py-2 text-sm outline-none ring-2 ring-transparent transition focus:border-primary focus:ring-primary/20"
                                />
                                <p v-if="errorFor('title')" class="text-xs text-destructive">{{ errorFor('title') }}</p>
                            </div>
                            <div class="grid gap-4 md:grid-cols-2">
                                <div class="space-y-2">
                                    <label class="text-sm font-medium">Type</label>
                                    <select
                                        v-model="form.type"
                                        class="w-full rounded-md border bg-background px-3 py-2 text-sm shadow-sm transition focus:border-primary focus:outline-none focus:ring focus:ring-primary/20"
                                    >
                                        <option value="manga">Manga</option>
                                        <option value="webtoon">Webtoon</option>
                                    </select>
                                    <p v-if="errorFor('type')" class="text-xs text-destructive">{{ errorFor('type') }}</p>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-medium">Statut</label>
                                    <select
                                        v-model="form.status"
                                        class="w-full rounded-md border bg-background px-3 py-2 text-sm shadow-sm transition focus:border-primary focus:outline-none focus:ring focus:ring-primary/20"
                                    >
                                        <option value="ongoing">En cours</option>
                                        <option value="hiatus">Pause</option>
                                        <option value="completed">Terminée</option>
                                    </select>
                                    <p v-if="errorFor('status')" class="text-xs text-destructive">
                                        {{ errorFor('status') }}
                                    </p>
                                </div>
                                <div class="space-y-2 md:col-span-2">
                                    <label class="text-sm font-medium">Fréquence</label>
                                    <select
                                        v-model="form.frequency"
                                        class="w-full rounded-md border bg-background px-3 py-2 text-sm shadow-sm transition focus:border-primary focus:outline-none focus:ring focus:ring-primary/20"
                                    >
                                        <option value="weekly">Hebdomadaire</option>
                                        <option value="biweekly">Bi-hebdomadaire</option>
                                        <option value="monthly">Mensuel</option>
                                        <option value="irregular">Irrégulier</option>
                                    </select>
                                    <p v-if="errorFor('frequency')" class="text-xs text-destructive">
                                        {{ errorFor('frequency') }}
                                    </p>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium">Synopsis</label>
                                <textarea
                                    v-model="form.synopsis"
                                    rows="4"
                                    class="w-full rounded-md border bg-background px-3 py-2 text-sm outline-none ring-2 ring-transparent transition focus:border-primary focus:ring-primary/20"
                                ></textarea>
                                <p v-if="errorFor('synopsis')" class="text-xs text-destructive">
                                    {{ errorFor('synopsis') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-xl border bg-card p-6 shadow-sm space-y-3">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold">Tags & genres</h2>
                            <span class="text-xs text-muted-foreground">Multi-sélection</span>
                        </div>
                        <MultiSelect
                            v-model="form.tags"
                            :options="props.tags"
                            option-label="label"
                            option-value="id"
                            display="chip"
                            placeholder="Choisir les tags"
                            class="w-full"
                        />
                        <p v-if="errorFor('tags') || errorFor('tags.0')" class="text-xs text-destructive">
                            {{ errorFor('tags') || errorFor('tags.0') }}
                        </p>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="rounded-xl border bg-card p-6 shadow-sm space-y-4">
                        <h2 class="text-lg font-semibold">Visuels</h2>
                        <div class="space-y-4">
                            <div class="space-y-2">
                                <label class="text-sm font-medium">Couverture</label>
                                <div class="rounded-lg border border-dashed bg-muted/30 p-3">
                                    <div
                                        v-if="coverPreview"
                                        class="mb-3 h-40 w-full overflow-hidden rounded-md border bg-cover bg-center"
                                        :style="{ backgroundImage: `url(${coverPreview})` }"
                                    ></div>
                                    <div
                                        v-else
                                        class="mb-3 flex h-40 items-center justify-center rounded-md border border-dashed bg-background text-muted-foreground"
                                    >
                                        <ImagePlus class="h-5 w-5" />
                                    </div>
                                    <label class="inline-flex cursor-pointer items-center gap-2 text-sm font-medium text-primary">
                                        <Upload class="h-4 w-4" />
                                        <span>Changer l'image</span>
                                        <input
                                            type="file"
                                            class="hidden"
                                            accept="image/*"
                                            @change="(e) => handleFileChange(e, 'cover')"
                                        />
                                    </label>
                                    <p v-if="errorFor('cover')" class="mt-1 text-xs text-destructive">
                                        {{ errorFor('cover') }}
                                    </p>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium">Hero / bannière</label>
                                <div class="rounded-lg border border-dashed bg-muted/30 p-3">
                                    <div
                                        v-if="heroPreview"
                                        class="mb-3 h-24 w-full overflow-hidden rounded-md border bg-cover bg-center"
                                        :style="{ backgroundImage: `url(${heroPreview})` }"
                                    ></div>
                                    <div
                                        v-else
                                        class="mb-3 flex h-24 items-center justify-center rounded-md border border-dashed bg-background text-muted-foreground"
                                    >
                                        <ImagePlus class="h-5 w-5" />
                                    </div>
                                    <label class="inline-flex cursor-pointer items-center gap-2 text-sm font-medium text-primary">
                                        <Upload class="h-4 w-4" />
                                        <span>Changer l'image</span>
                                        <input
                                            type="file"
                                            class="hidden"
                                            accept="image/*"
                                            @change="(e) => handleFileChange(e, 'hero')"
                                        />
                                    </label>
                                    <p v-if="errorFor('hero')" class="mt-1 text-xs text-destructive">
                                        {{ errorFor('hero') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-xl border bg-card p-6 shadow-sm space-y-3">
                        <h2 class="text-lg font-semibold">Actions</h2>
                        <p class="text-sm text-muted-foreground">
                            Mets à jour la série puis vérifie la fiche.
                        </p>
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                            <Link
                                href="/creator/series"
                                class="inline-flex items-center justify-center gap-2 rounded-md border px-4 py-2 text-sm font-medium transition hover:border-primary"
                            >
                                Annuler
                            </Link>
                            <button
                                type="submit"
                                class="inline-flex items-center justify-center gap-2 rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground shadow hover:opacity-90 disabled:cursor-not-allowed disabled:opacity-70"
                                :disabled="processing"
                            >
                                <Loader2 v-if="processing" class="h-4 w-4 animate-spin" />
                                <span>{{ processing ? 'Envoi...' : 'Mettre à jour' }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </CreatorLayout>
</template>
