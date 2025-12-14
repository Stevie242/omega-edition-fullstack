<script setup lang="ts">
import AppLogo from '@/components/AppLogo.vue';
import { Link, usePage } from '@inertiajs/vue3';

withDefaults(
    defineProps<{
        title?: string;
        description?: string;
        minimal?: boolean;
    }>(),
    {
        title: 'Omega Edition',
        description: '',
        minimal: false,
    },
);

const page = usePage();
</script>

<template>
    <div class="min-h-screen bg-background text-foreground">
        <header class="sticky top-0 z-30 border-b bg-background/80 backdrop-blur">
            <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-3 sm:px-6">
                <div class="flex items-center gap-2">
                    <Link href="/" class="flex items-center gap-2">
                        <AppLogo />
                        <span class="text-sm font-semibold uppercase tracking-[0.08em]">Omega Edition</span>
                    </Link>
                </div>
                <nav class="flex items-center gap-3 text-sm">
                    <Link href="/catalog" class="hover:text-primary">Catalogue</Link>
                    <Link href="/about" class="hover:text-primary">À propos</Link>
                    <Link href="/privacy" class="hover:text-primary">Confidentialité</Link>
                    <Link href="/terms" class="hover:text-primary">Conditions</Link>
                    <div class="hidden items-center gap-2 sm:flex">
                        <Link href="/login" class="rounded-full px-3 py-1 text-sm hover:bg-muted">Connexion</Link>
                        <Link
                            v-if="!page.props.auth?.user"
                            href="/register"
                            class="rounded-full bg-primary px-3 py-1 text-sm font-semibold text-primary-foreground hover:opacity-90"
                        >
                            Créer un compte
                        </Link>
                        <Link
                            v-else
                            :href="page.props.auth.user.role === 'creator' ? '/creator' : (page.props.auth.user.role === 'admin' ? '/admin' : '/reader')"
                            class="rounded-full bg-primary px-3 py-1 text-sm font-semibold text-primary-foreground hover:opacity-90"
                        >
                            Tableau de bord
                        </Link>
                    </div>
                </nav>
            </div>
        </header>

        <main class="mx-auto max-w-6xl px-4 py-10 sm:px-6 sm:py-14">
            <div v-if="!minimal" class="mb-8 space-y-2">
                <p class="text-xs uppercase tracking-[0.14em] text-primary/70">Omega Edition</p>
                <h1 class="text-3xl font-bold tracking-tight sm:text-4xl">{{ title }}</h1>
                <p class="max-w-3xl text-sm text-muted-foreground">{{ description }}</p>
            </div>
            <slot />
        </main>
    </div>
</template>
