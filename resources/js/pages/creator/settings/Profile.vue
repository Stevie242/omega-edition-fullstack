<script setup lang="ts">
import CreatorLayout from '@/layouts/CreatorLayout.vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Separator } from '@/components/ui/separator';
import { Globe2, MapPin, UploadCloud } from 'lucide-vue-next';
import { useForm, usePage } from '@inertiajs/vue3';
import { computed, ref, watch, onMounted } from 'vue';
import { useToast } from 'primevue/usetoast';

interface ProfilePayload {
    first_name?: string | null;
    last_name?: string | null;
    display_name?: string | null;
    age?: number | null;
    gender?: string | null;
    nationality?: string | null;
    location?: string | null;
    languages?: string | null;
    headline?: string | null;
    bio?: string | null;
    signature_style?: string | null;
    favorite_formats?: string | null;
    portfolio_links?: string | null;
    moodboard?: string | null;
    website?: string | null;
    phone?: string | null;
    availability?: string | null;
    avatar_url?: string | null;
    cover_url?: string | null;
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
const page = usePage();
const toast = useToast();

const form = useForm({
    email: props.user?.email ?? '',
    first_name: props.profile?.first_name ?? '',
    last_name: props.profile?.last_name ?? '',
    display_name: props.profile?.display_name ?? props.user?.name ?? '',
    age: props.profile?.age ?? '',
    gender: props.profile?.gender ?? '',
    nationality: props.profile?.nationality ?? '',
    location: props.profile?.location ?? '',
    languages: props.profile?.languages ?? '',
    headline: props.profile?.headline ?? '',
    bio: props.profile?.bio ?? '',
    signature_style: props.profile?.signature_style ?? '',
    favorite_formats: props.profile?.favorite_formats ?? '',
    portfolio_links: props.profile?.portfolio_links ?? '',
    moodboard: props.profile?.moodboard ?? '',
    website: props.profile?.website ?? '',
    phone: props.profile?.phone ?? '',
    availability: props.profile?.availability ?? '',
    avatar_url: props.profile?.avatar_url ?? '',
    cover_url: props.profile?.cover_url ?? '',
    avatar_file: null as File | null,
    cover_file: null as File | null,
});

const displayName = computed(() => form.display_name || props.user?.name || 'Createur');
const displayInitial = computed(() => displayName.value?.[0] ?? 'C');

const avatarPreview = ref<string | null>(form.avatar_url || null);
const coverPreview = ref<string | null>(form.cover_url || null);

const avatarFileInput = ref<HTMLInputElement | null>(null);
const coverFileInput = ref<HTMLInputElement | null>(null);

const syncFromProps = () => {
    form.defaults({
        email: props.user?.email ?? '',
        first_name: props.profile?.first_name ?? '',
        last_name: props.profile?.last_name ?? '',
        display_name: props.profile?.display_name ?? props.user?.name ?? '',
        age: props.profile?.age ?? '',
        gender: props.profile?.gender ?? '',
        nationality: props.profile?.nationality ?? '',
        location: props.profile?.location ?? '',
        languages: props.profile?.languages ?? '',
        headline: props.profile?.headline ?? '',
        bio: props.profile?.bio ?? '',
        signature_style: props.profile?.signature_style ?? '',
        favorite_formats: props.profile?.favorite_formats ?? '',
        portfolio_links: props.profile?.portfolio_links ?? '',
        moodboard: props.profile?.moodboard ?? '',
        website: props.profile?.website ?? '',
        phone: props.profile?.phone ?? '',
        availability: props.profile?.availability ?? '',
        avatar_url: props.profile?.avatar_url ?? '',
        cover_url: props.profile?.cover_url ?? '',
        avatar_file: null,
        cover_file: null,
    });

    form.reset();
    avatarPreview.value = props.profile?.avatar_url ?? null;
    coverPreview.value = props.profile?.cover_url ?? null;
};

syncFromProps();

const onFileChange = (event: Event, target: 'avatar_url' | 'cover_url') => {
    const input = event.target as HTMLInputElement;
    const file = input.files?.[0];
    if (!file) return;

    if (target === 'avatar_url') {
        form.avatar_file = file;
        if (avatarPreview.value) URL.revokeObjectURL(avatarPreview.value);
        avatarPreview.value = URL.createObjectURL(file);
    } else {
        form.cover_file = file;
        if (coverPreview.value) URL.revokeObjectURL(coverPreview.value);
        coverPreview.value = URL.createObjectURL(file);
    }
};

watch(
    () => props.profile,
    () => {
        syncFromProps();
    }
);

watch(
    () => form.avatar_url,
    (val) => {
        if (!form.avatar_file) {
            avatarPreview.value = val || null;
        }
    }
);

watch(
    () => form.cover_url,
    (val) => {
        if (!form.cover_file) {
            coverPreview.value = val || null;
        }
    }
);

const flash = page.props.flash as { success?: string; error?: string };

onMounted(() => {
    if (flash?.success) {
        toast.add({ severity: 'success', summary: flash.success, life: 3000 });
    }
    if (flash?.error) {
        toast.add({ severity: 'error', summary: flash.error, life: 3000 });
    }
});

watch(
    () => page.props.flash?.success,
    (val) => {
        if (val) {
            toast.add({ severity: 'success', summary: val, life: 3000 });
        }
    }
);

watch(
    () => page.props.flash?.error,
    (val) => {
        if (val) {
            toast.add({ severity: 'error', summary: val, life: 3000 });
        }
    }
);

const submitProfile = () => {
    form.transform((data) => ({
        ...data,
        _method: 'put',
    }));

    form.post('/creator/settings/profile', {
        forceFormData: true,
        preserveScroll: true,
        onFinish: () => {
            if (form.avatar_file) form.avatar_file = null;
            if (form.cover_file) form.cover_file = null;
            form.clearErrors();
        },
        onError: (errs) => {
            if (errs && typeof errs === 'object') {
                toast.add({ severity: 'error', summary: 'Erreur', detail: 'Verifiez les champs obligatoires.', life: 3000 });
            }
        },
    });
};
</script>

<template>
    <CreatorLayout
        title="Profil createur"
        description="Editer vos infos publiques, vos visuels et vos contacts en un seul endroit."
        :breadcrumbs="[
            { title: 'Parametres', href: '/creator/settings/profile' },
            { title: 'Profil' },
        ]"
    >
        <div class="space-y-6">
            <Card>
                <CardContent class="flex flex-col gap-4 p-6">
                    <div class="flex items-start gap-4">
                        <div class="relative">
                            <Avatar class="size-16 border shadow-sm">
                                <AvatarImage :src="avatarPreview || form.avatar_url" />
                                <AvatarFallback>{{ displayInitial }}</AvatarFallback>
                            </Avatar>
                        </div>
                        <div class="flex-1 space-y-1">
                            <div class="flex items-center justify-between gap-2">
                                <div>
                                    <p class="text-xs uppercase tracking-[0.18em] text-muted-foreground">Profil public</p>
                                    <h2 class="text-xl font-semibold leading-tight">
                                        {{ displayName }}
                                    </h2>
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    <Badge variant="secondary">{{ form.languages || 'Langues' }}</Badge>
                                    <Badge variant="secondary">{{ form.location || 'Localisation' }}</Badge>
                                </div>
                            </div>
                            <p class="text-sm text-muted-foreground">
                                {{ form.headline || 'Ajoutez une accroche courte pour votre page publique.' }}
                            </p>
                        </div>
                    </div>
                    <Separator />
                    <div class="flex flex-wrap items-center gap-2">
                        <Button variant="outline" @click.prevent="avatarFileInput?.click()">Changer la photo</Button>
                        <Button variant="outline" @click.prevent="coverFileInput?.click()">Changer la bannière</Button>
                        <Button @click.prevent="submitProfile" :disabled="form.processing">Enregistrer</Button>
                    </div>
                </CardContent>
            </Card>

            <div class="grid gap-6 lg:grid-cols-[1.7fr_1fr]">
                <div class="space-y-6">
                    <Card>
                        <CardHeader class="gap-2">
                            <CardTitle>Infos principales</CardTitle>
                            <CardDescription>Nom, bio, contact.</CardDescription>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="grid gap-4 md:grid-cols-2">
                                <div class="space-y-2">
                                    <Label for="first_name">Prenom</Label>
                                    <Input id="first_name" v-model="form.first_name" placeholder="Prenom" />
                                    <p v-if="form.errors.first_name" class="text-xs text-destructive">{{ form.errors.first_name }}</p>
                                </div>
                                <div class="space-y-2">
                                    <Label for="last_name">Nom</Label>
                                    <Input id="last_name" v-model="form.last_name" placeholder="Nom" />
                                    <p v-if="form.errors.last_name" class="text-xs text-destructive">{{ form.errors.last_name }}</p>
                                </div>
                                <div class="space-y-2">
                                    <Label for="display_name">Nom public</Label>
                                    <Input id="display_name" v-model="form.display_name" placeholder="Nom public / Studio" />
                                    <p v-if="form.errors.display_name" class="text-xs text-destructive">{{ form.errors.display_name }}</p>
                                </div>
                                <div class="space-y-2">
                                    <Label for="email">Email</Label>
                                    <Input id="email" v-model="form.email" type="email" placeholder="Email" />
                                    <p v-if="form.errors.email" class="text-xs text-destructive">{{ form.errors.email }}</p>
                                </div>
                            </div>
                            <div class="grid gap-4 md:grid-cols-3">
                                <div class="space-y-2">
                                    <Label for="age">Age</Label>
                                    <Input id="age" v-model="form.age" type="number" placeholder="Age" />
                                    <p v-if="form.errors.age" class="text-xs text-destructive">{{ form.errors.age }}</p>
                                </div>
                                <div class="space-y-2">
                                    <Label for="gender">Genre</Label>
                                    <Input id="gender" v-model="form.gender" placeholder="Genre" />
                                    <p v-if="form.errors.gender" class="text-xs text-destructive">{{ form.errors.gender }}</p>
                                </div>
                                <div class="space-y-2">
                                    <Label for="nationality">Nationalite</Label>
                                    <Input id="nationality" v-model="form.nationality" placeholder="Nationalite" />
                                    <p v-if="form.errors.nationality" class="text-xs text-destructive">{{ form.errors.nationality }}</p>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <Label for="bio">Bio</Label>
                                <textarea
                                    id="bio"
                                    v-model="form.bio"
                                    rows="4"
                                    placeholder="Decrivez-vous en quelques phrases."
                                    class="min-h-[110px] w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-xs outline-none transition-[color,box-shadow] placeholder:text-muted-foreground focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px]"
                                ></textarea>
                                <p v-if="form.errors.bio" class="text-xs text-destructive">{{ form.errors.bio }}</p>
                            </div>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader class="gap-2">
                            <CardTitle>Portfolio</CardTitle>
                            <CardDescription>Style, formats, liens.</CardDescription>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="grid gap-4 md:grid-cols-2">
                                <div class="space-y-2">
                                    <Label for="signature">Signature visuelle</Label>
                                    <Input id="signature" v-model="form.signature_style" placeholder="Signature visuelle" />
                                    <p v-if="form.errors.signature_style" class="text-xs text-destructive">{{ form.errors.signature_style }}</p>
                                </div>
                                <div class="space-y-2">
                                    <Label for="formats">Formats favoris</Label>
                                    <Input id="formats" v-model="form.favorite_formats" placeholder="One-shot, webtoon..." />
                                    <p v-if="form.errors.favorite_formats" class="text-xs text-destructive">{{ form.errors.favorite_formats }}</p>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <Label for="portfolio">Liens portfolio</Label>
                                <Input id="portfolio" v-model="form.portfolio_links" placeholder="https://dribbble.com/... https://behance.net/..." />
                                <p v-if="form.errors.portfolio_links" class="text-xs text-destructive">{{ form.errors.portfolio_links }}</p>
                            </div>
                            <div class="space-y-2">
                                <Label for="mood">Moodboard / univers</Label>
                                <textarea
                                    id="mood"
                                    v-model="form.moodboard"
                                    rows="3"
                                    placeholder="References, musiques, inspirations..."
                                    class="min-h-[90px] w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-xs outline-none transition-[color,box-shadow] placeholder:text-muted-foreground focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px]"
                                ></textarea>
                                <p v-if="form.errors.moodboard" class="text-xs text-destructive">{{ form.errors.moodboard }}</p>
                            </div>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader class="gap-2">
                            <CardTitle>Contact</CardTitle>
                            <CardDescription>Email, telephone, disponibilite.</CardDescription>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="grid gap-4 md:grid-cols-2">
                                <div class="space-y-2">
                                    <Label for="phone">Telephone / WhatsApp</Label>
                                    <Input id="phone" v-model="form.phone" placeholder="+242 ..." />
                                    <p v-if="form.errors.phone" class="text-xs text-destructive">{{ form.errors.phone }}</p>
                                </div>
                                <div class="space-y-2">
                                    <Label for="website">Site / vitrine</Label>
                                    <Input id="website" v-model="form.website" placeholder="https://..." />
                                    <p v-if="form.errors.website" class="text-xs text-destructive">{{ form.errors.website }}</p>
                                </div>
                                <div class="space-y-2">
                                    <Label for="location">Localisation</Label>
                                    <Input id="location" v-model="form.location" placeholder="Brazzaville / Pointe-Noire" />
                                    <p v-if="form.errors.location" class="text-xs text-destructive">{{ form.errors.location }}</p>
                                </div>
                                <div class="space-y-2">
                                    <Label for="availability">Disponibilite</Label>
                                    <Input id="availability" v-model="form.availability" placeholder="Disponibilite" />
                                    <p v-if="form.errors.availability" class="text-xs text-destructive">{{ form.errors.availability }}</p>
                                </div>
                            </div>
                        </CardContent>
                        <CardFooter class="flex justify-end">
                            <Button variant="secondary" :disabled="form.processing" @click.prevent="submitProfile">Enregistrer</Button>
                        </CardFooter>
                    </Card>
                </div>

                <div class="space-y-6">
                    <Card>
                        <CardHeader class="gap-2">
                            <CardTitle>Apparence publique</CardTitle>
                            <CardDescription>Photo et bannière, avec preview.</CardDescription>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="space-y-3 rounded-xl border bg-muted/40 p-4 text-center">
                                <div class="mx-auto flex size-24 items-center justify-center overflow-hidden rounded-full border border-dashed border-muted-foreground/40 bg-background">
                                    <img
                                        v-if="avatarPreview"
                                        :src="avatarPreview"
                                        alt="Avatar preview"
                                        class="h-full w-full object-cover"
                                    />
                                    <UploadCloud v-else class="size-7 text-muted-foreground" />
                                </div>
                                <p class="text-sm font-medium">Photo de profil</p>
                                <p class="text-xs text-muted-foreground">512x512 recommande</p>
                                <input
                                    ref="avatarFileInput"
                                    type="file"
                                    accept="image/*"
                                    class="hidden"
                                    @change="(e) => onFileChange(e, 'avatar_url')"
                                />
                                <Button class="w-full" variant="outline" :disabled="form.processing" @click.prevent="avatarFileInput?.click()">
                                    Choisir un fichier
                                </Button>
                            </div>
                            <div class="space-y-3 rounded-xl border bg-muted/40 p-4">
                                <div class="aspect-[3/1] overflow-hidden rounded-lg border border-dashed border-muted-foreground/40 bg-gradient-to-r from-slate-100 to-slate-200 dark:from-slate-800 dark:to-slate-900">
                                    <img
                                        v-if="coverPreview"
                                        :src="coverPreview"
                                        alt="Couverture preview"
                                        class="h-full w-full object-cover"
                                    />
                                    <div v-else class="flex h-full items-center justify-center text-muted-foreground">
                                        <UploadCloud class="size-6" />
                                    </div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium">Banniere</p>
                                        <p class="text-xs text-muted-foreground">1440x480 recommande</p>
                                    </div>
                                    <Button variant="secondary" size="sm" :disabled="form.processing" @click.prevent="coverFileInput?.click()">Choisir</Button>
                                </div>
                                <input
                                    ref="coverFileInput"
                                    type="file"
                                    accept="image/*"
                                    class="hidden"
                                    @change="(e) => onFileChange(e, 'cover_url')"
                                />
                            </div>
                            <div class="flex justify-end">
                                <Button :disabled="form.processing" @click.prevent="submitProfile">Enregistrer</Button>
                            </div>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader class="gap-2">
                            <CardTitle>Resume express</CardTitle>
                            <CardDescription>Ce qui apparait sur la carte profil.</CardDescription>
                        </CardHeader>
                        <CardContent class="space-y-3">
                            <div class="grid gap-3 sm:grid-cols-2">
                                <div class="space-y-2">
                                    <Label for="headline">Accroche</Label>
                                    <textarea
                                        id="headline"
                                        v-model="form.headline"
                                        rows="3"
                                        placeholder="Accroche courte"
                                        class="min-h-[80px] w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-xs outline-none transition-[color,box-shadow] placeholder:text-muted-foreground focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px]"
                                    ></textarea>
                                    <p v-if="form.errors.headline" class="text-xs text-destructive">{{ form.errors.headline }}</p>
                                </div>
                                <div class="space-y-2">
                                    <Label for="languages">Langues</Label>
                                    <Input id="languages" v-model="form.languages" placeholder="FR / EN ..." />
                                    <p v-if="form.errors.languages" class="text-xs text-destructive">{{ form.errors.languages }}</p>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <Label for="location_res">Localisation</Label>
                                <Input id="location_res" v-model="form.location" placeholder="Ville / Pays" />
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <Badge variant="secondary"><Globe2 class="size-3" /> {{ form.languages || 'Langues' }}</Badge>
                                <Badge variant="secondary"><MapPin class="size-3" /> {{ form.location || 'Localisation' }}</Badge>
                                <Badge variant="secondary">{{ form.availability || 'Disponibilite' }}</Badge>
                            </div>
                            <Separator />
                            <p class="text-xs text-muted-foreground">
                                Ce bloc peut etre repris sur les vignettes profils et cartes sociales.
                            </p>
                        </CardContent>
                    </Card>
                </div>
            </div>

        </div>
    </CreatorLayout>
</template>
