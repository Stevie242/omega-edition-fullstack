# Guide de rémunération par vues (créateurs)

## Collecte des vues
- Table `chapter_views` : une ligne par `user_id` + `chapter_id` + `year_month` (unique). On upsert pour éviter le double comptage.
- Champs clés : `duration_seconds` (cumul), `completion_ratio` (0-100), `counted_at` (vue validée), `creator_id`, `series_id`, `meta`.
- Validation d’une vue : seuil par défaut `duration_seconds >= 30s` **et** `completion_ratio >= 50%`. Si atteint, `counted_at` est renseigné.
- End-point : `POST /reader/chapters/{chapter}/view` (voir `Reader\ChapterViewController`), appelé par le front lecture avec `duration_seconds` + `completion_ratio`.
- On autorise une nouvelle vue d’un même lecteur sur le même chapitre à chaque mois (`year_month`), pour rémunérer une relecture sur abonnement renouvelé.

## Agrégation / revenus
- Commande/Job : `revenue:aggregate --month=YYYY-MM --value=25 --platform-bps=2500` déclenche `AggregateCreatorRevenueJob`.
- Sources : vues validées (`counted_at` non null) sur le mois `year_month`.
- Calcul par créateur :
  - `gross = vues_validées * valeur_par_vue` (par défaut 25 XAF).
  - `platform_fee = gross * platform_fee_bps / 10000` (par défaut 25% = 2500 bps).
  - `tax_withheld = gross * tax_rate_bps / 10000` si `taxProfile` du créateur a un taux; sinon 0.
  - `net = gross - platform_fee - tax_withheld` (min 0).
- Résultat stocké dans `creator_revenue_monthlies` (unique par `creator_id` + `year_month`), avec les vues validées et les montants.

## Modèles / tables
- `chapter_views` : journal des vues (unique par user/chapter/mois).
- `creator_revenue_monthlies` : agrégat mensuel par créateur (vues validées, montants brut/plateforme/taxe/net).
- `tax_profiles` : mode fiscal (`self` ou `withheld`), pays, tax_id, `tax_rate_bps`. Utilisé pour la retenue éventuelle.
- `payouts` : champs `tax_withheld_xaf` et `tax_rate_bps` pour tracer la retenue appliquée sur les versements.

## Pages concernées
- Dashboard créateur : affiche net/pending et derniers reversements.
- Payouts : affiche résumé avec taxe retenue, reversements/factures/comptes.
- Fiscalité : `creator/settings/tax` pour choisir le mode (autogéré / retenue plateforme) et le taux (bps).

## À connecter côté front lecture
- Envoyer un ping `POST /reader/chapters/{chapter}/view` avec `duration_seconds` cumulé et `completion_ratio` (pages lues / pages totales * 100).
- Débouncer l’envoi (fin de lecture ou toutes X secondes) en accumulant la durée côté front, pour limiter le trafic.

## Notes
- Le seuil de validation peut être ajusté dans `ViewTrackingService::meetsThreshold`.
- La valeur par vue et la commission sont paramétrables via la commande `revenue:aggregate`.
- Pour éviter la croissance infinie, on peut archiver ou purger `chapter_views` après agrégation + durée de rétention (ex. > 6 mois) si nécessaire.***
