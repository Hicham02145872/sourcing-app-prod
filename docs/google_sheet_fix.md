# Rapport d'Analyse et Correction : Erreur Google Sheet Service

Ce document explique la cause de l'erreur survenue sur l'environnement de pré-production et détaille la solution appliquée.

## Le Problème

**Erreur observée :**
```
file_get_contents(): Read of 12288 bytes failed with errno=21 Is a directory
```
Cette erreur se produisait dans `Google\Client->setAuthConfig()`.

**Analyse :**
L'erreur "Is a directory" (errno=21) indique que la fonction PHP `file_get_contents` a tenté de lire un dossier comme si c'était un fichier.
Cela s'est produit à la ligne suivante dans `app/Services/GoogleSheetService.php` :

```php
$credentialsPath = storage_path(env('GOOGLE_APPLICATION_CREDENTIALS_PATH'));
```

Sur votre environnement de pré-production (et souvent en production), la commande `php artisan config:cache` est exécutée pour optimiser les performances. **Lorsque la configuration est mise en cache, la fonction `env()` de Laravel retourne toujours `null`** si elle est appelée en dehors des fichiers de configuration (`config/*.php`).

En conséquence :
1. `env('GOOGLE_APPLICATION_CREDENTIALS_PATH')` retournait `null`.
2. `storage_path(null)` retourne le chemin racine du dossier `storage/` (qui est un dossier).
3. `file_exists` retournait `true` (car le dossier existe).
4. `setAuthConfig` essayait de lire ce dossier, causant l'erreur.

## La Solution

Pour corriger cela de manière pérenne et suivre les bonnes pratiques Laravel, il ne faut jamais utiliser `env()` directement dans le code applicatif (Contrôleurs, Services, etc.), mais passer par les fichiers de configuration.

**Actions effectuées :**

1.  **Mise à jour de `config/services.php` :**
    J'ai ajouté une entrée pour Google avec une valeur par défaut.
    ```php
    'google' => [
        'credentials_path' => env('GOOGLE_APPLICATION_CREDENTIALS_PATH', 'app/secure/credentials.json'),
    ],
    ```
    Cela permet à la valeur d'être capturée lors du `config:cache`.

2.  **Mise à jour de `app/Services/GoogleSheetService.php` :**
    J'ai remplacé l'appel direct `env()` par `config()`.
    ```php
    $credentialsPath = storage_path(config('services.google.credentials_path'));
    ```

## Actions Requises

Après avoir déployé ces changements sur votre serveur de pré-production, vous devez impérativement rafraîchir le cache de configuration pour que les modifications soient prises en compte :

```bash
php artisan config:cache
```
ou
```bash
php artisan optimize
```

Le service devrait maintenant fonctionner correctement en utilisant le chemin par défaut `storage/app/secure/credentials.json` (ou celui défini dans votre `.env`).
