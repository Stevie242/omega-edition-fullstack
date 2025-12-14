<script setup lang="ts">
import PublicLayout from '@/layouts/PublicLayout.vue';
</script>

<template>
    <PublicLayout
        title="Conditions d’utilisation"
        description="Règles d’usage pour lecteurs et créateurs."
    >
        <div class="prose prose-sm dark:prose-invert max-w-none text-muted-foreground">
            <h3>Accès</h3>
            <p>Les chapitres publics sont accessibles sans compte. Les fonctionnalités avancées (favoris, historique, publication) nécessitent un compte et un rôle adapté.</p>

            <h3>Contenus créateurs</h3>
            <p>Les créateurs doivent détenir les droits des œuvres publiées. Les contenus illicites ou offensants sont interdits.</p>

            <h3>Abonnements</h3>
            <p>Le plan gratuit offre les premiers chapitres. Le plan premium donne accès complet. Les tarifs sont affichés en XAF (convertis selon la devise choisie).</p>

            <h3>Limitation de responsabilité</h3>
            <p>Omega Edition est fourni « tel quel ». Nous mettons en œuvre des moyens raisonnables pour la disponibilité, sans garantie absolue.</p>

            <h3>Résiliation</h3>
            <p>Nous pouvons suspendre ou clôturer un compte en cas de violation. Vous pouvez supprimer votre compte et données associées, hors contraintes légales.</p>
        </div>
    </PublicLayout>
</template>
