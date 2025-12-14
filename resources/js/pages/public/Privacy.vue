<script setup lang="ts">
import PublicLayout from '@/layouts/PublicLayout.vue';
</script>

<template>
    <PublicLayout
        title="Politique de confidentialité"
        description="Comment nous traitons les données sur Omega Edition."
    >
        <div class="prose prose-sm dark:prose-invert max-w-none text-muted-foreground">
            <h3>Collecte</h3>
            <p>Nous collectons les données de compte (email, nom) et les préférences pour personnaliser l’expérience. Les lectures publiques sans compte ne sont pas rattachées à un profil.</p>

            <h3>Usage</h3>
            <p>Vos données servent à l’authentification, aux notifications, aux statistiques agrégées et à la facturation des abonnements.</p>

            <h3>Partage</h3>
            <p>Aucune revente. Partage limité aux prestataires nécessaires (paiement, stockage) avec des clauses de protection.</p>

            <h3>Conservation</h3>
            <p>Les données sont conservées le temps nécessaire au service ou sur demande de suppression, sauf obligations légales.</p>

            <h3>Droits</h3>
            <p>Accès, rectification, suppression, portabilité. Contactez le support pour exercer vos droits.</p>
        </div>
    </PublicLayout>
</template>
