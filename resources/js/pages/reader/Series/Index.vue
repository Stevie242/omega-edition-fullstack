<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import ReaderLayout from '@/layouts/ReaderLayout.vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

interface SeriesItem {
    id: string;
    title: string;
    slug: string;
    type: string;
    status: string;
    format: string;
    language: string;
    synopsis?: string | null;
    rating?: number | null;
    cover_url?: string | null;
    creator?: { id?: string; name?: string | null } | null;
    tags?: { name: string; slug: string }[];
}

interface Paginated<T> {
    data: T[];
    links: { url: string | null; label: string; active: boolean }[];
    meta?: Record<string, unknown>;
}

const props = defineProps<{
    series: Paginated<SeriesItem>;
    filters: Record<string, string | undefined | null>;
    filterOptions: {
        types: string[];
        statuses: string[];
        formats: string[];
        tags: { name: string; slug: string }[];
    };
}>();

const form = useForm({
    search: props.filters.search ?? '',
    type: props.filters.type ?? '',
    status: props.filters.status ?? '',
    format: props.filters.format ?? '',
    tag: props.filters.tag ?? '',
});

const submitFilters = () => {
    router.get('/reader/series', form.data(), { preserveScroll: true, preserveState: true });
};

const clearFilters = () => {
    form.search = '';
    form.type = '';
    form.status = '';
    form.format = '';
    form.tag = '';
    submitFilters();
};

const hasFilters = computed(() => {
    return Boolean(form.search || form.type || form.status || form.format || form.tag);
});
</script>

<template>
    <ReaderLayout
        title="Catalogue"
        description="Explore les séries par genre, format et statut."
    >
        <div class="space-y-6">
            <Card>
                <CardHeader class="space-y-2">
                    <CardTitle>Filtres</CardTitle>
                    <CardDescription>Affiner les résultats par type, format, statut ou tag.</CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                        <div class="space-y-2">
                            <Label for="search">Recherche</Label>
                            <Input
                                id="search"
                                v-model="form.search"
                                placeholder="Titre, mot-clé..."
                                @keyup.enter="submitFilters"
                            />
                            <InputError :message="form.errors.search" />
                        </div>
                        <div class="space-y-2">
                            <Label for="type">Type</Label>
                            <select
                                id="type"
                                v-model="form.type"
                                class="h-9 w-full rounded-md border border-input bg-background px-3 text-sm text-foreground"
                                @change="submitFilters"
                            >
                                <option value="">Tous</option>
                                <option v-for="t in filterOptions.types" :key="t" :value="t">{{ t }}</option>
                            </select>
                            <InputError :message="form.errors.type" />
                        </div>
                        <div class="space-y-2">
                            <Label for="status">Statut</Label>
                            <select
                                id="status"
                                v-model="form.status"
                                class="h-9 w-full rounded-md border border-input bg-background px-3 text-sm text-foreground"
                                @change="submitFilters"
                            >
                                <option value="">Tous</option>
                                <option v-for="s in filterOptions.statuses" :key="s" :value="s">{{ s }}</option>
                            </select>
                            <InputError :message="form.errors.status" />
                        </div>
                        <div class="space-y-2">
                            <Label for="format">Format</Label>
                            <select
                                id="format"
                                v-model="form.format"
                                class="h-9 w-full rounded-md border border-input bg-background px-3 text-sm text-foreground"
                                @change="submitFilters"
                            >
                                <option value="">Tous</option>
                                <option v-for="f in filterOptions.formats" :key="f" :value="f">{{ f }}</option>
                            </select>
                            <InputError :message="form.errors.format" />
                        </div>
                        <div class="space-y-2 md:col-span-2 lg:col-span-4">
                            <Label for="tag">Tag</Label>
                            <select
                                id="tag"
                                v-model="form.tag"
                                class="h-9 w-full rounded-md border border-input bg-background px-3 text-sm text-foreground"
                                @change="submitFilters"
                            >
                                <option value="">Tous</option>
                                <option v-for="tag in filterOptions.tags" :key="tag.slug" :value="tag.slug">
                                    {{ tag.name }}
                                </option>
                            </select>
                            <InputError :message="form.errors.tag" />
                        </div>
                    </div>
                </CardContent>
                <CardFooter class="flex flex-wrap gap-3">
                    <Button variant="default" @click="submitFilters" :disabled="form.processing">Filtrer</Button>
                    <Button variant="ghost" @click="clearFilters" :disabled="form.processing" v-if="hasFilters">
                        Réinitialiser
                    </Button>
                </CardFooter>
            </Card>

            <div class="grid gap-4">
                <div v-if="series.data.length === 0" class="rounded-lg border border-dashed p-6 text-sm text-muted-foreground">
                    Aucune série trouvée pour ces filtres.
                </div>
                <div v-else class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                    <Card v-for="item in series.data" :key="item.id" class="flex flex-col overflow-hidden">
                        <div class="aspect-[4/5] w-full overflow-hidden bg-muted">
                            <img
                                v-if="item.cover_url"
                                :src="item.cover_url"
                                :alt="item.title"
                                class="h-full w-full object-cover"
                            />
                            <div v-else class="flex h-full items-center justify-center text-sm text-muted-foreground">
                                Couverture indisponible
                            </div>
                        </div>
                        <CardHeader class="space-y-1">
                            <CardTitle class="text-base">{{ item.title }}</CardTitle>
                            <CardDescription>
                                {{ item.creator?.name ?? 'Auteur inconnu' }} · {{ item.type }}
                            </CardDescription>
                        </CardHeader>
                        <CardContent class="flex-1 space-y-2 text-sm text-muted-foreground">
                            <div class="flex flex-wrap gap-2">
                                <Badge variant="secondary">{{ item.status }}</Badge>
                                <Badge variant="outline">{{ item.format }}</Badge>
                            </div>
                            <div class="line-clamp-3">{{ item.synopsis ?? 'Synopsis non fourni.' }}</div>
                            <div class="flex flex-wrap gap-1">
                                <Badge v-for="tag in item.tags ?? []" :key="tag.slug" variant="secondary">{{ tag.name }}</Badge>
                            </div>
                        </CardContent>
                        <CardFooter>
                            <Button asChild class="w-full">
                                <Link :href="`/reader/series/${item.id}`">Ouvrir</Link>
                            </Button>
                        </CardFooter>
                    </Card>
                </div>
            </div>

            <div v-if="series.links && series.links.length" class="flex flex-wrap items-center gap-2">
                <Button
                    v-for="link in series.links"
                    :key="link.label"
                    :variant="link.active ? 'default' : 'ghost'"
                    :disabled="!link.url"
                    @click="link.url && router.visit(link.url, { preserveScroll: true })"
                    class="text-sm"
                >
                    <span>
                        {{
                            link.label === 'pagination.previous'
                                ? 'Précédent'
                                : link.label === 'pagination.next'
                                  ? 'Suivant'
                                  : link.label
                        }}
                    </span>
                </Button>
            </div>
        </div>
    </ReaderLayout>
</template>
