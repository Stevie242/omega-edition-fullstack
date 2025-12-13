<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { home } from '@/routes';
import { Link } from '@inertiajs/vue3';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div class="relative min-h-svh overflow-hidden bg-gradient-to-b from-[#050711] via-[#0a1224] to-[#01030d]">
        <div class="pointer-events-none absolute inset-0 opacity-80">
            <div class="absolute -left-16 top-8 h-64 w-64 rounded-full bg-gradient-to-br from-cyan-400/20 to-transparent blur-3xl" />
            <div class="absolute right-0 top-0 h-72 w-72 rounded-full bg-gradient-to-bl from-purple-500/18 to-transparent blur-3xl" />
            <div class="absolute bottom-0 left-1/3 h-80 w-80 rounded-full bg-gradient-to-br from-pink-500/20 to-transparent blur-3xl" />
        </div>

        <div class="relative z-10 mx-auto flex min-h-svh max-w-6xl items-center justify-center px-6 py-10">
            <div
                class="w-full max-w-xl rounded-2xl border border-white/10 bg-white/5 p-1 shadow-[0_24px_70px_rgba(0,0,0,0.45)] backdrop-blur"
            >
                <div
                    class="relative rounded-[14px] bg-[#0b1226]/85 p-8 text-white backdrop-blur"
                >
                    <div class="mb-8 flex items-center justify-between gap-3">
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
                        <div class="rounded-full border border-white/10 px-3 py-1 text-[11px] uppercase tracking-[0.28em] text-white/60">
                            Choisis ta voie
                        </div>
                    </div>

                    <div class="mb-8 space-y-2 text-left">
                        <h1 class="text-2xl font-semibold leading-tight text-white">{{ title }}</h1>
                        <p class="text-sm text-white/70">
                            {{ description }}
                        </p>
                    </div>

                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>
