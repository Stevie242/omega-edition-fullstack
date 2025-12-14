<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
}

defineProps<Props>();
</script>

<template>
    <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 64 64"
        :class="className"
        v-bind="$attrs"
    >
        <defs>
            <linearGradient id="omega-grad" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" stop-color="var(--omega-start, #0ef)" />
                <stop offset="50%" stop-color="var(--omega-mid, #7f5bff)" />
                <stop offset="100%" stop-color="var(--omega-end, #ff4d6d)" />
            </linearGradient>
        </defs>
        <g fill="currentColor">
            <path
                d="M32 6c-11.6 0-21 9.4-21 21 0 8.2 4.7 15.3 11.7 18.8L16 58h7.8l2.8-6h11l2.8 6H48l-6.7-12.2C51.3 42.3 55 35.2 55 27c0-11.6-9.4-21-21-21Zm0 8c7.2 0 13 5.8 13 13s-5.8 13-13 13-13-5.8-13-13 5.8-13 13-13Z"
                fill-rule="evenodd"
                clip-rule="evenodd"
                :fill="$attrs?.['data-duotone'] ? 'url(#omega-grad)' : 'currentColor'"
            />
            <path
                d="M26 30h12v4H26z"
                opacity="0.85"
            />
        </g>
    </svg>
</template>
