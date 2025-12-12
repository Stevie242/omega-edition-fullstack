<script setup lang="ts">
import PasswordController from '@/actions/App/Http/Controllers/Settings/PasswordController';
import InputError from '@/components/InputError.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import ReaderLayout from '@/layouts/ReaderLayout.vue';
import { Form } from '@inertiajs/vue3';
</script>

<template>
    <ReaderLayout
        title="Sécurité - Mot de passe"
        description="Mettre à jour votre mot de passe lecteur."
        :breadcrumbs="[
            { title: 'Paramètres', href: '/reader/settings/profile' },
            { title: 'Mot de passe' },
        ]"
    >
        <div class="space-y-6">
            <HeadingSmall
                title="Mettre à jour le mot de passe"
                description="Utilisez un mot de passe long et unique pour protéger votre compte lecteur."
            />

            <Form
                v-bind="PasswordController.update.form()"
                :options="{ preserveScroll: true }"
                reset-on-success
                :reset-on-error="['password', 'password_confirmation', 'current_password']"
                class="space-y-6 rounded-lg border bg-card p-6"
                v-slot="{ errors, processing, recentlySuccessful }"
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
                    <Transition
                        enter-active-class="transition ease-in-out"
                        enter-from-class="opacity-0"
                        leave-active-class="transition ease-in-out"
                        leave-to-class="opacity-0"
                    >
                        <p v-show="recentlySuccessful" class="text-sm text-green-600">
                            Mot de passe mis à jour.
                        </p>
                    </Transition>
                </div>
            </Form>
        </div>
    </ReaderLayout>
</template>
