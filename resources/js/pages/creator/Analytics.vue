<script setup lang="ts">
import CreatorLayout from '@/layouts/CreatorLayout.vue';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    BarElement,
    Title,
    Tooltip,
    Legend,
} from 'chart.js';
import { Line, Bar } from 'vue-chartjs';

ChartJS.register(
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    BarElement,
    Title,
    Tooltip,
    Legend,
);

type ReadsByMonth = { year_month: string; views: number };
type CompletionBucket = { bucket: number; views: number };
type TopSerie = { series_id: string | null; views: number };

const props = defineProps<{
    readsByMonth: ReadsByMonth[];
    completionBuckets: CompletionBucket[];
    topSeries: TopSerie[];
}>();

const lineData = computed(() => {
    const labels = props.readsByMonth.map((r) => r.year_month);
    const data = props.readsByMonth.map((r) => r.views);
    return {
        labels,
        datasets: [
            {
                label: 'Lectures',
                data,
                borderColor: '#4f46e5',
                backgroundColor: 'rgba(79,70,229,0.1)',
                tension: 0.3,
                fill: true,
            },
        ],
    };
});

const completionData = computed(() => {
    const labels = ['<50%', '50-89%', '90%+'];
    const map = { 0: 0, 50: 0, 90: 0 };
    props.completionBuckets.forEach((b) => {
        const bucket = b.bucket === 90 ? 90 : (b.bucket === 50 ? 50 : 0);
        map[bucket as 0 | 50 | 90] = b.views;
    });
    return {
        labels,
        datasets: [
            {
                label: 'Vues',
                data: [map[0], map[50], map[90]],
                backgroundColor: ['#cbd5e1', '#93c5fd', '#22d3ee'],
            },
        ],
    };
});

const topSeriesData = computed(() => {
    const labels = props.topSeries.map((s) => s.series_id ?? 'Série');
    const data = props.topSeries.map((s) => s.views);
    return {
        labels,
        datasets: [
            {
                label: 'Lectures',
                data,
                backgroundColor: '#4ade80',
            },
        ],
    };
});

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: true,
            position: 'bottom' as const,
        },
    },
    scales: {
        y: {
            beginAtZero: true,
            ticks: { precision: 0 },
        },
    },
};
</script>

<template>
    <CreatorLayout
        title="Analytics créateur"
        description="Vues, complétion et top séries."
    >
        <div class="grid gap-6 lg:grid-cols-2">
            <Card class="lg:col-span-2">
                <CardHeader>
                    <CardTitle>Lectures par mois</CardTitle>
                    <CardDescription>Tendance des vues validées par mois.</CardDescription>
                </CardHeader>
                <CardContent class="h-[320px]">
                    <Line :data="lineData" :options="chartOptions" />
                </CardContent>
            </Card>

            <Card>
                <CardHeader>
                    <CardTitle>Complétion des chapitres</CardTitle>
                    <CardDescription>Répartition par niveau de lecture.</CardDescription>
                </CardHeader>
                <CardContent class="h-[280px]">
                    <Bar :data="completionData" :options="chartOptions" />
                </CardContent>
            </Card>

            <Card>
                <CardHeader>
                    <CardTitle>Top séries</CardTitle>
                    <CardDescription>Séries les plus lues.</CardDescription>
                </CardHeader>
                <CardContent class="h-[280px]">
                    <Bar :data="topSeriesData" :options="chartOptions" />
                </CardContent>
            </Card>
        </div>
    </CreatorLayout>
</template>
