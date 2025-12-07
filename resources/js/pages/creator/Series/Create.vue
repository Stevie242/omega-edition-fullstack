<script setup lang="ts">
import CreatorLayout from '@/layouts/CreatorLayout.vue';
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { ArrowLeft, CalendarDays, Info, PlusCircle, Tags, Upload } from 'lucide-vue-next';

type ChapterPlan = {
    id: number;
    title: string;
    releaseDate: string;
};

const form = ref({
    title: '',
    type: 'manga',
    status: 'ongoing',
    format: 'series',
    language: 'fr',
    synopsis: '',
    tags: ['Action', 'Aventure'],
    cover: null as File | null,
    hero: null as File | null,
    frequency: 'weekly',
    schedule: [] as ChapterPlan[],
});

const coverPreview = ref<string | null>(null);
const heroPreview = ref<string | null>(null);

const addChapterPlan = () => {
    const nextId = form.value.schedule.length + 1;
    form.value.schedule.push({
        id: nextId,
        title: `Chapitre ${nextId}`,
        releaseDate: '',
    });
};

const removeChapterPlan = (id: number) => {
    form.value.schedule = form.value.schedule.filter((c) => c.id !== id);
};

const handleFile = (event: Event, key: 'cover' | 'hero') => {
    const target = event.target as HTMLInputElement;
    const [file] = target.files || [];
    form.value[key] = file ?? null;

    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            const url = (e.target?.result as string) ?? null;
            if (key === 'cover') coverPreview.value = url;
            if (key === 'hero') heroPreview.value = url;
        };
        reader.readAsDataURL(file);
    } else {
        if (key === 'cover') coverPreview.value = null;
        if (key === 'hero') heroPreview.value = null;
    }
};

const presetTags = ['Action', 'Aventure', 'Romance', 'Sci-fi', 'Drame', 'Fantastique', 'Thriller'];
const toggleTag = (tag: string) => {
    const exists = form.value.tags.includes(tag);
    form.value.tags = exists
        ? form.value.tags.filter((t) => t !== tag)
        : [...form.value.tags, tag];
};
</script>

<template>
    <CreatorLayout
        title="Créer une série"
        description="Renseigne les métadonnées, visuels et planning de sortie des chapitres."
        :breadcrumbs="[
            { title: 'Séries', href: '/creator/series' },
            { title: 'Créer' },
        ]"
    >
        <div class="space-y-6">
            <div class="flex items-center gap-3 text-sm text-muted-foreground">
                <Link
                    href="/creator/series"
                    class="inline-flex items-center gap-2 rounded-md border px-3 py-2 hover:border-primary hover:text-primary"
                >
                    <ArrowLeft class="h-4 w-4" />
                    Retour aux séries
                </Link>
                <div class="flex items-center gap-2 rounded-md border border-dashed px-3 py-2">
                    <Info class="h-4 w-4 text-primary" />
                    Les champs visuels sont optionnels en mock; en prod, prévoir upload S3/local.
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-[2fr_1fr]">
                <div class="space-y-6">
                    <div class="rounded-xl border bg-card p-6 shadow-sm">
                        <div class="mb-4 flex items-center justify-between">
                            <h2 class="text-lg font-semibold">Métadonnées</h2>
                        </div>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="md:col-span-2 space-y-2">
                                <label class="text-sm font-medium">Titre</label>
                                <input
                                    v-model="form.title"
                                    type="text"
                                    placeholder="Nom de la série"
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
                                    placeholder="Résumé de la série..."
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
                                Le créateur planifie manuellement les dates (simulation).
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
                                Fréquence indiquée par le créateur (mock). Sert à estimer l’attente entre deux chapitres.
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
                            <span class="text-xs text-muted-foreground">Mock seulement</span>
                        </div>
                        <div class="flex flex-col gap-3">
                            <button
                                type="button"
                                class="inline-flex items-center justify-center gap-2 rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground shadow hover:opacity-90"
                            >
                                Enregistrer le brouillon
                            </button>
                            <button
                                type="button"
                                class="inline-flex items-center justify-center gap-2 rounded-md border px-4 py-2 text-sm font-medium transition hover:border-primary"
                            >
                                Publier
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </CreatorLayout>
</template>
