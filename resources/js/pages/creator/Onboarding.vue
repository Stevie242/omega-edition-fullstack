<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Head, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

interface ProfilePayload {
    first_name?: string | null;
    last_name?: string | null;
    display_name?: string | null;
    headline?: string | null;
    bio?: string | null;
    signature_style?: string | null;
    favorite_formats?: string | null;
}

const props = defineProps<{
    profile: ProfilePayload & { is_completed?: boolean };
}>();

const form = useForm({
    first_name: props.profile?.first_name ?? '',
    last_name: props.profile?.last_name ?? '',
    display_name: props.profile?.display_name ?? '',
    headline: props.profile?.headline ?? '',
    bio: props.profile?.bio ?? '',
    signature_style: props.profile?.signature_style ?? '',
    favorite_formats: props.profile?.favorite_formats ?? '',
});

const isCompleted = computed(() => props.profile?.is_completed === true);
</script>

<template>
    <Head title="Onboarding Creator" />

    <div
        class="relative min-h-screen overflow-hidden bg-gradient-to-b from-[#040711] via-[#070d1f] to-[#01030a] text-white"
    >
        <div class="pointer-events-none absolute inset-0">
            <div
                class="absolute -left-20 top-10 h-72 w-72 rounded-full bg-gradient-to-br from-emerald-400/25 to-transparent blur-3xl"
            />
            <div
                class="absolute right-10 top-0 h-80 w-80 rounded-full bg-gradient-to-br from-purple-400/20 to-transparent blur-3xl"
            />
            <div
                class="absolute bottom-0 left-1/3 h-64 w-64 rounded-full bg-gradient-to-br from-blue-500/20 via-transparent to-transparent blur-3xl"
            />
        </div>

        <div class="relative z-10 mx-auto flex max-w-6xl flex-col gap-10 px-6 py-12 lg:flex-row lg:items-start">
            <div class="space-y-5 lg:max-w-md">
                <p class="text-xs uppercase tracking-[0.32em] text-white/60">Choisis ta voie</p>
                <h1 class="text-4xl font-semibold leading-tight md:text-5xl">
                    Onboarding creator<br />
                    <span class="text-emerald-300">Forge ton interface</span>
                </h1>
                <p class="text-base text-white/70">
                    On te a deja place sur la trajectoire creator. Verifie ton alias public, affine ton style et
                    pose les bases de ton hub. Tu peux reprendre exactement ou tu t'es arrete.
                </p>

                <div class="grid gap-3">
                    <div class="flex items-center gap-3 rounded-xl border border-white/10 bg-white/5 p-3 backdrop-blur">
                        <span
                            class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-400/20 text-emerald-200"
                            >01</span
                        >
                        <div class="text-sm">
                            <div class="font-semibold text-white/90">Identite</div>
                            <div class="text-white/60">Alias public et tes coordonnees.</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 rounded-xl border border-white/10 bg-white/5 p-3 backdrop-blur">
                        <span
                            class="flex h-9 w-9 items-center justify-center rounded-lg bg-purple-400/25 text-purple-100"
                            >02</span
                        >
                        <div class="text-sm">
                            <div class="font-semibold text-white/90">Vibe</div>
                            <div class="text-white/60">Headline, bio et style signature.</div>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="relative w-full max-w-3xl rounded-2xl border border-white/10 bg-white/5 p-1 shadow-[0_24px_70px_rgba(0,0,0,0.45)]"
            >
                <div
                    class="absolute inset-0 rounded-2xl bg-gradient-to-br from-emerald-400/10 via-transparent to-blue-500/10 opacity-70"
                />
                <div class="relative rounded-[14px] bg-[#0b1226]/80 p-6 backdrop-blur">
                    <div class="mb-6 flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-[0.26em] text-white/60">Loadout</p>
                            <h2 class="text-xl font-semibold">Profil creator</h2>
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
                            Pour modifier ton profil creator, rends-toi dans les paramètres profil.
                        </p>
                        <div class="mt-3 flex gap-3">
                            <Button asChild class="bg-emerald-400 text-black hover:bg-emerald-300">
                                <a href="/creator">Aller au dashboard</a>
                            </Button>
                            <Button asChild variant="outline" class="border-white/20 text-white hover:bg-white/10">
                                <a href="/creator/settings/profile">Paramètres profil</a>
                            </Button>
                        </div>
                    </div>

                    <form
                        v-else
                        class="grid gap-5"
                        @submit.prevent="form.post('/onboarding/creator')"
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
                            <Label for="display_name">Alias public</Label>
                            <Input
                                id="display_name"
                                v-model="form.display_name"
                                name="display_name"
                                type="text"
                                required
                                autocomplete="organization"
                                placeholder="Pseudo / nom de studio visible"
                            />
                            <InputError :message="form.errors.display_name" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="headline">Headline</Label>
                            <Input
                                id="headline"
                                v-model="form.headline"
                                name="headline"
                                type="text"
                                placeholder="Ex: Univers cyberpunk, narration immersive"
                            />
                            <InputError :message="form.errors.headline" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="bio">Bio courte</Label>
                            <textarea
                                id="bio"
                                v-model="form.bio"
                                name="bio"
                                rows="4"
                                class="file:text-foreground placeholder:text-muted-foreground selection:bg-primary selection:text-primary-foreground dark:bg-input/30 border-input w-full rounded-md border bg-transparent px-3 py-2 text-sm shadow-xs transition-[color,box-shadow] outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive"
                                placeholder="Decris ton ton, ta cadence de publication et ce que tes lecteurs peuvent attendre."
                            />
                            <InputError :message="form.errors.bio" />
                        </div>

                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="grid gap-2">
                                <Label for="signature_style">Style signature</Label>
                                <Input
                                    id="signature_style"
                                    v-model="form.signature_style"
                                    name="signature_style"
                                    type="text"
                                    placeholder="Ex: Néon noir, techno-thriller"
                                />
                                <InputError :message="form.errors.signature_style" />
                            </div>
                            <div class="grid gap-2">
                                <Label for="favorite_formats">Formats favoris</Label>
                                <Input
                                    id="favorite_formats"
                                    v-model="form.favorite_formats"
                                    name="favorite_formats"
                                    type="text"
                                    placeholder="Ex: Séries courtes, audio, visual novel"
                                />
                                <InputError :message="form.errors.favorite_formats" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3">
                            <div class="text-xs uppercase tracking-[0.18em] text-white/60">
                                Etape 1/1 : lancer le hub
                            </div>
                            <Button
                                type="submit"
                                :disabled="form.processing"
                                class="bg-emerald-400 text-black shadow-lg transition duration-200 hover:-translate-y-0.5 hover:bg-emerald-300"
                            >
                                Verrouiller mon loadout
                            </Button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
