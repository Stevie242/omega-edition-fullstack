<script setup lang="ts">
import AppLogo from '@/components/AppLogo.vue';
import { Button } from '@/components/ui/button';
import { Sheet, SheetContent, SheetHeader, SheetTitle, SheetTrigger } from '@/components/ui/sheet';
import { useAppearance } from '@/composables/useAppearance';
import { Link, usePage } from '@inertiajs/vue3';
import { Menu, Moon, Sun } from 'lucide-vue-next';

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
const { appearance, updateAppearance } = useAppearance();

const toggleTheme = () => {
    updateAppearance(appearance.value === 'dark' ? 'light' : 'dark');
};

const navLinks = [
    { href: '/', label: 'Accueil' },
    { href: '/catalog', label: 'Catalogue' },
    { href: '/about', label: 'À propos' },
];
</script>

<template>
    <div class="flex min-h-screen flex-col bg-background text-foreground">
        <header class="sticky top-0 z-30 border-b bg-background/80 backdrop-blur supports-[backdrop-filter]:bg-background/70">
            <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-3 sm:px-6">
                <Link href="/" class="flex items-center gap-2">
                    <AppLogo />
                </Link>

                <!-- Desktop nav -->
                <nav class="hidden items-center gap-3 text-sm sm:flex">
                    <Link
                        v-for="item in navLinks"
                        :key="item.href"
                        :href="item.href"
                        class="rounded-full px-3 py-1 hover:bg-muted"
                    >
                        {{ item.label }}
                    </Link>
                    <Button variant="ghost" size="icon" class="rounded-full" @click="toggleTheme" :title="appearance === 'dark' ? 'Passer en clair' : 'Passer en sombre'">
                        <Sun v-if="appearance !== 'dark'" class="h-4 w-4" />
                        <Moon v-else class="h-4 w-4" />
                    </Button>
                    <div class="flex items-center gap-2">
                        <Link href="/login" class="rounded-full px-3 py-1 hover:bg-muted">Connexion</Link>
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

                <!-- Mobile nav -->
                <div class="flex items-center gap-2 sm:hidden">
                    <Button variant="ghost" size="icon" class="rounded-full" @click="toggleTheme" :title="appearance === 'dark' ? 'Passer en clair' : 'Passer en sombre'">
                        <Sun v-if="appearance !== 'dark'" class="h-4 w-4" />
                        <Moon v-else class="h-4 w-4" />
                    </Button>
                    <Sheet>
                        <SheetTrigger as-child>
                            <Button variant="ghost" size="icon" class="rounded-full" aria-label="Ouvrir le menu">
                                <Menu class="h-5 w-5" />
                            </Button>
                        </SheetTrigger>
                        <SheetContent side="right" class="w-72 bg-background/95 backdrop-blur">
                            <SheetHeader class="text-left">
                                <SheetTitle class="text-base font-semibold">Navigation</SheetTitle>
                            </SheetHeader>
                            <div class="mt-4 flex flex-col gap-3 text-sm">
                                <Link
                                    v-for="item in navLinks"
                                    :key="item.href"
                                    :href="item.href"
                                    class="rounded-lg px-3 py-2 hover:bg-muted"
                                >
                                    {{ item.label }}
                                </Link>
                                <div class="h-px bg-border/70"></div>
                                <Link href="/login" class="rounded-lg px-3 py-2 hover:bg-muted">Connexion</Link>
                                <Link href="/register/reader" class="rounded-lg px-3 py-2 hover:bg-muted">Créer un compte</Link>
                                <div class="px-3 text-xs text-muted-foreground">
                                    Créateurs : <Link href="/register/creator" class="text-primary hover:underline">s’inscrire</Link>
                                </div>
                            </div>
                        </SheetContent>
                    </Sheet>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-6xl flex-1 px-4 py-10 sm:px-6 sm:py-14">
            <div v-if="!minimal" class="mb-8 space-y-2">
                <p class="text-xs uppercase tracking-[0.14em] text-primary/70">Omega Edition</p>
                <h1 class="text-3xl font-bold tracking-tight sm:text-4xl">{{ title }}</h1>
                <p class="max-w-3xl text-sm text-muted-foreground">{{ description }}</p>
            </div>
            <slot />
        </main>

        <footer class="border-t bg-muted/30">
            <div class="mx-auto flex max-w-6xl flex-col gap-6 px-4 py-8 sm:px-6 sm:flex-row sm:items-start sm:justify-between">
                <div class="space-y-2">
                    <div class="flex items-center gap-2">
                        <AppLogo />
                        <span class="text-sm font-semibold uppercase tracking-[0.08em]">Omega Edition</span>
                    </div>
                    <p class="text-sm text-muted-foreground max-w-sm">
                        Plateforme futuriste pour lecteurs et créateurs : lecture légale, publication, analytics, soutien aux auteurs.
                    </p>
                </div>
                <div class="grid grid-cols-2 gap-6 text-sm text-muted-foreground sm:grid-cols-3">
                    <div class="space-y-2">
                        <div class="text-foreground font-semibold">Navigation</div>
                        <div class="flex flex-col gap-1">
                            <Link href="/" class="hover:text-primary">Accueil</Link>
                            <Link href="/catalog" class="hover:text-primary">Catalogue</Link>
                            <Link href="/about" class="hover:text-primary">À propos</Link>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="text-foreground font-semibold">Compte</div>
                        <div class="flex flex-col gap-1">
                            <Link href="/login" class="hover:text-primary">Connexion</Link>
                            <Link href="/register/reader" class="hover:text-primary">Lecteur</Link>
                            <Link href="/register/creator" class="hover:text-primary">Créateur</Link>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="text-foreground font-semibold">Légal</div>
                        <div class="flex flex-col gap-1">
                            <Link href="/privacy" class="hover:text-primary">Confidentialité</Link>
                            <Link href="/terms" class="hover:text-primary">Conditions</Link>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>
