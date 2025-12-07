<script setup lang="ts">
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
import { BarChart3, LayoutGrid, LibraryBig, Wallet } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';
import CreatorNavUser from './CreatorNavUser.vue';

const page = usePage();

const items: NavItem[] = [
    { title: 'Dashboard', href: '/creator', icon: LayoutGrid },
    { title: 'Séries', href: '/creator/series', icon: LibraryBig },
    { title: 'Analytics', href: '/creator/analytics', icon: BarChart3 },
    { title: 'Payouts', href: '/creator/payouts', icon: Wallet },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link href="/creator">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <SidebarGroup class="px-2 py-0">
                <SidebarGroupLabel>Créateur</SidebarGroupLabel>
                <SidebarMenu>
                    <SidebarMenuItem v-for="item in items" :key="item.title">
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
        <CreatorNavUser />
    </SidebarFooter>
</Sidebar>
<slot />
</template>
