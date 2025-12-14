<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { router } from '@inertiajs/vue3';
import { computed, reactive } from 'vue';

interface Ticket {
    id: number;
    subject: string;
    message: string;
    status: string;
    resolution_note?: string | null;
    created_at?: string;
    updated_at?: string;
    user?: {
        id: string;
        name?: string;
        email?: string;
        role?: string;
    };
}

const props = defineProps<{
    tickets: Ticket[];
    statusOptions: string[];
}>();

const edits = reactive<Record<number, { status: string; resolution_note: string }>>(
    Object.fromEntries(
        (props.tickets || []).map((t) => [
            t.id,
            {
                status: t.status,
                resolution_note: t.resolution_note ?? '',
            },
        ]),
    ),
);

const statusLabel = (status: string) => {
    if (status === 'open') return 'Ouvert';
    if (status === 'in_progress') return 'En cours';
    if (status === 'resolved') return 'Résolu';
    if (status === 'closed') return 'Fermé';
    return status;
};

const statusVariant = (status: string) => {
    if (status === 'resolved') return 'secondary';
    if (status === 'in_progress') return 'default';
    if (status === 'closed') return 'outline';
    return 'default';
};

const formatDate = (value?: string) => {
    if (!value) return '';
    const date = new Date(value);
    return Number.isNaN(date.getTime()) ? '' : date.toLocaleString();
};

const counts = computed(() => {
    const total = props.tickets.length;
    const open = props.tickets.filter((t) => t.status === 'open').length;
    const inProgress = props.tickets.filter((t) => t.status === 'in_progress').length;
    const resolved = props.tickets.filter((t) => t.status === 'resolved').length;
    const closed = props.tickets.filter((t) => t.status === 'closed').length;
    return { total, open, inProgress, resolved, closed };
});

const updateTicket = (id: number) => {
    const payload = edits[id];
    router.put(`/admin/support/${id}`, payload, { preserveScroll: true });
};
</script>

<template>
    <AdminLayout
        title="Support"
        description="Suivi des tickets lecteurs/créateurs, statut et résolution."
        :breadcrumbs="[{ title: 'Support' }]"
    >
        <div class="space-y-6">
            <div class="grid gap-3 sm:grid-cols-4">
                <Card class="bg-card/80 shadow-sm">
                    <CardContent class="py-4 text-sm text-muted-foreground">
                        Total : <span class="font-semibold text-foreground">{{ counts.total }}</span>
                    </CardContent>
                </Card>
                <Card class="bg-card/80 shadow-sm">
                    <CardContent class="py-4 text-sm text-muted-foreground">
                        Ouverts : <span class="font-semibold text-foreground">{{ counts.open }}</span>
                    </CardContent>
                </Card>
                <Card class="bg-card/80 shadow-sm">
                    <CardContent class="py-4 text-sm text-muted-foreground">
                        En cours : <span class="font-semibold text-foreground">{{ counts.inProgress }}</span>
                    </CardContent>
                </Card>
                <Card class="bg-card/80 shadow-sm">
                    <CardContent class="py-4 text-sm text-muted-foreground">
                        Résolus/fermés : <span class="font-semibold text-foreground">{{ counts.resolved + counts.closed }}</span>
                    </CardContent>
                </Card>
            </div>

            <Card class="bg-card/80 shadow-sm">
                <CardHeader>
                    <CardTitle>Tickets</CardTitle>
                </CardHeader>
                <CardContent class="space-y-4">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="text-left text-xs text-muted-foreground">
                                <tr class="border-b">
                                    <th class="py-2 pr-3">Sujet</th>
                                    <th class="py-2 pr-3">Auteur</th>
                                    <th class="py-2 pr-3">Statut</th>
                                    <th class="py-2 pr-3">Dernière mise à jour</th>
                                    <th class="py-2 pr-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="ticket in tickets" :key="ticket.id" class="border-b align-top">
                                    <td class="py-3 pr-3">
                                        <div class="font-semibold text-foreground">{{ ticket.subject }}</div>
                                        <div class="mt-1 text-xs text-muted-foreground whitespace-pre-line">
                                            {{ ticket.message }}
                                        </div>
                                    </td>
                                    <td class="py-3 pr-3 text-xs text-muted-foreground">
                                        <div class="font-medium text-foreground">{{ ticket.user?.name ?? 'Utilisateur' }}</div>
                                        <div>{{ ticket.user?.email }}</div>
                                        <div class="mt-1 rounded-full border border-border px-2 py-0.5 text-[11px] uppercase tracking-wide">
                                            {{ ticket.user?.role ?? ticket.role }}
                                        </div>
                                    </td>
                                    <td class="py-3 pr-3 text-xs">
                                        <Badge :variant="statusVariant(ticket.status)">{{ statusLabel(ticket.status) }}</Badge>
                                        <div class="mt-1 text-muted-foreground">
                                            Créé : {{ formatDate(ticket.created_at) }}
                                        </div>
                                    </td>
                                    <td class="py-3 pr-3 text-xs text-muted-foreground">
                                        {{ formatDate(ticket.updated_at) }}
                                    </td>
                                    <td class="py-3 pr-3">
                                        <div class="space-y-2">
                                            <label class="text-xs text-muted-foreground">Statut</label>
                                            <select
                                                v-model="edits[ticket.id].status"
                                                class="w-full rounded-md border bg-background px-2 py-1 text-sm"
                                            >
                                                <option v-for="status in statusOptions" :key="status" :value="status">
                                                    {{ statusLabel(status) }}
                                                </option>
                                            </select>
                                            <label class="text-xs text-muted-foreground">Note de résolution</label>
                                            <textarea
                                                v-model="edits[ticket.id].resolution_note"
                                                rows="3"
                                                class="w-full rounded-md border bg-background px-2 py-1 text-sm outline-none focus:border-primary focus:ring-1 focus:ring-primary"
                                                placeholder="Résumé de la solution apportée"
                                            ></textarea>
                                            <div class="flex justify-end">
                                                <Button size="sm" @click="updateTicket(ticket.id)">Mettre à jour</Button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AdminLayout>
</template>
