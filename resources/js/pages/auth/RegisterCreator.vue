<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AuthBase from '@/layouts/AuthLayout.vue';
import { login } from '@/routes';
import { store } from '@/routes/register';
import { Form, Head } from '@inertiajs/vue3';
</script>

<template>
    <AuthBase
        title="Profil creator"
        description="Publie, anime ton audience et pilote tes revenus"
    >
        <Head title="Register - Creator" />

        <Form
            v-bind="store.form()"
            :reset-on-success="['password', 'password_confirmation']"
            v-slot="{ errors, processing }"
            class="flex flex-col gap-6"
        >
            <input type="hidden" name="role" value="creator" />
            <div class="grid gap-6">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="grid gap-2">
                        <Label for="first_name">Prénom</Label>
                        <Input
                            id="first_name"
                            type="text"
                            :tabindex="1"
                            autocomplete="given-name"
                            name="first_name"
                            placeholder="Prénom"
                        />
                        <InputError :message="errors.first_name" />
                    </div>
                    <div class="grid gap-2">
                        <Label for="last_name">Nom</Label>
                        <Input
                            id="last_name"
                            type="text"
                            :tabindex="2"
                            autocomplete="family-name"
                            name="last_name"
                            placeholder="Nom"
                        />
                        <InputError :message="errors.last_name" />
                    </div>
                </div>

                <div class="grid gap-2">
                    <Label for="stage_name">Nom d'artiste</Label>
                    <Input
                        id="stage_name"
                        type="text"
                        required
                        autofocus
                        :tabindex="3"
                        autocomplete="organization"
                        name="stage_name"
                        placeholder="Studio, pseudo ou nom public"
                    />
                    <InputError :message="errors.stage_name" />
                </div>

                <div class="grid gap-2">
                    <Label for="email">Email address</Label>
                    <Input
                        id="email"
                        type="email"
                        required
                        :tabindex="4"
                        autocomplete="email"
                        name="email"
                        placeholder="email@example.com"
                    />
                    <InputError :message="errors.email" />
                </div>

                <div class="grid gap-2">
                    <Label for="password">Password</Label>
                    <Input
                        id="password"
                        type="password"
                        required
                        :tabindex="5"
                        autocomplete="new-password"
                        name="password"
                        placeholder="Password"
                    />
                    <InputError :message="errors.password" />
                </div>

                <div class="grid gap-2">
                    <Label for="password_confirmation">Confirm password</Label>
                    <Input
                        id="password_confirmation"
                        type="password"
                        required
                        :tabindex="6"
                        autocomplete="new-password"
                        name="password_confirmation"
                        placeholder="Confirm password"
                    />
                    <InputError :message="errors.password_confirmation" />
                </div>

                <Button
                    type="submit"
                    class="mt-2 w-full"
                    tabindex="7"
                    :disabled="processing"
                    data-test="register-creator-button"
                >
                    <Spinner v-if="processing" />
                    Lancer mon espace creator
                </Button>
            </div>

            <div class="space-y-2 text-center text-sm text-muted-foreground">
                <div>
                    Deja un compte?
                    <TextLink
                        :href="login()"
                        class="underline underline-offset-4"
                        :tabindex="8"
                        >Log in</TextLink
                    >
                </div>
                <div>
                    Plutot lecteur?&nbsp;
                    <TextLink href="/register/reader" :tabindex="9">
                        Continuer en reader
                    </TextLink>
                </div>
            </div>
        </Form>
    </AuthBase>
</template>
