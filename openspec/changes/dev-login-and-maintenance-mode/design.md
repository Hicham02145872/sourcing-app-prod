## Context

L'application SmartSourcing a déjà un système de rôles (client, admin, super_admin, developer) avec un Dev Dashboard complet accessible à `/admin/dev-dashboard`. Le login actuel est partagé entre tous les rôles. Le mode maintenance Laravel natif (`artisan down`) n'est pas accessible depuis l'UI et affiche une page 503 basique.

L'objectif est de :
1. Séparer le point d'entrée login pour les développeurs
2. Permettre l'activation/désactivation d'un écran de maintenance élégant depuis le Dev Dashboard

## Goals / Non-Goals

**Goals:**
- Route `/dev/login` dédiée avec identité visuelle distincte
- Authentification dev exclusive (role:developer uniquement)
- Toggle maintenance mode dans le Dev Dashboard
- Écran de maintenance personnalisable (message, branding)
- Les developers ET les admins/super_admin ne sont PAS affectés par le mode maintenance
- Les API endpoints ne sont pas affectés

**Non-Goals:**
- Modifier le login existant pour les clients/admin
- Ajouter un système de rôles nouveau
- Créer un endpoint API pour le maintenance mode

## Decisions

### 1. Login dev : nouvelle route + controller dédié

**Décision**: Créer `DevLoginController` avec `showLoginForm()` et `login()` dans `app/Http/Controllers/Auth/`. La route est `/{locale}/dev/login` dans `routes/web.php`.

**Pourquoi**: Séparation claire du flux d'auth. Le controller vérifie `$user->isDeveloper()` après authentification et rejette les non-développeurs.

**Alternatives considérées**:
- Ajouter un paramètre au login existant → Rejeté car moins propre, mélange les flux
- Utiliser un middleware sur le login existant → Rejeté car le formulaire serait le même

### 2. Maintenance mode : stockage en cache avec fallback DB

**Décision**: Stocker l'état dans `Cache::remember()` avec une clé `maintenance_mode` et `maintenance_message`. Utiliser une table `settings` comme fallback pour persister跨 cache clears.

**Pourquoi**: Le cache est rapide et suffisant. La table `settings` assure la persistance. Si les deux échouent, le mode est considéré comme inactif (fail-open).

**Alternatives considérées**:
- `artisan down`/`up` → Rejeté car pas de message personnalisable UI, et bloque les devs aussi
- Fichier `storage/framework/maintenance.php` → Rejeté car pas de toggle UI

### 3. Middleware CheckMaintenanceMode

**Décision**: Nouveau middleware `CheckMaintenanceMode` enregistré dans `bootstrap/app.php` comme middleware web global. Il vérifie si le mode est actif et si l'utilisateur n'est PAS developer/admin/super_admin.

**Pourquoi**: Intercepte toutes les requêtes web une seule fois. Les devs et admins passent toujours.

### 4. Écran de maintenance : vue Blade standalone — Design "WOW"

**Décision**: Créer `resources/views/maintenance.blade.php` avec un design premium, moderne et impressionnant.

**Approche visuelle :**

- **Background** : Gradient animé dark (deep indigo → violet → noir) avec particules flottantes (bokeh effect) en CSS pur
- **Carte centrale** : Glassmorphism (backdrop-blur, bordures semi-transparentes, ombre portée douce)
- **Illustration** : SVG animé inline — engrenages tournants avec une fusée qui décolle (symbolise la "mise à jour"). Animations CSS keyframes fluides, pas de JS
- **Typographie** : Titre en bold gradient text (white → violet), message en gras
- **Animations** : Fade-in au chargement, pulsation subtile du logo, rotation lente des engrenages
- **Responsive** : Mobile-first, adapte taille illustration et carte
- **Logo** : SmartSourcing avec effet glow/shimmer CSS

**Technologies utilisées :**
- CSS custom properties pour les couleurs
- `@keyframes` pour toutes les animations (pas de bibliothèque JS lourde)
- SVG inline pour l'illustration (pas d'image externe = chargement instantané)
- `backdrop-filter: blur()` pour le glassmorphism
- CSS `animation-delay` pour créer un effet de vague

**Pourquoi CSS pur plutôt que JS/Canvas :**
- Pas de dépendance externe
- Performances optimales (GPU-accelerated via transform/opacity)
- Fonctionne même si JS est désactivé
- Taille de page minimale (~15KB total)

**Alternatives considérées :**
- Three.js (comme la page welcome) → Rejeté car trop lourd pour une page 503
- Lottie animation → Rejeté car nécessite une dépendance externe
- Image statique → Rejeté car pas d'effet "wow"

### 5. Persistance : table `settings` comme source de vérité

**Décision**: Créer une migration pour une table `settings` (key/value) qui stocke `maintenance_mode` (boolean) et `maintenance_message` (string). Le cache est synchronisé avec cette table.

**Pourquoi**: Survit aux cache clears, aux redémarrages, et est queryable. Le cache est un layer au-dessus pour la performance.

## Risks / Trade-offs

- **Risque**: Cache non synchronisé si le serveur Redis tombe → **Mitigation**: Fallback sur la table `settings`
- **Risque**: Admin accidentellement locké hors de l'app → **Mitigation**: Les admins ne sont PAS affectés par le mode maintenance
- **Trade-off**: Table `settings` supplémentaire → Accepté car nécessaire pour la persistance

## Migration Plan

1. Migration : créer table `settings` avec seed
2. Controller : `DevLoginController` + routes
3. Vue : `dev-login.blade.php`
4. Middleware : `CheckMaintenanceMode` + registration
5. Vue : `maintenance.blade.php`
6. Dev Dashboard : ajouter toggle + champ message
7. Langues : traductions EN/FR/AR
