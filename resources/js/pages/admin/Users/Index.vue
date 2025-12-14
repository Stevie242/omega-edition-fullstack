<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';

interface UserItem {
    id: string;
    name: string;
    email: string;
    role: string;
    is_locked: boolean;
    created_at?: string;
    email_verified_at?: string | null;
}

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

const props = defineProps<{
    users: {
        data: UserItem[];
        links: PaginationLink[];
    };
    counts: Record<string, number>;
    filters: {
        role?: string;
        search?: string;
    };
}>();

const form = useForm({
    name: '',
    email: '',
    password: '',
});

const applyRoleFilter = (role: string) => {
    const params = new URLSearchParams(window.location.search);
    if (role) params.set('role', role);
    else params.delete('role');
    const url = `${window.location.pathname}?${params.toString()}`;
    window.location.href = url;
};

const submitSearch = (value: string) => {
    const params = new URLSearchParams(window.location.search);
    if (value) params.set('search', value);
    else params.delete('search');
    const url = `${window.location.pathname}?${params.toString()}`;
    window.location.href = url;
};

const paginationLabel = (label: string) => {
    if (label.includes('pagination.previous') || label.includes('«')) return 'Précédent';
    if (label.includes('pagination.next') || label.includes('»')) return 'Suivant';
    return label;
};
</script>

<template>
    <AdminLayout
        title="Utilisateurs"
        description="Créer des admins et déverrouiller les comptes si besoin."
        :breadcrumbs="[{ title: 'Utilisateurs' }]"
    >
        <div class="space-y-6">
            <Card class="bg-card/80 shadow-sm">
                <CardHeader>
                    <CardTitle>Créer un administrateur</CardTitle>
                </CardHeader>
                <CardContent class="space-y-3">
                    <div class="grid gap-3 md:grid-cols-3">
                        <div class="space-y-1.5">
                            <label class="text-sm font-medium text-foreground">Nom</label>
                            <Input v-model="form.name" placeholder="Nom complet" :class="form.errors.name ? 'border-destructive' : ''" />
                            <p v-if="form.errors.name" class="text-xs text-destructive">{{ form.errors.name }}</p>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-sm font-medium text-foreground">Email</label>
                            <Input v-model="form.email" type="email" placeholder="admin@exemple.com" :class="form.errors.email ? 'border-destructive' : ''" />
                            <p v-if="form.errors.email" class="text-xs text-destructive">{{ form.errors.email }}</p>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-sm font-medium text-foreground">Mot de passe</label>
                            <Input v-model="form.password" type="password" placeholder="Mot de passe" :class="form.errors.password ? 'border-destructive' : ''" />
                            <p v-if="form.errors.password" class="text-xs text-destructive">{{ form.errors.password }}</p>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <Button size="sm" :disabled="form.processing" @click="form.post('/admin/users')">Créer l’admin</Button>
                    </div>
                </CardContent>
            </Card>

            <Card class="bg-card/80 shadow-sm">
                <CardHeader>
                    <CardTitle>Liste des utilisateurs</CardTitle>
                </CardHeader>
                <CardContent class="space-y-3 overflow-x-auto">
                    <div class="flex flex-wrap items-center gap-2 text-xs text-muted-foreground">
                        <Badge variant="secondary">Total {{ counts.total ?? 0 }}</Badge>
                        <Badge>Admins {{ counts.admin ?? 0 }}</Badge>
                        <Badge variant="outline">Créateurs {{ counts.creator ?? 0 }}</Badge>
                        <Badge variant="outline">Lecteurs {{ counts.reader ?? 0 }}</Badge>
                        <Badge variant="destructive">Verrouillés {{ counts.locked ?? 0 }}</Badge>
                        <div class="ml-auto flex flex-wrap items-center gap-2">
                            <Input
                                :value="filters.search || ''"
                                class="h-8 w-48 text-xs"
                                placeholder="Nom ou email"
                                @keyup.enter="submitSearch(($event.target as HTMLInputElement).value)"
                            />
                            <Button size="sm" variant="ghost" @click="submitSearch('')">Reset</Button>
                            <select
                                :value="filters.role || ''"
                                class="rounded-md border bg-background px-2 py-1 text-sm"
                                @change="applyRoleFilter(($event.target as HTMLSelectElement).value)"
                            >
                                <option value="">Tous</option>
                                <option value="admin">Admin</option>
                                <option value="creator">Créateur</option>
                                <option value="reader">Lecteur</option>
                            </select>
                        </div>
                    </div>
                    <table class="w-full text-sm">
                        <thead class="text-left text-xs text-muted-foreground">
                            <tr class="border-b">
                                <th class="py-2 pr-3">Utilisateur</th>
                                <th class="py-2 pr-3">Rôle</th>
                                <th class="py-2 pr-3">Statut</th>
                                <th class="py-2 pr-3">Création</th>
                                <th class="py-2 pr-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="user in users.data" :key="user.id" class="border-b align-middle">
                                <td class="py-3 pr-3">
                                    <div class="font-semibold text-foreground">{{ user.name }}</div>
                                    <div class="text-xs text-muted-foreground">{{ user.email }}</div>
                                </td>
                                <td class="py-3 pr-3">
                                    <Badge :variant="user.role === 'admin' ? 'default' : 'outline'">{{ user.role }}</Badge>
                                </td>
                                <td class="py-3 pr-3 text-xs text-muted-foreground">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span v-if="user.email_verified_at" class="rounded-full bg-secondary/20 px-2 py-1">Vérifié</span>
                                        <span v-else class="rounded-full bg-muted px-2 py-1">Non vérifié</span>
                                        <span v-if="user.is_locked" class="rounded-full bg-destructive/10 px-2 py-1 text-destructive">Verrouillé</span>
                                    </div>
                                </td>
                                <td class="py-3 pr-3 text-xs text-muted-foreground">
                                    {{ user.created_at ? new Date(user.created_at).toLocaleDateString() : '' }}
                                </td>
                                <td class="py-3 pr-3">
                                    <Button as-child size="sm" variant="outline">
                                        <Link :href="`/admin/users/${user.id}/edit`">Voir / Déverrouiller</Link>
                                    </Button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="flex flex-wrap gap-2">
                        <Button
                            v-for="link in users.links"
                            :key="link.label"
                            :variant="link.active ? 'default' : 'outline'"
                            :disabled="!link.url"
                            size="sm"
                            class="rounded-full"
                            @click="link.url && (window.location.href = link.url)"
                        >
                            <span>{{ paginationLabel(link.label) }}</span>
                        </Button>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AdminLayout>
</template>
