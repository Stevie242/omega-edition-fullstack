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
import { Camera, Film, Globe2, MapPin, Music, UploadCloud, Video } from 'lucide-vue-next';

const featuredTags = ['Seinen', 'Aventure', 'Cyberpunk'];
const hobbies = ['Croquis', 'Boxe', 'Sound design', 'Photographie'];
const languages = ['FR', 'EN', 'JP'];
</script>

<template>
    <CreatorLayout
        title="Profil createur"
        description="Tout editer depuis cette page: infos publiques, medias, portfolio et contacts."
        :breadcrumbs="[
            { title: 'Parametres', href: '/creator/settings/profile' },
            { title: 'Profil' },
        ]"
    >
        <div class="space-y-6">
            <Card>
                <CardContent class="flex flex-col gap-6 p-6 md:flex-row md:items-center md:justify-between">
                    <div class="flex items-center gap-4">
                        <div class="relative">
                            <Avatar class="size-16 border shadow-sm">
                                <AvatarImage
                                    src="https://images.unsplash.com/photo-1524504388940-b1c1722653e1?w=240&auto=format&fit=crop&q=80"
                                />
                                <AvatarFallback>MC</AvatarFallback>
                            </Avatar>
                        </div>
                        <div class="space-y-1">
                            <p class="text-xs uppercase tracking-[0.18em] text-muted-foreground">Profil public</p>
                            <h2 class="text-xl font-semibold leading-tight">Mika Okada</h2>
                            <p class="text-sm text-muted-foreground">Auteur / Character design / Storytelling</p>
                            <div class="flex flex-wrap gap-2">
                                <Badge v-for="tag in featuredTags" :key="tag" variant="secondary">
                                    {{ tag }}
                                </Badge>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <Button variant="outline">
                            <Camera class="size-4" /> Modifier l'avatar
                        </Button>
                        <Button variant="secondary">
                            Previsualiser
                        </Button>
                    </div>
                </CardContent>
                <Separator />
                <CardFooter class="grid gap-3 px-6 py-4 sm:grid-cols-4">
                    <div class="space-y-1">
                        <p class="text-xs uppercase text-muted-foreground">Abonnes</p>
                        <p class="text-lg font-semibold">18,4K</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-xs uppercase text-muted-foreground">Series</p>
                        <p class="text-lg font-semibold">6 actives</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-xs uppercase text-muted-foreground">Langues</p>
                        <p class="text-lg font-semibold">{{ languages.join(' / ') }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-xs uppercase text-muted-foreground">Localisation</p>
                        <p class="text-lg font-semibold">Paris / Tokyo</p>
                    </div>
                </CardFooter>
            </Card>

            <div class="grid gap-6 lg:grid-cols-[1.6fr_1fr]">
                <div class="space-y-6">
                    <Card>
                        <CardHeader class="gap-2">
                            <CardTitle>Identite</CardTitle>
                            <CardDescription>Infos publiques en une fois, sans changer de page.</CardDescription>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="grid gap-4 md:grid-cols-2">
                                <div class="space-y-2">
                                    <Label for="first_name">Prenom</Label>
                                    <Input id="first_name" placeholder="Mika" />
                                </div>
                                <div class="space-y-2">
                                    <Label for="last_name">Nom</Label>
                                    <Input id="last_name" placeholder="Okada" />
                                </div>
                                <div class="space-y-2">
                                    <Label for="nickname">Nom public / Studio</Label>
                                    <Input id="nickname" placeholder="Studio Pulse / Mika Sensei" />
                                </div>
                                <div class="grid gap-3 sm:grid-cols-3">
                                    <div class="space-y-2">
                                        <Label for="age">Age</Label>
                                        <Input id="age" placeholder="28" />
                                    </div>
                                    <div class="space-y-2">
                                        <Label for="gender">Genre</Label>
                                        <Input id="gender" placeholder="Femme / Elle" />
                                    </div>
                                    <div class="space-y-2">
                                        <Label for="nationality">Nationalite</Label>
                                        <Input id="nationality" placeholder="Franco-japonaise" />
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <Label for="bio">Bio courte</Label>
                                <textarea
                                    id="bio"
                                    rows="4"
                                    placeholder="Pitch concis, influences, univers de predilection..."
                                    class="min-h-[120px] w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-xs outline-none transition-[color,box-shadow] placeholder:text-muted-foreground focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px]"
                                ></textarea>
                            </div>
                        </CardContent>
                        <CardFooter class="flex items-center justify-between">
                            <p class="text-xs text-muted-foreground">Visible sur la page publique.</p>
                            <Button>Enregistrer</Button>
                        </CardFooter>
                    </Card>

                    <Card>
                        <CardHeader class="gap-2">
                            <CardTitle>Portfolio</CardTitle>
                            <CardDescription>Positionnez votre style, formats et liens clefs.</CardDescription>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="grid gap-4 md:grid-cols-2">
                                <div class="space-y-2">
                                    <Label for="signature">Signature visuelle</Label>
                                    <Input id="signature" placeholder="Trait nerveux, palettes neons" />
                                </div>
                                <div class="space-y-2">
                                    <Label for="formats">Formats favoris</Label>
                                    <Input id="formats" placeholder="One-shot, webtoon vertical, 12-18 chapitres" />
                                </div>
                            </div>
                            <div class="space-y-2">
                                <Label for="portfolio">Liens portfolio</Label>
                                <Input id="portfolio" placeholder="https://dribbble.com/... https://behance.net/..." />
                            </div>
                            <div class="space-y-2">
                                <Label for="mood">Moodboard / univers</Label>
                                <textarea
                                    id="mood"
                                    rows="4"
                                    placeholder="References visuelles, themes recurrents, musiques..."
                                    class="min-h-[120px] w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-xs outline-none transition-[color,box-shadow] placeholder:text-muted-foreground focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px]"
                                ></textarea>
                                <div class="flex flex-wrap gap-2">
                                    <Badge v-for="hobby in hobbies" :key="hobby" variant="secondary">
                                        <Music class="size-3" /> {{ hobby }}
                                    </Badge>
                                </div>
                            </div>
                        </CardContent>
                        <CardFooter class="flex justify-end">
                            <Button variant="secondary">Sauvegarder</Button>
                        </CardFooter>
                    </Card>

                    <Card>
                        <CardHeader class="gap-2">
                            <CardTitle>Contact et dispo</CardTitle>
                            <CardDescription>Ajustable sans sortir de la page.</CardDescription>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="grid gap-4 md:grid-cols-2">
                                <div class="space-y-2">
                                    <Label for="email">Email principal</Label>
                                    <Input id="email" placeholder="mika.okada@studio.com" />
                                </div>
                                <div class="space-y-2">
                                    <Label for="phone">Telephone / WhatsApp</Label>
                                    <Input id="phone" placeholder="+33 6 12 34 56 78" />
                                </div>
                                <div class="space-y-2">
                                    <Label for="website">Site / vitrine</Label>
                                    <Input id="website" placeholder="https://mika-studio.com" />
                                </div>
                                <div class="space-y-2">
                                    <Label for="timezone">Fuseau horaire</Label>
                                    <Input id="timezone" placeholder="UTC+1 / UTC+9" />
                                </div>
                            </div>
                            <div class="space-y-2">
                                <Label for="availability">Disponibilite</Label>
                                <Input id="availability" placeholder="Ouverte aux projets Q1 / Missions courtes" />
                            </div>
                        </CardContent>
                        <CardFooter class="flex items-center justify-between">
                            <p class="text-xs text-muted-foreground">Prive: visible uniquement par l'equipe.</p>
                            <Button variant="outline">Mettre a jour</Button>
                        </CardFooter>
                    </Card>
                </div>

                <div class="space-y-6">
                    <Card>
                        <CardHeader class="gap-2">
                            <CardTitle>Medias</CardTitle>
                            <CardDescription>Uploads rapides sans redirection.</CardDescription>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="grid gap-4 sm:grid-cols-[1fr_1.1fr]">
                                <div class="space-y-3 rounded-xl border bg-muted/40 p-4 text-center">
                                    <div class="mx-auto flex size-20 items-center justify-center rounded-full border border-dashed border-muted-foreground/40 bg-background">
                                        <UploadCloud class="size-7 text-muted-foreground" />
                                    </div>
                                    <p class="text-sm font-medium">Avatar unique</p>
                                    <p class="text-xs text-muted-foreground">PNG ou JPG / 512x512 / Max 2 Mo</p>
                                    <Button class="w-full">Choisir un fichier</Button>
                                </div>
                                <div class="space-y-3 rounded-xl border bg-muted/40 p-4">
                                    <div class="aspect-[3/1] overflow-hidden rounded-lg border border-dashed border-muted-foreground/40 bg-gradient-to-r from-slate-100 to-slate-200 dark:from-slate-800 dark:to-slate-900">
                                        <div class="flex h-full items-center justify-center text-muted-foreground">
                                            <Camera class="mr-2 size-4" /> Couverture placeholder
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium">Couverture</p>
                                            <p class="text-xs text-muted-foreground">1440x480 recommande / JPG/PNG</p>
                                        </div>
                                        <Button variant="secondary" size="sm">Changer</Button>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader class="gap-2">
                            <CardTitle>Video de presentation</CardTitle>
                            <CardDescription>Pitch, bande-annonce ou interview.</CardDescription>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="overflow-hidden rounded-xl border bg-muted/40">
                                <div class="aspect-video bg-black/60">
                                    <div class="flex h-full items-center justify-center text-muted-foreground">
                                        <Video class="mr-2 size-5" /> Placeholder lecteur video
                                    </div>
                                </div>
                                <div class="flex items-center justify-between px-4 py-3">
                                    <div>
                                        <p class="text-sm font-medium">Showreel 2025 / 1m12</p>
                                        <p class="text-xs text-muted-foreground">YouTube / Non listee</p>
                                    </div>
                                    <Button variant="outline" size="sm">Changer</Button>
                                </div>
                            </div>
                            <div class="space-y-3 rounded-xl border border-dashed border-muted-foreground/40 p-4 text-center">
                                <p class="text-sm font-medium">Ajouter une video</p>
                                <p class="text-xs text-muted-foreground">URL YouTube, Vimeo ou upload direct (MP4 / 500 Mo max)</p>
                                <div class="flex flex-wrap justify-center gap-2">
                                    <Badge variant="outline">Pitch 90s</Badge>
                                    <Badge variant="outline">Bande-annonce</Badge>
                                    <Badge variant="outline">Process dessin</Badge>
                                </div>
                                <Button variant="secondary">
                                    <UploadCloud class="size-4" /> Importer ou coller un lien
                                </Button>
                            </div>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader class="gap-2">
                            <CardTitle>Resume express</CardTitle>
                            <CardDescription>Pour les fiches serie et le catalogue.</CardDescription>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="flex flex-wrap gap-2">
                                <Badge variant="secondary">Disponible</Badge>
                                <Badge variant="secondary"><Globe2 class="size-3" /> {{ languages.join(' / ') }}</Badge>
                                <Badge variant="secondary"><MapPin class="size-3" /> Paris / Tokyo</Badge>
                            </div>
                            <div class="space-y-2">
                                <p class="text-sm font-medium">Accroche</p>
                                <p class="text-sm text-muted-foreground">
                                    "J'explore la frontiere entre le quotidien et le cyberpunk, avec des heroines faillibles et des arcs emotionnels serres."
                                </p>
                            </div>
                            <Separator />
                            <div class="grid gap-3 sm:grid-cols-2">
                                <div class="rounded-lg border bg-muted/40 p-3">
                                    <div class="mb-2 flex items-center gap-2 text-xs uppercase tracking-[0.18em] text-muted-foreground">
                                        <Film class="size-3" /> Series phares
                                    </div>
                                    <p class="text-sm font-semibold">Neon Blossoms</p>
                                    <p class="text-xs text-muted-foreground">Thriller / 12 chapitres</p>
                                </div>
                                <div class="rounded-lg border bg-muted/40 p-3">
                                    <div class="mb-2 flex items-center gap-2 text-xs uppercase tracking-[0.18em] text-muted-foreground">
                                        <Music class="size-3" /> Inspirations
                                    </div>
                                    <p class="text-sm font-semibold">Lo-fi, City Pop, synthes analogiques</p>
                                    <p class="text-xs text-muted-foreground">
                                        Moodboard sonore pour trailer et marketing.
                                    </p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </CreatorLayout>
</template>
