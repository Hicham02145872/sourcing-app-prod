# Rapport d'Intégration du Caching - Sourcing App

Ce rapport détaille les opportunités d'optimisation par le caching pour l'application Sourcing App, visant à améliorer les performances, réduire la charge sur la base de données et accélérer l'expérience utilisateur.

## 1. État Actuel
L'analyse montre que l'application utilise actuellement le driver de cache `database` par défaut. Bien que fonctionnel, ce driver rajoute de la charge sur la base de données pour chaque opération de cache.

### Points Forts
- Utilisation de verrous (locks) de cache pour éviter les doubles synchronisations Google Sheets.
- Invalidation manuelle du cache des notifications lors de la mise à jour des tokens FCM.

### Opportunités d'Amélioration
- **Driver de Cache** : Passer de `database` à `redis` ou `file` pour plus de rapidité.
- **Requêtes Redondantes** : Les listes administratives (Admins, Pays, Catégories) sont récupérées à chaque chargement de page.
- **Vues Statiques** : Les pages "Privacy Policy" et "Terms" peuvent être mises en cache intégralement.

---

## 2. Stratégies de Caching Recommandées

### A. Optimisation de l'Infrastructure
**Action** : Configurer Redis comme driver principal. Redis est extrêmement rapide car il stocke les données en RAM.

```dotenv
# .env
CACHE_STORE=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### B. Caching des Requêtes Eloquent (Query Caching)
Certaines données changent rarement et devraient être mises en cache pour éviter des requêtes SQL inutiles.

#### 1. Liste des Admins (utilisée dans les formulaires et filtres)
Dans `SourcingOrderController::index` :
```php
// Avant
$admins = User::where('role', 'admin')->get();

// Après
$admins = Cache::remember('admin_list', now()->addDay(), function () {
    return User::where('role', 'admin')->get();
});
```

#### 2. Données de Configuration (Pays, Services, Catégories)
Ces listes sont souvent utilisées dans l'application.
```php
public static function getAllCached()
{
    return Cache::remember('all_countries', now()->addWeek(), function () {
        return self::all();
    });
}
```

### C. Caching des Vues et Fragments (Blade Caching)
Pour les composants d'interface qui ne changent pas souvent (ex: menus, pieds de pages, pages statiques).

```blade
{{-- Dans welcome.blade.php ou footer.blade.php --}}
@cache('global_footer', 3600)
    <footer>
        <!-- Contenu lourd -->
    </footer>
@endcache
```

### D. Optimisation des Réponses API / Notification
L'application effectue déjà un `Cache::forget` pour les notifications, mais elle pourrait bénéficier d'un `Cache::remember` lors de la récupération :

```php
// Dans NotificationController::index
$cacheKey = "user_notifications_{$user->id}_page_{$currentPage}";
$mappedNotifications = Cache::remember($cacheKey, now()->addMinutes(15), function () use ($notifications) {
    return $notifications->getCollection()->map(...);
});
```

---

## 3. Plan d'Intégration Graduelle

### Phase 1 : Configuration (Immédit)
1. Modifier le `.env` pour utiliser le driver `file` (en local) ou `redis` (en production).
2. Exécuter `php artisan config:cache` et `php artisan route:cache`.

### Phase 2 : Données Globales (Court Terme)
1. Créer des méthodes `getCached()` dans les modèles `Country`, `Category` et `Service`.
2. Mettre à jour les contrôleurs pour utiliser ces méthodes.

### Phase 3 : Tableaux de Bord (Moyen Terme)
1. Mettre en cache les compteurs du Dashboard Admin (total commandes, profit du mois) pour 15-30 minutes.

---

## 4. Précautions et Invalidation
Le caching nécessite une stratégie d'invalidation rigoureuse pour éviter les données périmées.

- **Observers** : Utiliser des Observers Laravel pour vider le cache lorsqu'un modèle est créé ou mis à jour.
  ```php
  public function saved(Country $country) {
      Cache::forget('all_countries');
  }
  ```
- **Tags** : Si Redis est utilisé, utiliser les `Cache::tags(['orders'])` pour grouper et invalider facilement des ensembles de données.

---

> [!IMPORTANT]
> Ne jamais mettre en cache des données sensibles ou hautement volatiles (comme les sessions de paiement en cours) sans un verrou de sécurité strict.
