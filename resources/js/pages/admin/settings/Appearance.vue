<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { useToast } from 'primevue/usetoast';
import { onMounted, ref, watch } from 'vue';
import { type CurrencyCode } from '@/lib/currency';
import { useForm, usePage } from '@inertiajs/vue3';

type ThemeOption = 'system' | 'light' | 'dark';

const toast = useToast();
const STORAGE_KEY = 'admin.ui.appearance';
const page = usePage();

const theme = ref<ThemeOption>('system');
const currency = ref<CurrencyCode>('XAF');
const form = useForm({
    theme: theme.value,
    currency: currency.value,
    payload: null as Record<string, unknown> | null,
});

const applyTheme = (value: ThemeOption) => {
    const root = document.documentElement;
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const shouldDark = value === 'dark' || (value === 'system' && prefersDark);
    root.classList.toggle('dark', shouldDark);
};

const loadPrefs = () => {
    const pref = (page.props.preference ?? null) as { theme?: ThemeOption; currency?: CurrencyCode } | null;
    if (pref) {
        theme.value = pref.theme ?? theme.value;
        currency.value = pref.currency ?? currency.value;
    } else {
        try {
            const raw = localStorage.getItem(STORAGE_KEY);
            if (raw) {
                const parsed = JSON.parse(raw);
                theme.value = parsed.theme ?? theme.value;
                currency.value = parsed.currency ?? currency.value;
            }
        } catch (e) {
            console.warn('Impossible de charger les préférences d’apparence.', e);
        }
    }
    applyTheme(theme.value);
};

const savePrefs = () => {
    form.theme = theme.value;
    form.currency = currency.value;
    form.payload = null;
    form.transform((data) => ({
        theme: data.theme,
        currency: data.currency,
        data: data.payload,
    })).put('/settings/preferences', {
        preserveScroll: true,
        onSuccess: () => {
            localStorage.setItem(
                STORAGE_KEY,
                JSON.stringify({
                    theme: theme.value,
                    currency: currency.value,
                }),
            );
            applyTheme(theme.value);
            toast.add({ severity: 'success', summary: 'Préférences enregistrées', life: 2200 });
        },
        onError: () => {
            toast.add({ severity: 'error', summary: 'Erreur lors de la sauvegarde', life: 2500 });
        },
    });
};

const resetPrefs = () => {
    theme.value = 'system';
    currency.value = 'XAF';
    savePrefs();
};

onMounted(() => loadPrefs());

watch(
    () => page.props.preference,
    (pref) => {
        if (pref && typeof pref === 'object') {
            theme.value = (pref as any).theme ?? theme.value;
            currency.value = (pref as any).currency ?? currency.value;
            applyTheme(theme.value);
        }
    },
);
</script>

<template>
    <AdminLayout
        title="Apparence"
        description="Thème et préférences d’affichage pour l’interface admin."
        :breadcrumbs="[
            { title: 'Paramètres', href: '/admin/settings/profile' },
            { title: 'Apparence' },
        ]"
    >
        <div class="grid gap-6 lg:grid-cols-[1.4fr_1fr]">
            <Card>
                <CardHeader>
                    <CardTitle>Thème</CardTitle>
                    <CardDescription>Mode clair/sombre ou synchronisation système.</CardDescription>
                </CardHeader>
                <CardContent class="space-y-3">
                    <div class="flex flex-wrap gap-2">
                        <label
                            v-for="option in ['system', 'light', 'dark']"
                            :key="option"
                            class="flex items-center gap-2 rounded-lg border px-3 py-2 text-sm"
                            :class="theme === option ? 'border-primary/60 bg-primary/5' : 'border-border'"
                        >
                            <input type="radio" class="h-4 w-4" :value="option" v-model="theme" />
                            <span class="capitalize">{{ option }}</span>
                        </label>
                    </div>
                </CardContent>
            </Card>

            <Card>
                <CardHeader>
                    <CardTitle>Devise d’affichage</CardTitle>
                    <CardDescription>Choix de la devise pour les montants affichés.</CardDescription>
                </CardHeader>
                <CardContent class="space-y-3">
                    <div class="flex flex-wrap gap-2">
                        <label
                            v-for="option in ['XAF', 'EUR', 'USD']"
                            :key="option"
                            class="flex items-center gap-2 rounded-lg border px-3 py-2 text-sm"
                            :class="currency === option ? 'border-primary/60 bg-primary/5' : 'border-border'"
                        >
                            <input type="radio" class="h-4 w-4" :value="option" v-model="currency" />
                            <span class="uppercase">{{ option }}</span>
                        </label>
                    </div>
                </CardContent>
            </Card>

            <Card class="lg:col-span-2">
                <CardHeader>
                    <CardTitle>Actions</CardTitle>
                    <CardDescription>Enregistrer ou réinitialiser vos préférences locales.</CardDescription>
                </CardHeader>
                <CardContent class="flex flex-wrap items-center gap-3">
                    <Button @click="savePrefs">Enregistrer</Button>
                    <Button variant="outline" @click="resetPrefs">Réinitialiser</Button>
                    <p class="text-xs text-muted-foreground">
                        Vos préférences sont synchronisées sur votre compte et appliquées sur tous vos appareils.
                    </p>
                </CardContent>
            </Card>
        </div>
    </AdminLayout>
</template>
