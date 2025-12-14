<script setup lang="ts">
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Link } from '@inertiajs/vue3';

interface SupportItem {
    title: string;
    description: string;
    href?: string;
    actionLabel?: string;
    badge?: string;
}

withDefaults(
    defineProps<{
        subtitle?: string;
        audience?: string;
        items?: SupportItem[];
        extraNote?: string;
    }>(),
    {
        subtitle: '',
        audience: '',
        items: () => [],
        extraNote: '',
    },
);
</script>

<template>
    <div class="space-y-6">
        <div class="rounded-2xl border bg-gradient-to-r from-primary/10 via-background to-secondary/10 p-5 shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <p v-if="audience" class="text-xs uppercase tracking-[0.14em] text-primary">{{ audience }}</p>
                    <div v-if="subtitle" class="text-sm text-muted-foreground">{{ subtitle }}</div>
                </div>
                <div class="flex flex-wrap gap-2 text-xs text-muted-foreground">
                    <span class="rounded-full border border-border px-3 py-1">Support</span>
                    <span class="rounded-full border border-border px-3 py-1">Reponse sous 72h</span>
                    <span class="rounded-full border border-border px-3 py-1">Priorite incidents</span>
                </div>
            </div>
        </div>

        <div class="grid gap-4 lg:grid-cols-3">
            <Card v-for="item in items" :key="item.title" class="bg-card/80 shadow-sm">
                <CardHeader class="flex flex-row items-start justify-between gap-3">
                    <CardTitle class="text-base">{{ item.title }}</CardTitle>
                    <span v-if="item.badge" class="rounded-full bg-muted px-2 py-1 text-[11px] text-muted-foreground">{{ item.badge }}</span>
                </CardHeader>
                <CardContent class="space-y-3 text-sm text-muted-foreground">
                    <p>{{ item.description }}</p>
                    <div v-if="item.href">
                        <Button as-child size="sm">
                            <component
                                :is="item.href.startsWith('http') || item.href.startsWith('mailto:') || item.href.startsWith('#') ? 'a' : Link"
                                :href="item.href"
                                class="inline-flex items-center gap-2"
                            >
                                {{ item.actionLabel ?? 'Ouvrir' }}
                            </component>
                        </Button>
                    </div>
                </CardContent>
            </Card>
        </div>

        <Card v-if="extraNote" class="border-dashed bg-card/70">
            <CardContent class="py-4 text-sm text-muted-foreground">
                {{ extraNote }}
            </CardContent>
        </Card>
    </div>
</template>
