# Guide d'Integration Sentry

## Vue d'ensemble

Sentry est un outil de suivi des erreurs et de monitoring en temps réel qui vous alerte sur les bugs en production avant que les utilisateurs ne les signalent.

## État actuel

✅ Sentry est déjà installé dans votre projet (`sentry/sentry-laravel`)
✅ La configuration est en place dans `bootstrap/app.php`
✅ Les fichiers de configuration sont créés

## Étapes de configuration

### 1. Obtenir votre DSN Sentry

1. Créez un compte sur [sentry.io](https://sentry.io)
2. Créez un nouveau projet (sélectionnez Laravel)
3. Copiez le **DSN** (URL de la forme `https://xxxxx@xxxxx.ingest.sentry.io/xxxxx`)

### 2. Configurer l'environnement local

Ajoutez le DSN dans votre `.env`:

```env
SENTRY_LARAVEL_DSN=https://YOUR_DSN_KEY@YOUR_DSN_ID.ingest.sentry.io/YOUR_PROJECT_ID
SENTRY_TRACES_SAMPLE_RATE=0.1
SENTRY_PROFILES_SAMPLE_RATE=0.1
```

**Notes:**
- `SENTRY_TRACES_SAMPLE_RATE=0.1` capture 10% des transactions (performance monitoring)
- `SENTRY_PROFILES_SAMPLE_RATE=0.1` capture 10% des profils
- En développement, vous pouvez mettre `0.1` ou `0.5`

### 3. Configurer la production

Dans `.env.production`, ajoutez:

```env
SENTRY_LARAVEL_DSN=https://YOUR_DSN_KEY@YOUR_DSN_ID.ingest.sentry.io/YOUR_PROJECT_ID
SENTRY_TRACES_SAMPLE_RATE=0.1
SENTRY_PROFILES_SAMPLE_RATE=0.05
```

**Notes:**
- En production, réduisez les taux d'échantillonnage (0.05-0.1) pour économiser les ressources
- L'environnement est automatiquement défini à `production` depuis `config/sentry.php`

## Configuration avancée

### Fichier de configuration: `config/sentry.php`

La configuration principale se trouve dans `config/sentry.php`. Principales options:

```php
// Breadcrumbs - Tracer les événements pour le débogage
'breadcrumbs' => [
    'logs' => true,              // Capturer les logs Laravel
    'sql_queries' => true,       // Capturer les requêtes SQL
    'cache' => true,             // Capturer les événements cache
    'http_client_requests' => true,  // Capturer les requêtes HTTP
    'queue_jobs' => true,        // Capturer les travaux en file d'attente
],

// Exceptions à ignorer (ne pas envoyer à Sentry)
'ignore_exceptions' => [
    \Illuminate\Auth\AuthenticationException::class,
],

// Capture des erreurs silencieuses
'capture_silenced_errors' => true,
```

### Capture manuelle d'événements

Vous pouvez capturer manuellement des événements dans votre code:

```php
use Sentry\Laravel\Facade as Sentry;

// Capturer une exception
try {
    // Votre code
} catch (\Exception $e) {
    Sentry::captureException($e);
}

// Capturer un message
Sentry::captureMessage('Quelque chose s\'est passé', 'info');

// Ajouter du contexte
Sentry::configureScope(function ($scope) {
    $scope->setContext('ordre', [
        'id' => $order->id,
        'total' => $order->total,
    ]);
});
```

## Tester la configuration

### 1. Test en développement

Exécutez cette commande pour vérifier que Sentry est correctement configuré:

```bash
php artisan tinker
```

Puis dans Tinker:

```php
\Sentry\Laravel\Facade::captureMessage('Test message from Sentry', 'info');
echo "Message envoyé à Sentry!";
```

### 2. Test en production

Pour tester en production, utilisez:

```bash
php artisan tinker
```

```php
\Sentry\Laravel\Integration::initialize();
\Sentry\captureMessage('Test message', 'info');
echo "Message envoyé!";
```

### 3. Vérifier sur le dashboard

Allez sur votre [dashboard Sentry](https://sentry.io) et vous devriez voir vos événements.

## Variables d'environnement disponibles

| Variable | Description | Défaut |
|----------|-------------|--------|
| `SENTRY_LARAVEL_DSN` | URL d'identification du projet | `null` |
| `SENTRY_TRACES_SAMPLE_RATE` | % de transactions capturées | `0.1` |
| `SENTRY_PROFILES_SAMPLE_RATE` | % de profils capturés | `0.1` |
| `APP_ENV` | Environnement (local/production) | Lue depuis `.env` |
| `LOG_CHANNEL` | Canal de logging | `stack` |

## Intégration dans bootstrap/app.php

Sentry est automatiquement intégré dans la gestion des exceptions:

```php
->withExceptions(function (Exceptions $exceptions): void {
    if (class_exists(\Sentry\Laravel\Integration::class)) {
        \Sentry\Laravel\Integration::handles($exceptions);
    }
    
    // Exceptions à ne pas signaler
    $exceptions->dontReport([
        AuthenticationException::class,
        // ...
    ]);
    
    // Ajouter du contexte à chaque événement
    $exceptions->context(function () {
        return [
            'user_id' => auth()->id(),
            'route' => request()->route()?->getName(),
            'url' => request()->fullUrl(),
            // ...
        ];
    });
})
```

## Options de filtrage

### Ignorer certaines exceptions

Dans `config/sentry.php`, modifiez:

```php
'ignore_exceptions' => [
    \Illuminate\Auth\AuthenticationException::class,
    \Illuminate\Validation\ValidationException::class,
    \Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class,
],
```

### Avant d'envoyer (Before Send)

Pour traiter les événements avant d'être envoyés:

```php
'before_send' => function ($event, $hint) {
    // Masquer les informations sensibles
    if (isset($event['request']['url'])) {
        // Nettoyer l'URL
    }
    
    return $event;
},
```

## Performance

Sentry peut impacter les performances. Conseils:

1. **Réduisez les taux d'échantillonnage** en production
2. **Utilisez les breadcrumbs judicieusement** - ne capturez que les événements importants
3. **Limitez la longueur des valeurs** avec `max_value_length`
4. **Limitez les breadcrumbs** avec `max_breadcrumbs`

```php
// config/sentry.php
'traces_sample_rate' => 0.05,      // 5% en production
'profiles_sample_rate' => 0.02,    // 2% en production
'max_value_length' => 1024,        // Limite la taille des valeurs
'max_breadcrumbs' => 50,           // Limite les breadcrumbs
```

## Débogage

### Voir les logs de Sentry

```bash
tail -f storage/logs/laravel.log | grep -i sentry
```

### Activer le mode verbose

Temporairement dans `config/sentry.php`:

```php
'before_send' => function ($event, $hint) {
    \Log::info('Sentry event:', $event);
    return $event;
},
```

## Ressources

- [Documentation officielle Sentry Laravel](https://docs.sentry.io/platforms/php/guides/laravel/)
- [Dashboard Sentry](https://sentry.io/welcome/)
- [Gestion des événements](https://docs.sentry.io/product/sentry-basics/guides/enrich-context/)

## Prochaines étapes

1. ✅ Configurer le DSN
2. ✅ Tester en développement
3. ✅ Déployer en production
4. ✅ Surveiller le dashboard Sentry
5. ✅ Configurer les alertes (notifications par email/Slack)

---

**Date de création:** 5 mai 2026
