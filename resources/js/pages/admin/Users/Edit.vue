<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { useForm } from '@inertiajs/vue3';

interface UserItem {
    id: string;
    name: string;
    email: string;
    role: string;
    is_locked: boolean;
    created_at?: string;
    email_verified_at?: string | null;
}

const props = defineProps<{
    user: UserItem;
}>();

const form = useForm({
    lock: !props.user.is_locked,
});
</script>

<template>
    <AdminLayout
        title="Compte utilisateur"
        :description="`Gérer le statut du compte #${user.id}`"
        :breadcrumbs="[{ title: 'Utilisateurs', href: '/admin/users' }, { title: user.name }]"
    >
        <div class="space-y-6">
            <Card class="bg-card/80 shadow-sm">
                <CardHeader>
                    <CardTitle>Détails</CardTitle>
                </CardHeader>
                <CardContent class="space-y-3 text-sm text-muted-foreground">
                    <div class="flex flex-wrap items-center gap-2 text-foreground">
                        <div class="text-base font-semibold">{{ user.name }}</div>
                        <Badge :variant="user.role === 'admin' ? 'default' : 'outline'">{{ user.role }}</Badge>
                        <Badge v-if="user.is_locked" variant="destructive">Verrouillé</Badge>
                        <Badge v-else variant="secondary">Actif</Badge>
                    </div>
                    <div>{{ user.email }}</div>
                    <div class="flex flex-wrap gap-2 text-xs">
                        <span class="rounded-full border border-border px-2 py-1">
                            Créé : {{ user.created_at ? new Date(user.created_at).toLocaleString() : '-' }}
                        </span>
                        <span class="rounded-full border border-border px-2 py-1">
                            Vérifié : {{ user.email_verified_at ? 'Oui' : 'Non' }}
                        </span>
                    </div>
                </CardContent>
            </Card>

            <Card class="bg-card/80 shadow-sm">
                <CardHeader>
                    <CardTitle>Déverrouiller / verrouiller</CardTitle>
                </CardHeader>
                <CardContent class="space-y-3">
                    <p class="text-sm text-muted-foreground">
                        Cette action change uniquement le verrouillage du compte, sans toucher au rôle ni au mot de passe.
                    </p>
                    <div class="flex justify-end">
                        <Button
                            size="sm"
                            :disabled="form.processing"
                            @click="form.put(`/admin/users/${user.id}`)"
                        >
                            {{ user.is_locked ? 'Déverrouiller' : 'Verrouiller' }}
                        </Button>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AdminLayout>
</template>
