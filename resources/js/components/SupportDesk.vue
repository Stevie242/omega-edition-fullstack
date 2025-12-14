<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { useForm } from '@inertiajs/vue3';

interface Ticket {
    id: number;
    subject: string;
    message: string;
    status: string;
    resolution_note?: string | null;
    created_at?: string;
    updated_at?: string;
}

const props = defineProps<{
    submitUrl: string;
    tickets: Ticket[];
}>();

const form = useForm({
    subject: '',
    message: '',
});

const statusLabel = (status: string) => {
    if (status === 'open') return 'Ouvert';
    if (status === 'in_progress') return 'En cours';
    if (status === 'resolved') return 'Resolue';
    if (status === 'closed') return 'Ferme';
    return status;
};

const formatDate = (value?: string) => {
    if (!value) return '';
    const date = new Date(value);
    return Number.isNaN(date.getTime()) ? '' : date.toLocaleString();
};

const statusVariant = (status: string) => {
    if (status === 'resolved') return 'secondary';
    if (status === 'in_progress') return 'default';
    if (status === 'closed') return 'outline';
    return 'default';
};

const submitTicket = () => {
    form.post(props.submitUrl, {
        onSuccess: () => form.reset('subject', 'message'),
        preserveScroll: true,
    });
};
</script>

<template>
    <div class="space-y-6">
        <Card class="bg-card/80 shadow-sm">
            <CardHeader>
                <CardTitle>Ouvrir un ticket</CardTitle>
            </CardHeader>
            <CardContent class="space-y-3">
                <div class="grid gap-3 sm:grid-cols-3">
                    <div class="sm:col-span-1 space-y-2">
                        <label for="subject" class="text-sm font-medium text-foreground">Objet</label>
                        <Input
                            id="subject"
                            v-model="form.subject"
                            placeholder="Ex: Problème de publication"
                            :class="form.errors.subject ? 'border-destructive' : ''"
                        />
                        <p v-if="form.errors.subject" class="text-xs text-destructive">{{ form.errors.subject }}</p>
                    </div>
                    <div class="sm:col-span-2 space-y-2">
                        <label for="message" class="text-sm font-medium text-foreground">Description</label>
                        <textarea
                            id="message"
                            v-model="form.message"
                            rows="4"
                            class="w-full rounded-md border bg-background px-3 py-2 text-sm outline-none ring-0 transition focus:border-primary focus:ring-1 focus:ring-primary"
                            placeholder="Décrivez le problème, incluez les IDs ou captures si possible."
                        ></textarea>
                        <p v-if="form.errors.message" class="text-xs text-destructive">{{ form.errors.message }}</p>
                    </div>
                </div>
                <div class="flex justify-end gap-2">
                    <Button variant="ghost" size="sm" type="button" @click="form.reset()">Effacer</Button>
                    <Button size="sm" type="button" :disabled="form.processing" @click="submitTicket">Envoyer le ticket</Button>
                </div>
            </CardContent>
        </Card>

        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <div class="text-sm text-muted-foreground">Historique de vos tickets</div>
                <Badge variant="secondary" class="text-[11px]">{{ tickets.length }} ticket(s)</Badge>
            </div>
            <div v-if="tickets.length" class="grid gap-3 lg:grid-cols-2">
                <Card v-for="ticket in tickets" :key="ticket.id" class="bg-card/70 shadow-sm">
                    <CardHeader class="flex flex-row items-start justify-between gap-3">
                        <div class="space-y-1">
                            <CardTitle class="text-base">{{ ticket.subject }}</CardTitle>
                            <div class="text-xs text-muted-foreground">{{ formatDate(ticket.created_at) }}</div>
                        </div>
                        <Badge :variant="statusVariant(ticket.status)">{{ statusLabel(ticket.status) }}</Badge>
                    </CardHeader>
                    <CardContent class="space-y-3 text-sm text-muted-foreground">
                        <p class="whitespace-pre-line">{{ ticket.message }}</p>
                        <div v-if="ticket.resolution_note" class="rounded-md border border-primary/20 bg-primary/5 p-3 text-xs text-foreground">
                            <div class="font-semibold text-primary">Solution</div>
                            <p class="text-muted-foreground whitespace-pre-line">{{ ticket.resolution_note }}</p>
                        </div>
                    </CardContent>
                </Card>
            </div>
            <Card v-else class="border-dashed bg-card/70">
                <CardContent class="py-8 text-center text-sm text-muted-foreground">
                    Aucun ticket pour le moment. Ouvrez-en un via le formulaire ci-dessus.
                </CardContent>
            </Card>
        </div>
    </div>
</template>
