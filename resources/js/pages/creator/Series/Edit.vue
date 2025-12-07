<script setup lang="ts">
import CreatorLayout from '@/layouts/CreatorLayout.vue';
import { Link } from '@inertiajs/vue3';
import { ArrowLeft, CalendarDays, Info, PlusCircle, Tags, Upload } from 'lucide-vue-next';
import { onMounted, ref } from 'vue';

const props = defineProps<{
    seriesId: string;
}>();

type ChapterPlan = {
    id: number;
    title: string;
    releaseDate: string;
};

const initial = {
    title: 'Omega Rebirth',
    type: 'manga',
    status: 'ongoing',
    format: 'series',
    language: 'fr',
    synopsis:
        'Un artefact ancien propulse un jeune guerrier dans une guerre entre royaumes. L’équilibre du monde tient à ses choix.',
    tags: ['Action', 'Dark fantasy'],
    cover: 'https://images.unsplash.com/photo-1504274066651-8d31a536b11a?auto=format&fit=crop&w=600&q=60',
    hero: 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=1200&q=60',
    frequency: 'weekly',
    schedule: [
        { id: 1, title: 'Chapitre 42', releaseDate: '2025-12-15' },
        { id: 2, title: 'Chapitre 43', releaseDate: '2025-12-22' },
    ] as ChapterPlan[],
};

const form = ref({ ...initial });
const coverPreview = ref<string | null>(initial.cover);
const heroPreview = ref<string | null>(initial.hero);
const loading = ref(true);

const presetTags = ['Action', 'Aventure', 'Romance', 'Sci-fi', 'Drame', 'Fantastique', 'Thriller', 'Dark fantasy'];

const toggleTag = (tag: string) => {
    const exists = form.value.tags.includes(tag);
    form.value.tags = exists
        ? form.value.tags.filter((t: string) => t !== tag)
        : [...form.value.tags, tag];
};

const handleFile = (event: Event, key: 'cover' | 'hero') => {
    const target = event.target as HTMLInputElement;
    const [file] = target.files || [];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = (e) => {
        const url = (e.target?.result as string) ?? null;
        if (key === 'cover') coverPreview.value = url;
        if (key === 'hero') heroPreview.value = url;
    };
    reader.readAsDataURL(file);
};

const addChapterPlan = () => {
    const nextId = form.value.schedule.length + 1;
    form.value.schedule.push({
        id: nextId,
        title: `Chapitre ${nextId}`,
        releaseDate: '',
    });
};

const removeChapterPlan = (id: number) => {
    form.value.schedule = form.value.schedule.filter((c: ChapterPlan) => c.id !== id);
};

onMounted(() => {
    setTimeout(() => {
        loading.value = false;
    }, 500);
});
</script>

<template>
    <CreatorLayout
        title="Éditer une série"
        :description="`Série #${props.seriesId} — modifier métadonnées, visuels et planning.`"
        :breadcrumbs="[
            { title: 'Séries', href: '/creator/series' },
            { title: props.seriesId },
        ]"
    >
        <div class="space-y-6">
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
                <div class="flex items-center gap-2 rounded-md border border-dashed px-3 py-2">
                    <Info class="h-4 w-4 text-primary" />
                    Mock : aucune persistance réelle.
                </div>
            </div>

            <div v-if="loading" class="grid gap-6 lg:grid-cols-[2fr_1fr]">
                <div class="space-y-4">
                    <div class="h-10 w-48 animate-pulse rounded bg-muted" />
                    <div class="space-y-4 rounded-xl border bg-card p-6 shadow-sm">
                        <div class="h-5 w-32 animate-pulse rounded bg-muted" />
                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="h-10 w-full animate-pulse rounded bg-muted md:col-span-2" />
                            <div class="h-10 w-full animate-pulse rounded bg-muted" />
                            <div class="h-10 w-full animate-pulse rounded bg-muted" />
                            <div class="h-10 w-full animate-pulse rounded bg-muted" />
                            <div class="h-10 w-full animate-pulse rounded bg-muted" />
                            <div class="h-20 w-full animate-pulse rounded bg-muted md:col-span-2" />
                        </div>
                    </div>
                    <div class="rounded-xl border bg-card p-6 shadow-sm">
                        <div class="h-5 w-32 animate-pulse rounded bg-muted" />
                        <div class="mt-4 flex flex-wrap gap-2">
                            <div class="h-7 w-16 animate-pulse rounded-full bg-muted" v-for="n in 6" :key="n" />
                        </div>
                    </div>
                    <div class="rounded-xl border bg-card p-6 shadow-sm space-y-3">
                        <div class="h-5 w-36 animate-pulse rounded bg-muted" />
                        <div class="space-y-2">
                            <div class="h-10 w-full animate-pulse rounded bg-muted" v-for="n in 2" :key="n" />
                        </div>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="h-10 w-36 animate-pulse rounded bg-muted" />
                    <div class="rounded-xl border bg-card p-6 shadow-sm space-y-3">
                        <div class="h-5 w-32 animate-pulse rounded bg-muted" />
                        <div class="h-40 w-full animate-pulse rounded bg-muted" />
                        <div class="h-24 w-full animate-pulse rounded bg-muted" />
                    </div>
                    <div class="rounded-xl border bg-card p-6 shadow-sm space-y-3">
                        <div class="h-5 w-32 animate-pulse rounded bg-muted" />
                        <div class="h-10 w-full animate-pulse rounded bg-muted" />
                    </div>
                    <div class="rounded-xl border bg-card p-6 shadow-sm space-y-2">
                        <div class="h-5 w-24 animate-pulse rounded bg-muted" />
                        <div class="h-10 w-full animate-pulse rounded bg-muted" />
                        <div class="h-10 w-full animate-pulse rounded bg-muted" />
                    </div>
                </div>
            </div>

            <div v-else class="grid gap-6 lg:grid-cols-[2fr_1fr]">
                <div class="space-y-6">
                    <div class="rounded-xl border bg-card p-6 shadow-sm">
                        <div class="mb-4 flex items-center justify-between">
                            <h2 class="text-lg font-semibold">Métadonnées</h2>
                            <span class="text-xs text-muted-foreground">
                                Statut actuel : {{ form.status === 'ongoing' ? 'En cours' : form.status === 'hiatus' ? 'Pause' : 'Terminé' }}
                            </span>
                        </div>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="md:col-span-2 space-y-2">
                                <label class="text-sm font-medium">Titre</label>
                                <input
                                    v-model="form.title"
                                    type="text"
                                    class="w-full rounded-md border bg-background px-3 py-2 text-sm outline-none ring-2 ring-transparent transition focus:border-primary focus:ring-primary/20"
                                />
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium">Type</label>
                                <select
                                    v-model="form.type"
                                    class="w-full rounded-md border bg-background px-3 py-2 text-sm shadow-sm transition focus:border-primary focus:outline-none focus:ring focus:ring-primary/20"
                                >
                                    <option value="manga">Manga</option>
                                    <option value="webtoon">Webtoon</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium">Statut</label>
                                <select
                                    v-model="form.status"
                                    class="w-full rounded-md border bg-background px-3 py-2 text-sm shadow-sm transition focus:border-primary focus:outline-none focus:ring focus:ring-primary/20"
                                >
                                    <option value="ongoing">En cours</option>
                                    <option value="hiatus">Pause</option>
                                    <option value="completed">Terminé</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium">Format</label>
                                <select
                                    v-model="form.format"
                                    class="w-full rounded-md border bg-background px-3 py-2 text-sm shadow-sm transition focus:border-primary focus:outline-none focus:ring focus:ring-primary/20"
                                >
                                    <option value="oneshot">One-shot</option>
                                    <option value="series">Série longue</option>
                                    <option value="miniseries">Mini-série</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium">Langue</label>
                                <select
                                    v-model="form.language"
                                    class="w-full rounded-md border bg-background px-3 py-2 text-sm shadow-sm transition focus:border-primary focus:outline-none focus:ring focus:ring-primary/20"
                                >
                                    <option value="fr">Français</option>
                                    <option value="en">Anglais</option>
                                    <option value="es">Espagnol</option>
                                </select>
                            </div>
                            <div class="md:col-span-2 space-y-2">
                                <label class="text-sm font-medium">Synopsis</label>
                                <textarea
                                    v-model="form.synopsis"
                                    rows="4"
                                    class="w-full rounded-md border bg-background px-3 py-2 text-sm outline-none ring-2 ring-transparent transition focus:border-primary focus:ring-primary/20"
                                ></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-xl border bg-card p-6 shadow-sm">
                        <div class="mb-4 flex items-center justify-between">
                            <h2 class="text-lg font-semibold">Tags & genres</h2>
                            <div class="flex items-center gap-2 text-xs text-muted-foreground">
                                <Tags class="h-4 w-4" />
                                Sélectionne plusieurs tags
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <button
                                v-for="tag in presetTags"
                                :key="tag"
                                type="button"
                                class="rounded-full border px-3 py-1 text-xs transition hover:border-primary"
                                :class="form.tags.includes(tag) ? 'bg-primary text-primary-foreground' : 'bg-muted/50'"
                                @click="toggleTag(tag)"
                            >
                                {{ tag }}
                            </button>
                        </div>
                    </div>

                    <div class="rounded-xl border bg-card p-6 shadow-sm">
                        <div class="mb-4 flex items-center justify-between">
                            <h2 class="text-lg font-semibold">Planning des chapitres</h2>
                            <div class="text-xs text-muted-foreground">
                                Mock : dates saisies par le créateur.
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div
                                v-for="chapter in form.schedule"
                                :key="chapter.id"
                                class="grid gap-3 rounded-lg border bg-muted/30 p-3 md:grid-cols-[1.5fr_1fr_auto]"
                            >
                                <input
                                    v-model="chapter.title"
                                    type="text"
                                    class="w-full rounded-md border bg-background px-3 py-2 text-sm outline-none ring-2 ring-transparent transition focus:border-primary focus:ring-primary/20"
                                    placeholder="Titre du chapitre"
                                />
                                <div class="relative">
                                    <CalendarDays class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                                    <input
                                        v-model="chapter.releaseDate"
                                        type="date"
                                        class="w-full rounded-md border bg-background py-2 pl-9 pr-3 text-sm outline-none ring-2 ring-transparent transition focus:border-primary focus:ring-primary/20"
                                    />
                                </div>
                                <button
                                    type="button"
                                    class="justify-self-end rounded-md border px-3 py-2 text-xs font-semibold text-destructive transition hover:border-destructive/60 hover:bg-destructive/10"
                                    @click="removeChapterPlan(chapter.id)"
                                >
                                    Supprimer
                                </button>
                            </div>
                            <button
                                type="button"
                                class="inline-flex items-center gap-2 rounded-md border px-3 py-2 text-sm transition hover:border-primary"
                                @click="addChapterPlan"
                            >
                                <PlusCircle class="h-4 w-4" />
                                Ajouter un chapitre planifié
                            </button>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="rounded-xl border bg-card p-6 shadow-sm">
                        <h2 class="mb-4 text-lg font-semibold">Visuels</h2>
                        <div class="space-y-4">
                            <div class="space-y-2">
                                <label class="text-sm font-medium">Couverture</label>
                                <label class="flex cursor-pointer flex-col items-center justify-center gap-3 rounded-lg border border-dashed bg-muted/30 p-4 text-sm text-muted-foreground hover:border-primary">
                                    <div
                                        v-if="coverPreview"
                                        class="h-40 w-full overflow-hidden rounded-md border bg-cover bg-center"
                                        :style="{ backgroundImage: `url(${coverPreview})` }"
                                    ></div>
                                    <template v-else>
                                        <Upload class="h-5 w-5" />
                                        <span>Sélectionner une image</span>
                                    </template>
                                    <input type="file" class="hidden" accept="image/*" @change="(e) => handleFile(e, 'cover')" />
                                </label>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium">Hero / Bannière</label>
                                <label class="flex cursor-pointer flex-col items-center justify-center gap-3 rounded-lg border border-dashed bg-muted/30 p-4 text-sm text-muted-foreground hover:border-primary">
                                    <div
                                        v-if="heroPreview"
                                        class="h-24 w-full overflow-hidden rounded-md border bg-cover bg-center"
                                        :style="{ backgroundImage: `url(${heroPreview})` }"
                                    ></div>
                                    <template v-else>
                                        <Upload class="h-5 w-5" />
                                        <span>Sélectionner une image</span>
                                    </template>
                                    <input type="file" class="hidden" accept="image/*" @change="(e) => handleFile(e, 'hero')" />
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-xl border bg-card p-6 shadow-sm">
                        <h2 class="mb-4 text-lg font-semibold">Fréquence de sortie</h2>
                        <div class="space-y-2">
                            <label class="text-sm text-muted-foreground">
                                Rythme estimé entre deux chapitres.
                            </label>
                            <select
                                v-model="form.frequency"
                                class="w-full rounded-md border bg-background px-3 py-2 text-sm shadow-sm transition focus:border-primary focus:outline-none focus:ring focus:ring-primary/20"
                            >
                                <option value="weekly">Hebdomadaire</option>
                                <option value="biweekly">Bi-hebdomadaire</option>
                                <option value="monthly">Mensuel</option>
                                <option value="irregular">Irrégulier</option>
                            </select>
                        </div>
                    </div>

                    <div class="rounded-xl border bg-card p-6 shadow-sm space-y-3">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold">Actions</h2>
                            <span class="text-xs text-muted-foreground">Mock</span>
                        </div>
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
                                Dupliquer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </CreatorLayout>
</template>
