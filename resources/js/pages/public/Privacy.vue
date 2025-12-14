<script setup lang="ts">
import PublicLayout from '@/layouts/PublicLayout.vue';
</script>

<template>
    <PublicLayout
        title="Politique de confidentialite"
        description="Synthese claire des donnees collectees, de leur usage et de vos droits sur Omega Edition."
    >
        <div class="space-y-6">
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="rounded-xl border bg-card p-4 shadow-sm">
                    <div class="text-xs uppercase tracking-[0.14em] text-primary">En bref</div>
                    <div class="mt-2 text-sm text-muted-foreground">
                        Donnees minimales pour le compte et le service. Aucun commerce de donnees. Prestataires sous contrat uniquement.
                    </div>
                </div>
                <div class="rounded-xl border bg-card p-4 shadow-sm">
                    <div class="text-xs uppercase tracking-[0.14em] text-primary">Contact</div>
                    <div class="mt-2 text-sm text-muted-foreground">
                        support@omega-edition.com ou Tableau de bord > Support. Reponse sous 72h ouvrables.
                    </div>
                </div>
            </div>

            <div class="grid gap-4 lg:grid-cols-2">
                <div class="rounded-xl border bg-card/80 p-5 shadow-sm">
                    <div class="mb-3 flex items-center justify-between">
                        <h3 class="text-base font-semibold">Collecte</h3>
                        <span class="rounded-full bg-muted px-3 py-1 text-xs text-muted-foreground">Minimal</span>
                    </div>
                    <p class="text-sm text-muted-foreground">
                        Compte (email, nom), preferences de lecture, telemetry technique anonymisee pour ameliorer l experience. Lectures publiques non rattachees a un profil.
                    </p>
                </div>
                <div class="rounded-xl border bg-card/80 p-5 shadow-sm">
                    <div class="mb-3 flex items-center justify-between">
                        <h3 class="text-base font-semibold">Usage</h3>
                        <span class="rounded-full bg-primary/10 px-3 py-1 text-xs text-primary">Service</span>
                    </div>
                    <p class="text-sm text-muted-foreground">
                        Authentification, notifications, statistiques agregees, facturation, moderation et securite de la plateforme.
                    </p>
                </div>
                <div class="rounded-xl border bg-card/80 p-5 shadow-sm">
                    <div class="mb-3 flex items-center justify-between">
                        <h3 class="text-base font-semibold">Partage</h3>
                        <span class="rounded-full bg-secondary/20 px-3 py-1 text-xs text-secondary-foreground">Limite</span>
                    </div>
                    <p class="text-sm text-muted-foreground">
                        Pas de revente. Prestataires indispensables (paiement, stockage, emailing) sous clauses de sous-traitance et de protection.
                    </p>
                </div>
                <div class="rounded-xl border bg-card/80 p-5 shadow-sm">
                    <div class="mb-3 flex items-center justify-between">
                        <h3 class="text-base font-semibold">Conservation</h3>
                        <span class="rounded-full bg-muted px-3 py-1 text-xs text-muted-foreground">Duree utile</span>
                    </div>
                    <p class="text-sm text-muted-foreground">
                        Donnees conservees le temps necessaire au service ou jusqu a votre demande de suppression, sauf obligations legales (facturation, lutte contre fraude).
                    </p>
                </div>
            </div>

            <div class="rounded-2xl border bg-gradient-to-r from-primary/10 via-background to-secondary/10 p-5 shadow-sm">
                <div class="flex flex-wrap items-center gap-2 text-xs text-muted-foreground">
                    <span class="rounded-full border border-border px-3 py-1">Vos droits</span>
                    <span class="rounded-full border border-border px-3 py-1">Acces</span>
                    <span class="rounded-full border border-border px-3 py-1">Rectification</span>
                    <span class="rounded-full border border-border px-3 py-1">Suppression</span>
                    <span class="rounded-full border border-border px-3 py-1">Portabilite</span>
                    <span class="rounded-full border border-border px-3 py-1">Opposition</span>
                </div>
                <p class="mt-3 text-sm text-muted-foreground">
                    Pour exercer vos droits, ouvrez un ticket support ou ecrivez a support@omega-edition.com en precisant l email du compte.
                </p>
            </div>
        </div>
    </PublicLayout>
</template>
