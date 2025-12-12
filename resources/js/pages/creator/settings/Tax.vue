<script setup lang="ts">
import CreatorLayout from '@/layouts/CreatorLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useForm } from '@inertiajs/vue3';

type TaxMode = 'self' | 'withheld';

const props = defineProps<{
    taxProfile: {
        mode: TaxMode;
        country: string | null;
        tax_id: string | null;
        tax_rate_bps: number | null;
    } | null;
}>();

const form = useForm({
    mode: (props.taxProfile?.mode as TaxMode) ?? 'self',
    country: props.taxProfile?.country ?? '',
    tax_id: props.taxProfile?.tax_id ?? '',
    tax_rate_bps: props.taxProfile?.tax_rate_bps ?? null,
});
</script>

<template>
    <CreatorLayout
        title="Fiscalité"
        description="Choisissez comment sont gérées vos taxes : autogérées ou retenues par la plateforme."
        :breadcrumbs="[
            { title: 'Paramètres', href: '/creator/settings/profile' },
            { title: 'Fiscalité' },
        ]"
    >
        <div class="space-y-6">
            <Card>
                <CardHeader>
                    <CardTitle>Mode de gestion</CardTitle>
                    <CardDescription>Retenue par la plateforme (optionnel) ou autogérée.</CardDescription>
                </CardHeader>
                <CardContent class="space-y-4">
                    <div class="flex flex-wrap gap-3">
                        <label
                            v-for="option in [
                                { value: 'self', label: 'Autogéré' },
                                { value: 'withheld', label: 'Retenue plateforme' },
                            ]"
                            :key="option.value"
                            class="flex items-center gap-2 rounded-lg border px-3 py-2 text-sm"
                            :class="form.mode === option.value ? 'border-primary/60 bg-primary/5' : 'border-border'"
                        >
                            <input type="radio" class="h-4 w-4" :value="option.value" v-model="form.mode" />
                            <span>{{ option.label }}</span>
                        </label>
                    </div>

                    <div class="grid gap-3 md:grid-cols-2">
                        <div class="space-y-2">
                            <Label for="country">Pays</Label>
                            <Input id="country" v-model="form.country" placeholder="Ex: CG" maxlength="2" />
                        </div>
                        <div class="space-y-2">
                            <Label for="tax_id">N° fiscal (optionnel)</Label>
                            <Input id="tax_id" v-model="form.tax_id" placeholder="NIF / TIN" />
                        </div>
                    </div>

                    <div class="space-y-2">
                        <Label for="tax_rate">Taux retenu (bps)</Label>
                        <Input
                            id="tax_rate"
                            type="number"
                            v-model.number="form.tax_rate_bps"
                            placeholder="Ex: 1000 = 10%"
                            :disabled="form.mode === 'self'"
                        />
                        <p class="text-xs text-muted-foreground">1% = 100 bps. Laisser vide pour taux par défaut.</p>
                    </div>

                    <div class="flex gap-2">
                        <Button :disabled="form.processing" @click="form.put('/creator/payouts/tax-profile')">
                            <span v-if="form.processing" class="mr-1 animate-pulse">…</span>
                            Enregistrer
                        </Button>
                        <Button variant="outline" :disabled="form.processing" @click="form.reset()">Réinitialiser</Button>
                    </div>
                </CardContent>
            </Card>
        </div>
    </CreatorLayout>
</template>
