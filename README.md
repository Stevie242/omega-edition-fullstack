# Omega Edition

Plateforme Laravel/Inertia pour la lecture et la publication de mangas & webtoons, avec trois espaces principaux : lecteur, créateur et administrateur. L’objectif : offrir une expérience fluide aux lecteurs, un studio numérique aux créateurs, et des outils de supervision complets aux admins.

## Fonctionnalités
- **Lecteurs** : tableau de bord, bibliothèque, lecture de chapitres, favoris, historique, abonnement.
- **Créateurs/Studios** : gestion des séries et chapitres, analytics, paiements, paramètres dédiés.
- **Administrateurs** : dashboard global, supervision du catalogue (validation/modération), gestion des utilisateurs et créateurs, monitoring.
- **Auth & rôles** : Fortify + middleware de rôles (`reader`, `creator`, `admin`), accès séparés via Inertia.
- **UI** : Vue 3 + Inertia + Tailwind v4 + PrimeVue/Lucide, layouts dédiés (admin/creator/reader) et navigation sidebar.

## Stack
- Laravel 12 (PHP ≥ 8.2), Inertia.js 2.x, Fortify.
- Vue 3 + Vite 7, Tailwind CSS v4, PrimeVue, Lucide.
- Pest pour les tests, Sail/Pail en dev.

## Installation
```bash
git clone <repo>
cd omega-full
cp .env.example .env    # ou laissez le script le faire
composer install
php artisan key:generate
php artisan migrate
npm install
npm run dev             # ou npm run build en prod
```
Raccourci complet : `composer run setup` (installe, copie l’`.env`, génère la clé, migre et build).

## Lancer le projet en local
- Serveur Laravel : `php artisan serve` (ou via `composer run dev` qui lance aussi Vite et la queue).
- Front (Vite) : `npm run dev`.
- Queue (si nécessaire) : `php artisan queue:listen --tries=1`.

## Tests
```bash
php artisan test    # ou composer test
```

## Organisation des routes/pages
- `routes/web.php` charge les sections `routes/admin.php`, `routes/creator.php`, `routes/reader.php`, `routes/settings.php`.
- Pages Inertia : `resources/js/Pages/{admin,creator,reader,settings}/`.
- Layouts : `resources/js/layouts/` (ex. `AdminLayout.vue`).

## Points d’accès principaux
- Landing : `/`
- Lecteur : `/reader`
- Créateur : `/creator`
- Admin : `/admin`

## Notes
- Pensez à ajuster l’`.env` (DB, mail, queue, cache) avant migration/build.
- Les rôles sont vérifiés via middleware : un compte doit être associé au rôle attendu pour accéder aux sections.***
