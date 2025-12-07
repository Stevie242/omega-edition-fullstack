<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';

interface Breadcrumb {
    title: string;
    href?: string;
}

interface Props {
    title?: string;
    description?: string;
    breadcrumbs?: Breadcrumb[];
}

const props = withDefaults(defineProps<Props>(), {
    title: 'Espace créateur',
    description: '',
    breadcrumbs: () => [],
});
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-4 py-6 lg:py-8">
            <div class="space-y-1">
                <p class="text-xs uppercase text-muted-foreground tracking-wide">Créateurs</p>
                <h1 class="text-2xl font-semibold leading-tight">{{ props.title }}</h1>
                <p v-if="props.description" class="text-sm text-muted-foreground">
                    {{ props.description }}
                </p>
            </div>
            <slot />
        </div>
    </AppLayout>
</template>
