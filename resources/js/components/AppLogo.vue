<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
</script>

<template>
    <div class="flex items-center gap-2">
        <div
            class="flex aspect-square size-9 items-center justify-center rounded-xl bg-gradient-to-br from-[#0ef] via-[#7f5bff] to-[#ff4d6d] text-white shadow-[0_10px_30px_rgba(0,0,0,0.25)]"
        >
            <AppLogoIcon class="size-6 fill-current drop-shadow-[0_0_8px_rgba(255,255,255,0.35)]" data-duotone />
        </div>
        <div class="grid text-left text-sm leading-tight">
            <span class="truncate font-semibold tracking-tight text-foreground">Omega Edition</span>
            <span class="text-xs text-muted-foreground">Create. Read. Evolve.</span>
        </div>
    </div>
</template>
