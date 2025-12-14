<script setup lang="ts">
import PublicLayout from '@/layouts/PublicLayout.vue';
</script>

<template>
    <PublicLayout
        title="Conditions d utilisation"
        description="Ce qui est autorise, vos engagements et les regles d abonnement sur Omega Edition."
    >
        <div class="space-y-6">
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="rounded-xl border bg-card p-4 shadow-sm">
                    <div class="text-xs uppercase tracking-[0.14em] text-primary">Essentiel</div>
                    <div class="mt-2 text-sm text-muted-foreground">
                        Acces libre au public, compte requis pour publier ou suivre. Respect des droits d auteur et de la moderation.
                    </div>
                </div>
                <div class="rounded-xl border bg-card p-4 shadow-sm">
                    <div class="text-xs uppercase tracking-[0.14em] text-primary">Support</div>
                    <div class="mt-2 text-sm text-muted-foreground">
                        support@omega-edition.com pour les questions legales, demandes de retrait ou desabonnement.
                    </div>
                </div>
            </div>

            <div class="grid gap-4 lg:grid-cols-2">
                <div class="rounded-xl border bg-card/80 p-5 shadow-sm">
                    <div class="mb-3 flex items-center justify-between">
                        <h3 class="text-base font-semibold">Acces et comptes</h3>
                        <span class="rounded-full bg-muted px-3 py-1 text-xs text-muted-foreground">Lecteurs</span>
                    </div>
                    <p class="text-sm text-muted-foreground">
                        Chapitres publics accessibles sans compte. Favoris, historique et notifications necessitent un compte lecteur. Protection de vos identifiants obligatoire.
                    </p>
                </div>
                <div class="rounded-xl border bg-card/80 p-5 shadow-sm">
                    <div class="mb-3 flex items-center justify-between">
                        <h3 class="text-base font-semibold">Contenus createurs</h3>
                        <span class="rounded-full bg-secondary/20 px-3 py-1 text-xs text-secondary-foreground">Droits</span>
                    </div>
                    <p class="text-sm text-muted-foreground">
                        Vous devez posseder les droits des oeuvres publiees. Contenus illicites, haineux ou plagies sont interdits. La moderation peut suspendre ou retirer sans preavis.
                    </p>
                </div>
                <div class="rounded-xl border bg-card/80 p-5 shadow-sm">
                    <div class="mb-3 flex items-center justify-between">
                        <h3 class="text-base font-semibold">Abonnements</h3>
                        <span class="rounded-full bg-primary/10 px-3 py-1 text-xs text-primary">Tarifs</span>
                    </div>
                    <p class="text-sm text-muted-foreground">
                        Plan gratuit : premiers chapitres. Plan premium : acces complet. Prix en XAF avec conversion selon la devise choisie. Renouvellement selon la periode choisie, resiliable avant echeance.
                    </p>
                </div>
                <div class="rounded-xl border bg-card/80 p-5 shadow-sm">
                    <div class="mb-3 flex items-center justify-between">
                        <h3 class="text-base font-semibold">Responsabilite</h3>
                        <span class="rounded-full bg-muted px-3 py-1 text-xs text-muted-foreground">Limitee</span>
                    </div>
                    <p class="text-sm text-muted-foreground">
                        Omega Edition est fourni tel quel. Disponibilite et securite font l objet de moyens raisonnables, sans garantie absolue. Les utilisateurs restent responsables de leurs contenus et usages.
                    </p>
                </div>
                <div class="rounded-xl border bg-card/80 p-5 shadow-sm lg:col-span-2">
                    <div class="mb-3 flex items-center justify-between">
                        <h3 class="text-base font-semibold">Resiliation</h3>
                        <span class="rounded-full bg-destructive/10 px-3 py-1 text-xs text-destructive">Conformite</span>
                    </div>
                    <p class="text-sm text-muted-foreground">
                        Nous pouvons suspendre ou clore un compte en cas de violation des presentes conditions. Vous pouvez supprimer votre compte et vos donnees, sauf obligations legales (facturation, litiges).
                    </p>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
