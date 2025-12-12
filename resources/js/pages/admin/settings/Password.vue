<script setup lang="ts">
import PasswordController from '@/actions/App/Http/Controllers/Settings/PasswordController';
import InputError from '@/components/InputError.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { Form } from '@inertiajs/vue3';
import { useToast } from 'primevue/usetoast';

const toast = useToast();
</script>

<template>
    <AdminLayout
        title="Sécurité - Mot de passe"
        description="Mettre à jour votre mot de passe administrateur."
        :breadcrumbs="[
            { title: 'Paramètres', href: '/admin/settings/profile' },
            { title: 'Mot de passe' },
        ]"
    >
        <div class="space-y-6">
            <HeadingSmall
                title="Mettre à jour le mot de passe"
                description="Utilisez un mot de passe long et unique pour protéger l’accès admin."
            />

            <Form
                v-bind="PasswordController.update.form()"
                :options="{ preserveScroll: true }"
                reset-on-success
                :reset-on-error="['password', 'password_confirmation', 'current_password']"
                class="space-y-6 rounded-lg border bg-card p-6"
                @success="toast.add({ severity: 'success', summary: 'Mot de passe mis à jour', life: 2500 })"
                v-slot="{ errors, processing }"
            >
                <div class="grid gap-2">
                    <Label for="current_password">Mot de passe actuel</Label>
                    <Input
                        id="current_password"
                        name="current_password"
                        type="password"
                        autocomplete="current-password"
                        placeholder="Mot de passe actuel"
                    />
                    <InputError :message="errors.current_password" />
                </div>

                <div class="grid gap-2">
                    <Label for="password">Nouveau mot de passe</Label>
                    <Input
                        id="password"
                        name="password"
                        type="password"
                        autocomplete="new-password"
                        placeholder="Nouveau mot de passe"
                    />
                    <InputError :message="errors.password" />
                </div>

                <div class="grid gap-2">
                    <Label for="password_confirmation">Confirmer le mot de passe</Label>
                    <Input
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        autocomplete="new-password"
                        placeholder="Confirmer le mot de passe"
                    />
                    <InputError :message="errors.password_confirmation" />
                </div>

                <div class="flex items-center gap-4">
                    <Button :disabled="processing">Sauvegarder</Button>
                </div>
            </Form>
        </div>
    </AdminLayout>
</template>
