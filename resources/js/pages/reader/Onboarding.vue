<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Head, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

interface ReaderProfile {
    first_name?: string | null;
    last_name?: string | null;
    avatar_url?: string | null;
    birthdate?: string | null;
    preferred_genres?: string[] | null;
    preferred_formats?: string[] | null;
    preferred_themes?: string[] | null;
    language_preferences?: string | null;
    is_completed?: boolean;
}

const props = defineProps<{
    profile: ReaderProfile;
}>();

const form = useForm({
    first_name: props.profile.first_name ?? '',
    last_name: props.profile.last_name ?? '',
    avatar_url: props.profile.avatar_url ?? '',
    birthdate: props.profile.birthdate ?? '',
    preferred_genres: props.profile.preferred_genres ?? [],
    preferred_formats: props.profile.preferred_formats ?? [],
    preferred_themes: props.profile.preferred_themes ?? [],
    language_preferences: props.profile.language_preferences ?? '',
});

const isCompleted = computed(() => props.profile?.is_completed === true);
</script>

<template>
    <Head title="Onboarding Reader" />

    <div
        class="relative min-h-screen overflow-hidden bg-gradient-to-b from-[#050810] via-[#0a1124] to-[#02040c] text-white"
    >
        <div class="pointer-events-none absolute inset-0">
            <div class="absolute -left-16 top-12 h-64 w-64 rounded-full bg-gradient-to-br from-cyan-400/25 to-transparent blur-3xl" />
            <div class="absolute right-0 top-0 h-72 w-72 rounded-full bg-gradient-to-bl from-indigo-400/25 to-transparent blur-3xl" />
            <div class="absolute bottom-0 left-1/3 h-72 w-72 rounded-full bg-gradient-to-br from-pink-500/20 to-transparent blur-3xl" />
        </div>

        <div class="relative z-10 mx-auto flex max-w-6xl flex-col gap-10 px-6 py-12 lg:flex-row lg:items-start">
            <div class="space-y-5 lg:max-w-md">
                <p class="text-xs uppercase tracking-[0.32em] text-white/60">Choisis ta voie</p>
                <h1 class="text-4xl font-semibold leading-tight md:text-5xl">
                    Onboarding reader<br />
                    <span class="text-cyan-200">Deviens explorateur</span>
                </h1>
                <p class="text-base text-white/70">
                    On calibre ton experience pour te recommander les meilleures oeuvres. Valide ton âge,
                    choisis tes vibes et plonge dans Omega.
                </p>

                <div class="grid gap-3">
                    <div class="flex items-center gap-3 rounded-xl border border-white/10 bg-white/5 p-3 backdrop-blur">
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-cyan-400/20 text-cyan-100"
                            >01</span
                        >
                        <div class="text-sm">
                            <div class="font-semibold text-white/90">Age gate</div>
                            <div class="text-white/60">On vérifie que tu peux entrer.</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 rounded-xl border border-white/10 bg-white/5 p-3 backdrop-blur">
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-indigo-400/25 text-indigo-100"
                            >02</span
                        >
                        <div class="text-sm">
                            <div class="font-semibold text-white/90">Tes univers</div>
                            <div class="text-white/60">Genres, thèmes et formats préférés.</div>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="relative w-full max-w-3xl rounded-2xl border border-white/10 bg-white/5 p-1 shadow-[0_24px_70px_rgba(0,0,0,0.45)]"
            >
                <div
                    class="absolute inset-0 rounded-2xl bg-gradient-to-br from-cyan-400/12 via-transparent to-indigo-400/14 opacity-70"
                />
                <div class="relative rounded-[14px] bg-[#0b1226]/80 p-6 backdrop-blur">
                    <div class="mb-6 flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-[0.26em] text-white/60">Loadout</p>
                            <h2 class="text-xl font-semibold">Profil reader</h2>
                        </div>
                        <div
                            class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs uppercase tracking-[0.2em] text-white/70"
                            :class="isCompleted ? 'text-emerald-200' : 'text-white/70'"
                        >
                            Statut :
                            <span :class="isCompleted ? 'text-emerald-300' : 'text-amber-200'">
                                {{ isCompleted ? 'déjà complété' : 'en cours' }}
                            </span>
                        </div>
                    </div>

                    <div
                        v-if="isCompleted"
                        class="mb-6 rounded-xl border border-white/10 bg-white/5 p-4 text-sm text-white/80"
                    >
                        <p class="font-semibold text-white">Ton onboarding est déjà verrouillé.</p>
                        <p class="text-white/70">
                            Pour modifier tes préférences, rends-toi dans les paramètres profil reader.
                        </p>
                        <div class="mt-3 flex gap-3">
                            <Button asChild class="bg-cyan-400 text-black hover:bg-cyan-300">
                                <a href="/reader">Aller au dashboard</a>
                            </Button>
                            <Button asChild variant="outline" class="border-white/20 text-white hover:bg-white/10">
                                <a href="/reader/settings/profile">Paramètres profil</a>
                            </Button>
                        </div>
                    </div>

                    <form
                        v-else
                        class="grid gap-5"
                        @submit.prevent="form.post('/onboarding/reader')"
                    >
                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="grid gap-2">
                                <Label for="first_name">Prénom</Label>
                                <Input
                                    id="first_name"
                                    v-model="form.first_name"
                                    name="first_name"
                                    type="text"
                                    autocomplete="given-name"
                                    placeholder="Prénom"
                                />
                                <InputError :message="form.errors.first_name" />
                            </div>
                            <div class="grid gap-2">
                                <Label for="last_name">Nom</Label>
                                <Input
                                    id="last_name"
                                    v-model="form.last_name"
                                    name="last_name"
                                    type="text"
                                    autocomplete="family-name"
                                    placeholder="Nom"
                                />
                                <InputError :message="form.errors.last_name" />
                            </div>
                        </div>

                        <div class="grid gap-2">
                            <Label for="birthdate">Date de naissance</Label>
                            <Input
                                id="birthdate"
                                v-model="form.birthdate"
                                name="birthdate"
                                type="date"
                                required
                                class="bg-white/5"
                            />
                            <InputError :message="form.errors.birthdate" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="preferred_genres">Genres préférés (séparés par des virgules)</Label>
                            <Input
                                id="preferred_genres"
                                :value="Array.isArray(form.preferred_genres) ? form.preferred_genres.join(', ') : ''"
                                name="preferred_genres"
                                type="text"
                                placeholder="Shonen, Seinen, Romance..."
                                @input="form.preferred_genres = $event.target.value.split(',').map(g => g.trim()).filter(Boolean)"
                            />
                            <InputError :message="form.errors.preferred_genres" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="preferred_themes">Thèmes favoris (séparés par des virgules)</Label>
                            <Input
                                id="preferred_themes"
                                :value="Array.isArray(form.preferred_themes) ? form.preferred_themes.join(', ') : ''"
                                name="preferred_themes"
                                type="text"
                                placeholder="Cyberpunk, Fantastique, Slice of life..."
                                @input="form.preferred_themes = $event.target.value.split(',').map(g => g.trim()).filter(Boolean)"
                            />
                            <InputError :message="form.errors.preferred_themes" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="preferred_formats">Formats préférés (séparés par des virgules)</Label>
                            <Input
                                id="preferred_formats"
                                :value="Array.isArray(form.preferred_formats) ? form.preferred_formats.join(', ') : ''"
                                name="preferred_formats"
                                type="text"
                                placeholder="Webtoon, Manga relié, Audio..."
                                @input="form.preferred_formats = $event.target.value.split(',').map(g => g.trim()).filter(Boolean)"
                            />
                            <InputError :message="form.errors.preferred_formats" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="language_preferences">Langue de lecture</Label>
                            <Input
                                id="language_preferences"
                                v-model="form.language_preferences"
                                name="language_preferences"
                                type="text"
                                placeholder="Français, Anglais..."
                            />
                            <InputError :message="form.errors.language_preferences" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="avatar_url">Avatar (URL)</Label>
                            <Input
                                id="avatar_url"
                                v-model="form.avatar_url"
                                name="avatar_url"
                                type="url"
                                placeholder="https://..."
                            />
                            <InputError :message="form.errors.avatar_url" />
                        </div>

                        <div class="flex items-center justify-end gap-3">
                            <div class="text-xs uppercase tracking-[0.18em] text-white/60">
                                Etape 1/1 : calibrage
                            </div>
                            <Button
                                type="submit"
                                :disabled="form.processing"
                                class="bg-cyan-400 text-black shadow-lg transition duration-200 hover:-translate-y-0.5 hover:bg-cyan-300"
                            >
                                Lancer Omega
                            </Button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
