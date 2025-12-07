<script setup lang="ts">
import UserInfo from '@/components/UserInfo.vue';
import {
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
} from '@/components/ui/dropdown-menu';
import { logout } from '@/routes';
import type { User } from '@/types';
import { Link, router } from '@inertiajs/vue3';
import { Lock, LogOut, Monitor, Palette, Settings, ShieldCheck } from 'lucide-vue-next';

interface Props {
    user: User;
}

const handleLogout = () => {
    router.flushAll();
};

defineProps<Props>();
</script>

<template>
    <DropdownMenuLabel class="p-0 font-normal">
        <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
            <UserInfo :user="user" :show-email="true" />
        </div>
    </DropdownMenuLabel>
    <DropdownMenuSeparator />
    <DropdownMenuGroup>
        <DropdownMenuItem :as-child="true">
            <Link class="block w-full" href="/admin/settings/profile" prefetch as="button">
                <Settings class="mr-2 h-4 w-4" />
                Profil admin
            </Link>
        </DropdownMenuItem>
        <DropdownMenuItem :as-child="true">
            <Link class="block w-full" href="/admin/settings/appearance" prefetch as="button">
                <Palette class="mr-2 h-4 w-4" />
                Apparence
            </Link>
        </DropdownMenuItem>
        <DropdownMenuItem :as-child="true">
            <Link class="block w-full" href="/admin/settings/password" prefetch as="button">
                <Lock class="mr-2 h-4 w-4" />
                Mot de passe
            </Link>
        </DropdownMenuItem>
        <DropdownMenuItem :as-child="true">
            <Link class="block w-full" href="/admin/settings/two-factor" prefetch as="button">
                <Monitor class="mr-2 h-4 w-4" />
                Double authentification
            </Link>
        </DropdownMenuItem>
        <DropdownMenuItem :as-child="true">
            <Link class="block w-full" href="/admin/settings/security" prefetch as="button">
                <ShieldCheck class="mr-2 h-4 w-4" />
                Sécurité avancée
            </Link>
        </DropdownMenuItem>
    </DropdownMenuGroup>
    <DropdownMenuSeparator />
    <DropdownMenuItem :as-child="true">
        <Link
            class="block w-full"
            :href="logout()"
            @click="handleLogout"
            as="button"
            data-test="logout-button"
        >
            <LogOut class="mr-2 h-4 w-4" />
            Déconnexion
        </Link>
    </DropdownMenuItem>
</template>
