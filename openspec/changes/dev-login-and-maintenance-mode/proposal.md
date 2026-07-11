## Why

Les développeurs utilisent actuellement le même flux de login que les clients et admin. Il est nécessaire d'avoir un point d'entrée dédié pour les développeurs, distinct et sécurisé. De plus, il n'existe aucun mécanisme pour activer/désactiver un écran de maintenance visible par les utilisateurs finaux sur la page d'accueil — le mode maintenance Laravel natif (`artisan down`) n'est pas accessible depuis l'interface et affiche une page 503 générique.

## What Changes

- Nouvelle route `/dev/login` avec un formulaire de login dédié aux développeurs (identifiant visuel distinct)
- Le login dev redirige vers le Dev Dashboard existant (`/admin/dev-dashboard`)
- Seuls les utilisateurs avec le rôle `developer` peuvent s'authentifier via cette route
- Nouveau toggle "Mode Maintenance" dans le Dev Dashboard pour activer/désactiver l'écran de maintenance
- Quand activé, tous les utilisateurs non-développeurs voient un écran de maintenance élégant sur la page d'accueil et toutes les routes protégées
- Les développeurs ne sont jamais affectés par le mode maintenance (ils continuent d'accéder normalement)
- L'écran de maintenance est personnalisable (message, image) depuis le Dev Dashboard

## Capabilities

### New Capabilities
- `dev-login`: Route et formulaire de login dédié aux développeurs, séparé du login client/admin
- `maintenance-mode-toggle`: Activation/désactiver un écran de maintenance depuis le Dev Dashboard, visible par les utilisateurs non-développeurs

### Modified Capabilities
<!-- Aucune spec existante modifiée -->

## Impact

- **Routes**: Ajout de `/dev/login` (GET + POST) dans `routes/web.php`
- **Middleware**: Nouveau middleware `CheckMaintenanceMode` pour intercepter les requêtes quand le mode est activé
- **Controllers**: Nouveau `DevLoginController` pour gérer le login dev
- **Views**: Nouvelle vue `auth/dev-login.blade.php` + nouvelle vue `maintenance.blade.php`
- **Dev Dashboard**: Ajout d'un toggle "Mode Maintenance" + champ pour le message personnalisé
- **Config/DB**: Stockage de l'état maintenance (cache ou table settings)
- **Langues**: Traductions EN/FR/AR pour l'écran de maintenance
