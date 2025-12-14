<script setup lang="ts">
import AppContent from '@/components/AppContent.vue';
import AppShell from '@/components/AppShell.vue';
import AppSidebarHeader from '@/components/AppSidebarHeader.vue';
import ReaderNavUser from '@/components/ReaderNavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarGroup,
    SidebarGroupLabel,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { urlIsActive } from '@/lib/utils';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { Heart, History, LayoutGrid, Sparkles, User, BadgeCheck, LifeBuoy } from 'lucide-vue-next';
import AppLogo from '@/components/AppLogo.vue';

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
    title: 'Espace lecteur',
    description: '',
    breadcrumbs: () => [],
});

const page = usePage();

const mainNavItems: NavItem[] = [
    { title: 'Accueil', href: '/reader', icon: LayoutGrid },
    { title: 'Catalogue', href: '/reader/series', icon: Sparkles },
    { title: 'Favoris', href: '/reader/favorites', icon: Heart },
    { title: 'Historique', href: '/reader/history', icon: History },
    { title: 'Abonnement', href: '/reader/subscription', icon: BadgeCheck },
    { title: 'Support', href: '/reader/support', icon: LifeBuoy },
];

const profileNavItems: NavItem[] = [
    { title: 'Profil', href: '/reader/settings/profile', icon: User },
];
</script>

<template>
    <AppShell variant="sidebar">
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" as-child>
                            <Link href="/reader">
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                <SidebarGroup class="px-2 py-0">
                    <SidebarGroupLabel>Lecteur</SidebarGroupLabel>
                    <SidebarMenu>
                        <SidebarMenuItem v-for="item in mainNavItems" :key="item.title">
                            <SidebarMenuButton
                                as-child
                                :is-active="urlIsActive(item.href, page.url)"
                                :tooltip="item.title"
                            >
                                <Link :href="item.href">
                                    <component :is="item.icon" />
                                    <span>{{ item.title }}</span>
                                </Link>
                            </SidebarMenuButton>
                        </SidebarMenuItem>
                    </SidebarMenu>
                </SidebarGroup>

                <SidebarGroup class="px-2 py-2">
                    <SidebarGroupLabel>Profil</SidebarGroupLabel>
                    <SidebarMenu>
                        <SidebarMenuItem v-for="item in profileNavItems" :key="item.title">
                            <SidebarMenuButton
                                as-child
                                :is-active="urlIsActive(item.href, page.url)"
                                :tooltip="item.title"
                            >
                                <Link :href="item.href">
                                    <component :is="item.icon" />
                                    <span>{{ item.title }}</span>
                                </Link>
                            </SidebarMenuButton>
                        </SidebarMenuItem>
                    </SidebarMenu>
                </SidebarGroup>
            </SidebarContent>

            <SidebarFooter>
                <ReaderNavUser />
            </SidebarFooter>
        </Sidebar>

        <AppContent variant="sidebar" class="overflow-x-hidden">
            <AppSidebarHeader :breadcrumbs="breadcrumbs" />
            <div class="space-y-4 py-6 lg:py-8">
                <div class="space-y-1 px-6 md:px-4">
                    <p class="text-xs uppercase text-muted-foreground tracking-wide">Lecteurs</p>
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
