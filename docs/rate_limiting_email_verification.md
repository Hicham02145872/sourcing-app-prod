# Intégration du Rate Limiting pour la Vérification d'Email

Ce document explique comment le système de limitation de débit (Rate Limiting) est configuré pour l'envoi et la vérification des emails dans l'application.

## 1. Pourquoi le Rate Limiting ?

La limitation de débit est cruciale pour les points d'entrée (endpoints) d'authentification et de communication car elle permet :
- **Prévention du Spam** : Empêcher un utilisateur ou un bot de saturer le serveur de mails en demandant des dizaines de renvois d'email par seconde.
- **Sécurité (Brute Force)** : Protéger le lien de vérification signé contre les tentatives de devinement de la signature ou du hash.
- **Délivrabilité** : Éviter que l'adresse IP du serveur soit bannie par les fournisseurs de messagerie (Gmail, Outlook) pour envoi massif et suspect.

---

## 2. Configuration Actuelle

Dans le fichier `routes/auth.php`, nous utilisons le middleware natif de Laravel `throttle`.

### Endpoint de Renvoi (`verification.send`)
```php
Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    ->middleware('throttle:6,1') // <--- ICI
    ->name('verification.send');
```
- **6** : Nombre maximum de tentatives autorisées.
- **1** : Intervalle de temps en minutes.
**Signification** : L'utilisateur peut demander un renvoi d'email au maximum **6 fois par minute**.

### Endpoint de Vérification (`verification.verify`)
```php
Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
    ->middleware(['signed', 'throttle:6,1']) // <--- ICI
    ->name('verification.verify');
```
**Signification** : Même limitation (6 clics par minute). Cela protège contre l'abus du lien signé.

---

## 3. Fonctionnement Interne

1. **Identification** : Laravel identifie l'utilisateur par son ID (s'il est connecté) ou par son adresse IP.
2. **Stockage** : Le nombre de tentatives est stocké en cache (Redis ou Database selon la configuration).
3. **Réponse 429** : Si la limite est dépassée, Laravel renvoie automatiquement une erreur `429 Too Many Requests`.
4. **En-têtes HTTP** : Les en-têtes suivants sont ajoutés à la réponse pour informer l'utilisateur :
    - `X-RateLimit-Limit`: Limite totale.
    - `X-RateLimit-Remaining`: Tentatives restantes.
    - `Retry-After`: Nombre de secondes à attendre avant la prochaine tentative.

---

## 4. Personnalisation Avancée (Laravel 11+)

Si vous souhaitez définir des limites plus complexes (ex: par membre premium vs gratuit), vous pouvez définir un Rate Limiter nommé dans `bootstrap/app.php` :

```php
// Exemple dans bootstrap/app.php
->withRouting(
    // ...
)
->withMiddleware(function (Middleware $middleware) {
    RateLimiter::for('email_verification', function (Request $request) {
        return Limit::perMinute(3)->by($request->user()?->id ?: $request->ip());
    });
})
```

Ensuite, remplacez `throttle:6,1` par `throttle:email_verification` dans les routes.

---

## 5. Lien avec l'UI (Alpine.js)

Le Rate Limiter côté backend travaille de concert avec le **timer Alpine.js** que nous avons ajouté sur le bouton "Resend". Le timer empêche le clic côté client (UX), tandis que le throttle garantit la sécurité côté serveur au cas où l'utilisateur contournerait l'interface.
