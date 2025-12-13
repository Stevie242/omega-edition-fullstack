<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import ReaderLayout from '@/layouts/ReaderLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import SpeedDial from 'primevue/speeddial';
import { computed } from 'vue';

interface Page {
    id: string;
    order: number;
    url?: string | null;
}

interface Chapter {
    id: string;
    title: string;
    number: number;
    status: string;
    published_at?: string | null;
    series?: { id?: string; title?: string | null } | null;
    pages?: Page[];
    prev_id?: string | null;
    next_id?: string | null;
    likes_count?: number | null;
    dislikes_count?: number | null;
    user_reaction?: 'like' | 'dislike' | null;
    can_comment?: boolean;
}

const props = defineProps<{
    chapter: Chapter;
}>();

const items = computed(() => {
    const list = [];
    if (props.chapter.prev_id) {
        list.push({
            label: 'Chapitre précédent',
            icon: 'pi pi-arrow-left',
            command: () => router.visit(`/reader/chapters/${props.chapter.prev_id}`),
        });
    }
    if (props.chapter.next_id) {
        list.push({
            label: 'Chapitre suivant',
            icon: 'pi pi-arrow-right',
            command: () => router.visit(`/reader/chapters/${props.chapter.next_id}`),
        });
    }
    list.push({
        label: 'Like',
        icon: 'pi pi-thumbs-up',
        command: () => router.post(`/reader/chapters/${props.chapter.id}/like`),
    });
    list.push({
        label: 'Dislike',
        icon: 'pi pi-thumbs-down',
        command: () => router.post(`/reader/chapters/${props.chapter.id}/dislike`),
    });
    if (props.chapter.series?.id) {
        list.push({
            label: 'Favoris série',
            icon: 'pi pi-bookmark',
            command: () => router.post(`/reader/series/${props.chapter.series.id}/favorite`),
        });
    }
    return list;
});
</script>

<template>
    <ReaderLayout
        :title="`Chapitre ${chapter.number}`"
        :description="chapter.title ?? 'Lecture'"
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
                    <Button variant="outline" :disabled="!chapter.prev_id" as-child>
                        <Link :href="chapter.prev_id ? `/reader/chapters/${chapter.prev_id}` : '#'">Précédent</Link>
                    </Button>
                    <Button variant="outline" :disabled="!chapter.next_id" as-child>
                        <Link :href="chapter.next_id ? `/reader/chapters/${chapter.next_id}` : '#'">Suivant</Link>
                    </Button>
                </div>
            </div>

            <Card>
                <CardHeader class="flex flex-wrap items-center justify-between gap-2">
                    <div>
                        <CardTitle>Lecture immersive</CardTitle>
                        <CardDescription>Défilement continu des pages.</CardDescription>
                    </div>
                    <div class="flex gap-2">
                        <Button
                            :variant="chapter.user_reaction === 'like' ? 'default' : 'secondary'"
                            @click.prevent="$inertia.post(`/reader/chapters/${chapter.id}/like`)"
                        >
                            👍 {{ chapter.likes_count ?? 0 }}
                        </Button>
                        <Button
                            :variant="chapter.user_reaction === 'dislike' ? 'default' : 'secondary'"
                            @click.prevent="$inertia.post(`/reader/chapters/${chapter.id}/dislike`)"
                        >
                            👎 {{ chapter.dislikes_count ?? 0 }}
                        </Button>
                    </div>
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
                    <CardTitle>Commentaires</CardTitle>
                    <CardDescription>Réactions des lecteurs (bientôt).</CardDescription>
                </CardHeader>
                <CardContent>
                    <div v-if="!chapter.can_comment" class="text-sm text-muted-foreground">
                        Les commentaires seront disponibles après avoir lu un chapitre.
                    </div>
                    <div v-else class="text-sm text-muted-foreground">
                        Module de commentaires à implémenter.
                    </div>
                </CardContent>
            </Card>

            <div class="fixed bottom-6 right-6">
                <SpeedDial
                    :model="items"
                    direction="up"
                    type="semi-circle"
                    :radius="80"
                    mask
                    :style="{ position: 'relative' }"
                    :buttonProps="{ severity: 'help', rounded: true }"
                />
            </div>
        </div>
    </ReaderLayout>
</template>
