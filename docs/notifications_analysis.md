# Analyse des Notifications - Système d'Assignation aux Admins

Ce document analyse les différents types de notifications dans l'application et comment elles sont acheminées vers les administrateurs assignés.

## Vue d'ensemble

Le système de notifications de l'application utilise plusieurs canaux pour informer les utilisateurs et les administrateurs :
- **Email** (via MailMessage)
- **Database** (notifications Laravel stockées en base de données)
- **FCM** (Firebase Cloud Messaging pour les notifications push)

## Logique d'Assignation des Destinataires

### Principe Général

L'application utilise un système d'assignation où chaque entité principale (`SourcingRequest`, `Quotation`, `SourcingOrder`) peut être assignée à un administrateur spécifique via le champ `assigned_to_admin_id`.

**Règle de notification :**
- ✅ **Super Admins** : Reçoivent TOUTES les notifications
- ✅ **Admin Assigné** : Reçoit les notifications uniquement pour les entités qui lui sont assignées
- ❌ **Autres Admins** : Ne reçoivent PAS de notifications pour les entités non assignées

---

## 1. Notifications pour les Demandes de Sourcing (SourcingRequest)

### 1.1 SourcingRequestCreated

**Déclencheur :** Création d'une nouvelle demande de sourcing par un client

**Fichier :** `app/Http/Controllers/SourcingRequestController.php` (ligne 127-141)

**Destinataires :**
```php
$admins = User::where('role', 'super_admin')
    ->when($assignedAdminId, function ($query) use ($assignedAdminId) {
        $query->orWhere(function ($q) use ($assignedAdminId) {
            $q->where('role', 'admin')
              ->where('id', $assignedAdminId);
        });
    })
    ->get();
```

**Interprétation :**
- Tous les super admins reçoivent la notification
- Si la demande est assignée (`assigned_to_admin_id` n'est pas null), l'admin assigné reçoit aussi la notification
- Les autres admins réguliers ne reçoivent rien

**Canaux utilisés :** Email, Database, FCM

---

### 1.2 SourcingRequestAssigned

**Déclencheur :** Une demande de sourcing est assignée manuellement ou automatiquement à un admin

**Fichier :** `app/Notifications/SourcingRequestAssigned.php`

**Destinataires :** L'administrateur qui se voit assigner la demande

**Canaux utilisés :** Email, Database, FCM

**Remarque :** Cette notification est actuellement en français (non internationalisée)

---

### 1.3 SourcingRequestAutoAssigned

**Déclencheur :** Assignation automatique via système Round Robin

**Destinataires :** L'administrateur assigné automatiquement

**Canaux utilisés :** Email, Database, FCM

---

### 1.4 SourcingRequestStatusUpdated

**Déclencheur :** Changement de statut d'une demande de sourcing

**Destinataires :** Le client (créateur de la demande)

**Canaux utilisés :** Email, Database, FCM

---

## 2. Notifications pour les Devis (Quotation)

### 2.1 QuotationCreated

**Déclencheur :** Création d'un nouveau devis par un admin

**Fichier trigger :** `app/Http/Controllers/Admin/QuotationController.php` (ligne 165)
```php
event(new \App\Events\QuotationCreated($quotation));
```

**Fichier listener :** `app/Listeners/SendQuotationCreatedNotification.php`

**Destinataires :** Le client (propriétaire de la `SourcingRequest` associée)

**Logique spéciale :**
- Protection anti-duplication avec cache lock (60 secondes)
- Retry: 3 tentatives avec backoff (1min, 5min, 15min)
- Validation des relations (quotation → sourcingRequest → user)

**Canaux utilisés :** Email, Database, FCM

**Remarque :** Cette notification est envoyée AU CLIENT, pas aux admins

---

### 2.2 QuotationAccepted

**Déclencheur :** Le client accepte un devis

**Destinataires :** Admins (selon logique d'assignation)

**Canaux utilisés :** Email, Database, FCM

---

### 2.3 QuotationRejected

**Déclencheur :** Le client rejette un devis

**Destinataires :** Admins (selon logique d'assignation)

**Canaux utilisés :** Email, Database, FCM

---

## 3. Notifications pour les Commandes (SourcingOrder)

### 3.1 ProofOfPaymentUploaded

**Déclencheur :** Le client téléverse une preuve de paiement

**Fichier :** `app/Http/Controllers/Client/SourcingOrderController.php` (ligne 69-83)

**Destinataires :**
```php
$admins = User::where('role', 'super_admin')
    ->when($assignedAdminId, function ($query) use ($assignedAdminId) {
        $query->orWhere(function ($q) use ($assignedAdminId) {
            $q->where('role', 'admin')
              ->where('id', $assignedAdminId);
        });
    })
    ->get();
```

**Interprétation :** Même logique que `SourcingRequestCreated`
- Super admins : ✅
- Admin assigné : ✅
- Autres admins : ❌

**Canaux utilisés :** Email, Database, FCM

**Event supplémentaire :** Déclenche aussi `ProofOfPaymentUploadedEvent` (pour sync Google Sheets)

---

### 3.2 ProofOfPaymentRejected

**Déclencheur :** Un admin rejette la preuve de paiement

**Destinataires :** Le client (propriétaire de la commande)

**Canaux utilisés :** Email, Database, FCM

---

### 3.3 SourcingOrderStatusUpdated

**Déclencheur :** Changement de statut d'une commande (via listener)

**Fichier listener :** `app/Listeners/SendSourcingOrderStatusUpdatedNotification.php`

**Destinataires :** Le client (propriétaire de la commande)

**Logique spéciale :**
- Protection anti-duplication avec cache lock
- Emojis dynamiques selon le statut (✅ paid, 📦 preparing, 🚚 transit, 🎉 delivered, etc.)

**Canaux utilisés :** Email, Database, FCM

**Remarque :** Cette notification est envoyée AU CLIENT, pas aux admins

---

## 4. Notifications pour les Remboursements (RefundRequest)

### 4.1 RefundRequestCreated

**Déclencheur :** Le client crée une demande de remboursement

**Fichier :** `app/Http/Controllers/Client/RefundRequestController.php` (ligne 99)

**Destinataires :** Logique similaire aux autres notifications (super admins + admin assigné)

**Canaux utilisés :** Email, Database, FCM

---

### 4.2 RefundStatusUpdated

**Déclencheur :** Changement de statut d'une demande de remboursement

**Destinataires :** Le client

**Canaux utilisés :** Email, Database, FCM

---

## 5. Autres Notifications

### 5.1 DossierAssignmentRemoved

**Déclencheur :** Suppression de l'assignation d'un admin sur un dossier

**Destinataires :** L'admin qui était précédemment assigné

**Canaux utilisés :** Email, Database

---

### 5.2 CustomVerifyEmail

**Déclencheur :** Vérification d'email (Laravel standard)

**Destinataires :** Utilisateur lors de l'inscription

**Canaux utilisés :** Email

---

## Schéma de Cascade d'Assignation

```
┌─────────────────────┐
│ SourcingRequest     │
│ assigned_to_admin_id│
└──────────┬──────────┘
           │
           │ Cascade lors de la création
           ▼
┌─────────────────────┐
│ Quotation           │
│ assigned_to_admin_id│
└──────────┬──────────┘
           │
           │ Cascade lors de l'acceptation
           ▼
┌─────────────────────┐
│ SourcingOrder       │
│ assigned_to_admin_id│
└─────────────────────┘
```

**Note :** Selon les conversations précédentes, cette cascade devrait être implémentée pour maintenir la cohérence des assignations à travers toute la chaîne SourcingRequest → Quotation → SourcingOrder.

---

## Relations dans les Modèles

### SourcingRequest
```php
// app/Models/SourcingRequest.php
protected $fillable = [
    // ...
    'assigned_to_admin_id',
    'assigned_at',
];

public function assignedAdmin()
{
    return $this->belongsTo(User::class, 'assigned_to_admin_id');
}

public function isAssigned()
{
    return !is_null($this->assigned_to_admin_id);
}

public function isAssignedTo(User $user)
{
    return $this->assigned_to_admin_id === $user->id;
}

public function claim(User $user)
{
    // Permet à un admin de réclamer le dossier
}
```

### Quotation
```php
// app/Models/Quotation.php
protected $fillable = [
    'sourcing_request_id',
    'assigned_to_admin_id',
    // ...
];

public function assignedAdmin()
{
    return $this->belongsTo(User::class, 'assigned_to_admin_id');
}
```

### SourcingOrder
Le champ `assigned_to_admin_id` devrait exister selon les conversations précédentes.

---

## Pattern Général de Notification aux Admins

Voici le code pattern réutilisé dans plusieurs endroits :

```php
// Récupérer l'ID de l'admin assigné
$assignedAdminId = $entity->assigned_to_admin_id;

// Construire la liste des destinataires
$admins = User::where('role', 'super_admin')
    ->when($assignedAdminId, function ($query) use ($assignedAdminId) {
        $query->orWhere(function ($q) use ($assignedAdminId) {
            $q->where('role', 'admin')
              ->where('id', $assignedAdminId);
        });
    })
    ->get();

// Envoyer la notification
foreach ($admins as $admin) {
    $admin->notify(new SomeNotification($entity));
}
```

**Ce pattern garantit que :**
1. Tous les super admins sont toujours notifiés
2. L'admin assigné est inclus s'il existe
3. Les autres admins réguliers sont exclus

---

## Recommandations

### 1. Centralisation du Pattern
Le code de sélection des destinataires est dupliqué. Il serait judicieux de créer une méthode helper :

```php
// app/Helpers/NotificationHelpers.php
class NotificationHelpers
{
    public static function getNotifiableAdmins(?int $assignedAdminId = null): Collection
    {
        return User::where('role', 'super_admin')
            ->when($assignedAdminId, function ($query) use ($assignedAdminId) {
                $query->orWhere(function ($q) use ($assignedAdminId) {
                    $q->where('role', 'admin')
                      ->where('id', $assignedAdminId);
                });
            })
            ->get();
    }
}
```

### 2. Internationalisation
Certaines notifications (comme `SourcingRequestAssigned`) contiennent du texte en dur en français. Il faudrait les internationaliser avec `__()`.

### 3. Vérification de la Cascade
S'assurer que lors de la création d'une `Quotation` depuis une `SourcingRequest`, l'`assigned_to_admin_id` est bien copié, et de même pour `SourcingOrder`.

### 4. Tests
Créer des tests pour vérifier :
- Que seuls les bons admins reçoivent les notifications
- Que la cascade d'assignation fonctionne correctement
- Que les super admins reçoivent toujours toutes les notifications

---

## Résumé des Fichiers Clés

| Fichier | Description |
|---------|-------------|
| `app/Notifications/` | Toutes les classes de notifications |
| `app/Http/Controllers/SourcingRequestController.php` | Logique de notification pour les demandes |
| `app/Http/Controllers/Client/SourcingOrderController.php` | Logique de notification pour les commandes |
| `app/Listeners/SendQuotationCreatedNotification.php` | Listener pour les devis créés |
| `app/Listeners/SendSourcingOrderStatusUpdatedNotification.php` | Listener pour les changements de statut |
| `app/Models/SourcingRequest.php` | Modèle avec relations d'assignation |
| `app/Models/Quotation.php` | Modèle avec relations d'assignation |
| `app/Providers/EventServiceProvider.php` | Configuration des events et listeners |

---

**Document généré le :** 2025-12-21  
**Auteur :** Analyse automatique du système
