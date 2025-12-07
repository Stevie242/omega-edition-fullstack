<script setup lang="ts">
import AppContent from '@/components/AppContent.vue';
import AppShell from '@/components/AppShell.vue';
import AppSidebarHeader from '@/components/AppSidebarHeader.vue';
import CreatorSidebar from '@/components/CreatorSidebar.vue';

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
    <AppShell variant="sidebar">
        <CreatorSidebar />
        <AppContent variant="sidebar" class="overflow-x-hidden">
            <AppSidebarHeader :breadcrumbs="breadcrumbs" />
            <div class="space-y-4 py-6 lg:py-8">
                <div class="space-y-1 px-6 md:px-4">
                    <p class="text-xs uppercase text-muted-foreground tracking-wide">Créateurs</p>
                    <h1 class="text-2xl font-semibold leading-tight">{{ props.title }}</h1>
                    <p v-if="props.description" class="text-sm text-muted-foreground">
                        {{ props.description }}
                    </p>
                </div>
                <div class="px-6 md:px-4">
                    <slot />
                </div>
            </div>
        </AppContent>
    </AppShell>
</template>
