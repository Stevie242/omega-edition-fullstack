<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import PublicLayout from '@/layouts/PublicLayout.vue';
import { Link } from '@inertiajs/vue3';

interface Page {
    id: string;
    order: number;
    url?: string | null;
}

interface Chapter {
    id: string;
    title: string;
    number: number;
    series?: { id?: string; title?: string | null; cover_url?: string | null } | null;
    pages?: Page[];
}

const props = defineProps<{
    chapter: Chapter;
}>();
</script>

<template>
    <PublicLayout
        :title="`Chapitre ${chapter.number}`"
        :description="chapter.title ?? 'Lecture publique'"
    >
        <div class="space-y-6">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div class="space-y-1">
                    <h1 class="text-2xl font-semibold leading-tight">
                        Chapitre {{ chapter.number }} · {{ chapter.title }}
                    </h1>
                    <p class="text-sm text-muted-foreground">
                        {{ chapter.series?.title ?? '' }}
                    </p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <Button as-child variant="ghost">
                        <Link :href="chapter.series?.id ? `/catalog/${chapter.series.id}` : '/catalog'">Retour série</Link>
                    </Button>
                    <Button as-child>
                        <Link href="/register/reader">Créer un compte pour lire la suite</Link>
                    </Button>
                </div>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>Lecture gratuite</CardTitle>
                    <CardDescription>Ce chapitre est accessible sans compte.</CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="flex flex-col gap-4">
                        <div
                            v-for="page in chapter.pages ?? []"
                            :key="page.id"
                            class="overflow-hidden rounded-lg border bg-muted"
                        >
                            <img
                                v-if="page.url"
                                :src="page.url"
                                :alt="`Page ${page.order}`"
                                class="w-full"
                            />
                            <div v-else class="flex h-32 items-center justify-center text-sm text-muted-foreground">
                                Page indisponible
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <Card>
                <CardHeader>
                    <CardTitle>Envie de continuer ?</CardTitle>
                    <CardDescription>Crée un compte lecteur pour débloquer tous les chapitres.</CardDescription>
                </CardHeader>
                <CardContent class="flex flex-wrap gap-3">
                    <Button as-child>
                        <Link href="/register/reader">Créer un compte lecteur</Link>
                    </Button>
                    <Button variant="outline" as-child>
                        <Link href="/login">Se connecter</Link>
                    </Button>
                </CardContent>
            </Card>
        </div>
    </PublicLayout>
</template>
