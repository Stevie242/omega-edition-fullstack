<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Separator } from '@/components/ui/separator';
import ReaderLayout from '@/layouts/ReaderLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { UploadCloud } from 'lucide-vue-next';
import { computed } from 'vue';

interface ProfilePayload {
    first_name?: string | null;
    last_name?: string | null;
    avatar_url?: string | null;
    birthdate?: string | null;
    age?: number | null;
    preferred_genres?: string[] | null;
    preferred_formats?: string[] | null;
    preferred_themes?: string[] | null;
    language_preferences?: string | null;
}

interface Props {
    user: {
        id: string;
        name: string;
        email: string;
        role: string;
    };
    profile: ProfilePayload | null;
}

const props = defineProps<Props>();

const csvString = (values?: string[] | null) => (Array.isArray(values) ? values.join(', ') : '');
const parseCsv = (value: string) =>
    value
        .split(',')
        .map((g) => g.trim())
        .filter(Boolean);

const form = useForm({
    email: props.user?.email ?? '',
    first_name: props.profile?.first_name ?? '',
    last_name: props.profile?.last_name ?? '',
    birthdate: props.profile?.birthdate ?? '',
    preferred_genres_input: csvString(props.profile?.preferred_genres),
    preferred_formats_input: csvString(props.profile?.preferred_formats),
    preferred_themes_input: csvString(props.profile?.preferred_themes),
    language_preferences: props.profile?.language_preferences ?? '',
    avatar_file: null as File | null,
});

const submit = () => {
    form.transform((data) => ({
        email: data.email,
        first_name: data.first_name,
        last_name: data.last_name,
        birthdate: data.birthdate,
        preferred_genres: parseCsv(data.preferred_genres_input ?? ''),
        preferred_formats: parseCsv(data.preferred_formats_input ?? ''),
        preferred_themes: parseCsv(data.preferred_themes_input ?? ''),
        language_preferences: data.language_preferences,
        avatar_file: data.avatar_file,
    })).post('/reader/settings/profile');
};

const previewAvatar = computed(() =>
    form.avatar_file
        ? URL.createObjectURL(form.avatar_file)
        : props.profile?.avatar_url ?? undefined,
);
</script>

<template>
    <ReaderLayout
        title="Profil lecteur"
        description="Mets à jour tes infos et tes préférences pour affiner les recos Omega."
        :breadcrumbs="[
            { title: 'Paramètres', href: '/reader/settings/profile' },
            { title: 'Profil' },
        ]"
    >
        <div class="grid gap-6 lg:grid-cols-12">
            <Card class="lg:col-span-8">
                <CardHeader class="space-y-1">
                    <CardTitle>Identité</CardTitle>
                    <CardDescription>Prénom, nom et email utilisés pour ton espace.</CardDescription>
                </CardHeader>
                <CardContent class="space-y-4">
                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="space-y-2">
                            <Label for="first_name">Prénom</Label>
                            <Input id="first_name" v-model="form.first_name" name="first_name" autocomplete="given-name" />
                            <InputError :message="form.errors.first_name" />
                        </div>
                        <div class="space-y-2">
                            <Label for="last_name">Nom</Label>
                            <Input id="last_name" v-model="form.last_name" name="last_name" autocomplete="family-name" />
                            <InputError :message="form.errors.last_name" />
                        </div>
                    </div>
                    <div class="space-y-2">
                        <Label for="email">Email</Label>
                        <Input id="email" v-model="form.email" name="email" type="email" autocomplete="email" />
                        <InputError :message="form.errors.email" />
                    </div>
                    <div class="space-y-2">
                        <Label for="birthdate">Date de naissance</Label>
                        <Input id="birthdate" v-model="form.birthdate" name="birthdate" type="date" />
                        <InputError :message="form.errors.birthdate" />
                    </div>
                </CardContent>
                <CardFooter class="flex justify-end">
                    <Button :disabled="form.processing" @click="submit">Enregistrer</Button>
                </CardFooter>
            </Card>

            <Card class="lg:col-span-4">
                <CardHeader class="space-y-1">
                    <CardTitle>Avatar</CardTitle>
                    <CardDescription>Image publique de ton profil lecteur.</CardDescription>
                </CardHeader>
                <CardContent class="space-y-4">
                    <div class="flex items-center gap-3">
                        <Avatar class="h-16 w-16 ring-2 ring-primary/30">
                            <AvatarImage :src="previewAvatar" alt="Avatar" />
                            <AvatarFallback>
                                {{ (form.first_name || 'O')[0] }}{{ (form.last_name || 'E')[0] }}
                            </AvatarFallback>
                        </Avatar>
                        <div class="text-xs text-muted-foreground">
                            Formats PNG/JPG, max 5 Mo. Upload depuis ton appareil.
                        </div>
                    </div>
                    <div class="grid gap-3">
                        <div class="space-y-2">
                            <Label for="avatar_file">Uploader</Label>
                            <label
                                class="group flex cursor-pointer items-center justify-between gap-3 rounded-md border border-dashed border-muted-foreground/40 px-3 py-2 text-sm"
                            >
                                <div class="flex items-center gap-3">
                                    <span class="flex h-9 w-9 items-center justify-center rounded-md bg-muted/50 text-foreground">
                                        <UploadCloud class="size-4" />
                                    </span>
                                    <div class="flex flex-col">
                                        <span class="font-medium">Choisir un fichier</span>
                                        <span class="text-[11px] text-muted-foreground">PNG/JPG jusqu'à 5 Mo</span>
                                    </div>
                                </div>
                                <input
                                    id="avatar_file"
                                    name="avatar_file"
                                    type="file"
                                    accept="image/*"
                                    class="sr-only"
                                    @change="(e: Event) => form.avatar_file = (e.target as HTMLInputElement)?.files?.[0] ?? null"
                                />
                                <span class="text-xs text-muted-foreground">
                                    {{ form.avatar_file ? form.avatar_file.name : 'Aucun fichier' }}
                                </span>
                            </label>
                            <InputError :message="form.errors.avatar_file" />
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>

        <Separator class="my-6" />

        <Card>
            <CardHeader class="space-y-1">
                <CardTitle>Préférences de lecture</CardTitle>
                <CardDescription>Genres, thèmes et formats pour personnaliser tes recos.</CardDescription>
            </CardHeader>
            <CardContent class="space-y-4">
                <div class="grid gap-4 md:grid-cols-2">
                    <div class="space-y-2">
                        <Label for="preferred_genres">Genres (séparés par virgules)</Label>
                        <Input
                            id="preferred_genres"
                            v-model="form.preferred_genres_input"
                            name="preferred_genres"
                            placeholder="Shonen, Seinen, Romance..."
                        />
                        <InputError :message="form.errors.preferred_genres" />
                    </div>
                    <div class="space-y-2">
                        <Label for="preferred_themes">Thèmes</Label>
                        <Input
                            id="preferred_themes"
                            v-model="form.preferred_themes_input"
                            name="preferred_themes"
                            placeholder="Cyberpunk, Fantastique..."
                        />
                        <InputError :message="form.errors.preferred_themes" />
                    </div>
                </div>
                <div class="grid gap-4 md:grid-cols-2">
                    <div class="space-y-2">
                        <Label for="preferred_formats">Formats</Label>
                        <Input
                            id="preferred_formats"
                            v-model="form.preferred_formats_input"
                            name="preferred_formats"
                            placeholder="Webtoon, Manga relié, Audio..."
                        />
                        <InputError :message="form.errors.preferred_formats" />
                    </div>
                    <div class="space-y-2">
                        <Label for="language_preferences">Langue</Label>
                        <Input
                            id="language_preferences"
                            v-model="form.language_preferences"
                            name="language_preferences"
                            placeholder="Français, Anglais..."
                        />
                        <InputError :message="form.errors.language_preferences" />
                    </div>
                </div>
            </CardContent>
            <CardFooter class="flex justify-end">
                <Button variant="secondary" :disabled="form.processing" @click="submit">Sauvegarder les préférences</Button>
            </CardFooter>
        </Card>
    </ReaderLayout>
</template>
