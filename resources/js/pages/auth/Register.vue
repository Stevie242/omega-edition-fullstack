<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { Button } from '@/components/ui/button';
import { home, login } from '@/routes';
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Choisis ton role" />

    <div
        class="relative min-h-screen overflow-hidden bg-gradient-to-b from-[#050814] via-[#080e21] to-[#04060d] text-white"
    >
        <div class="pointer-events-none absolute inset-0 opacity-70">
            <div
                class="absolute -left-10 top-10 h-72 w-72 rounded-full bg-gradient-to-br from-emerald-500/20 via-transparent to-transparent blur-3xl"
            />
            <div
                class="absolute bottom-0 left-1/3 h-72 w-72 rounded-full bg-gradient-to-tr from-purple-500/15 via-transparent to-transparent blur-3xl"
            />
            <div
                class="absolute right-0 top-24 h-80 w-80 rounded-full bg-gradient-to-bl from-amber-400/25 via-transparent to-transparent blur-3xl"
            />
        </div>

        <div class="relative z-10 flex min-h-screen flex-col">
            <header class="flex items-center justify-between px-6 py-6 md:px-12">
                <Link
                    :href="home()"
                    class="flex items-center gap-3 text-sm font-semibold uppercase tracking-[0.22em] text-white/80"
                >
                    <span
                        class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/10 ring-1 ring-white/10"
                    >
                        <AppLogoIcon class="h-6 w-6" />
                    </span>
                    <span>Omega Edition</span>
                </Link>
                <Link
                    :href="login()"
                    class="text-sm font-medium text-white/75 transition hover:text-white"
                >
                    Deja un compte ? Connexion
                </Link>
            </header>

            <main class="flex flex-1 items-center">
                <div class="mx-auto flex w-full max-w-6xl flex-col gap-10 px-6 pb-14 md:px-12">
                    <div class="max-w-3xl space-y-4">
                        <p class="text-xs uppercase tracking-[0.32em] text-white/60">
                            Choisis ta voie
                        </p>
                        <h1 class="text-4xl font-semibold leading-tight md:text-5xl">
                            Forge ta destinee sur Omega
                        </h1>
                        <p class="text-base text-white/75 md:text-lg">
                            Deux roles, deux ambiances. Selectionne la facon dont tu veux vivre
                            Omega : explorer en reader ou creer en creator. A toi de jouer.
                        </p>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        <div
                            class="group relative overflow-hidden rounded-2xl border border-white/10 bg-white/5 p-1 shadow-[0_20px_50px_rgba(0,0,0,0.35)]"
                        >
                            <div
                                class="absolute inset-0 bg-gradient-to-br from-emerald-400/12 via-transparent to-emerald-300/18 transition duration-300 group-hover:from-emerald-400/20 group-hover:to-emerald-300/28"
                            />
                            <div
                                class="absolute inset-y-6 left-[46%] w-px bg-gradient-to-b from-transparent via-white/40 to-transparent opacity-40 group-hover:opacity-70"
                            />
                            <div
                                class="relative grid h-full grid-rows-[1fr_auto] gap-6 rounded-[14px] bg-[#0b1226]/80 p-6 backdrop-blur"
                            >
                                <div class="space-y-4">
                                    <div
                                        class="inline-flex items-center gap-2 rounded-full bg-white/5 px-3 py-1 text-[11px] uppercase tracking-[0.26em] text-emerald-100/85 ring-1 ring-emerald-200/30"
                                    >
                                        Reader path
                                    </div>
                                    <h2 class="text-2xl font-semibold">Explorer, collectionner, soutenir</h2>
                                    <p class="text-sm text-white/70">
                                        Pensé pour les lecteurs qui veulent se perdre dans les univers des
                                        creators et soutenir leurs favoris sans friction.
                                    </p>
                                    <ul class="space-y-2 text-sm text-white/75">
                                        <li class="flex items-center gap-2">
                                            <span
                                                class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-400/20 text-emerald-200"
                                            >
                                                ◆
                                            </span>
                                            <span>Lecture fluide et recommandations guidees.</span>
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <span
                                                class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-400/20 text-emerald-200"
                                            >
                                                ◆
                                            </span>
                                            <span>Collection et favoris en un geste.</span>
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <span
                                                class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-400/20 text-emerald-200"
                                            >
                                                ◆
                                            </span>
                                            <span>Support direct des creators sans quitter la lecture.</span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="flex items-center justify-between gap-3">
                                    <div class="text-xs uppercase tracking-[0.2em] text-white/60">
                                        Mode immersion
                                    </div>
                                    <Button
                                        asChild
                                        class="bg-emerald-400 text-black shadow-lg transition duration-200 hover:-translate-y-0.5 hover:bg-emerald-300"
                                    >
                                        <Link href="/register/reader">Je deviens reader</Link>
                                    </Button>
                                </div>
                            </div>
                        </div>

                        <div
                            class="group relative overflow-hidden rounded-2xl border border-white/10 bg-white/5 p-1 shadow-[0_20px_50px_rgba(0,0,0,0.35)]"
                        >
                            <div
                                class="absolute inset-0 bg-gradient-to-br from-amber-300/14 via-transparent to-purple-400/16 transition duration-300 group-hover:from-amber-300/22 group-hover:to-purple-400/24"
                            />
                            <div
                                class="absolute inset-y-6 left-[46%] w-px bg-gradient-to-b from-transparent via-white/40 to-transparent opacity-40 group-hover:opacity-70"
                            />
                            <div
                                class="relative grid h-full grid-rows-[1fr_auto] gap-6 rounded-[14px] bg-[#120d23]/85 p-6 backdrop-blur"
                            >
                                <div class="space-y-4">
                                    <div
                                        class="inline-flex items-center gap-2 rounded-full bg-white/5 px-3 py-1 text-[11px] uppercase tracking-[0.26em] text-amber-100/85 ring-1 ring-amber-200/30"
                                    >
                                        Creator path
                                    </div>
                                    <h2 class="text-2xl font-semibold">Creer, publier, monnayer</h2>
                                    <p class="text-sm text-white/70">
                                        Une voie pour celles et ceux qui veulent partager leurs creations,
                                        piloter leurs analytics et activer leurs fans.
                                    </p>
                                    <ul class="space-y-2 text-sm text-white/75">
                                        <li class="flex items-center gap-2">
                                            <span
                                                class="flex h-6 w-6 items-center justify-center rounded-full bg-amber-300/25 text-amber-100"
                                            >
                                                ◆
                                            </span>
                                            <span>Publication rapide avec mise en avant automatique.</span>
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <span
                                                class="flex h-6 w-6 items-center justify-center rounded-full bg-amber-300/25 text-amber-100"
                                            >
                                                ◆
                                            </span>
                                            <span>Analytics pour suivre ta progression.</span>
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <span
                                                class="flex h-6 w-6 items-center justify-center rounded-full bg-amber-300/25 text-amber-100"
                                            >
                                                ◆
                                            </span>
                                            <span>Outils de monetisation integres.</span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="flex items-center justify-between gap-3">
                                    <div class="text-xs uppercase tracking-[0.2em] text-white/60">
                                        Mode creation
                                    </div>
                                    <Button
                                        asChild
                                        class="bg-white text-black shadow-lg transition duration-200 hover:-translate-y-0.5 hover:bg-amber-100"
                                    >
                                        <Link href="/register/creator">Je deviens creator</Link>
                                    </Button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>
