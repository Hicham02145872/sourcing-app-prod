# Plan d'Implémentation : Système de Tracking FSB avec Statuts Virtuels

## 1. Vue d'ensemble

### Objectif
Implémenter un système de tracking intelligent où le numéro FSB est disponible immédiatement après paiement, avec des statuts virtuels basés sur le temps jusqu'à ce qu'un vrai numéro de tracking soit associé par l'admin.

### Acteurs concernés
- **Client** : Recherche son colis via FSB immédiatement après paiement
- **Admin** : Associe le vrai tracking number au FSB (3-4 jours après)
- **Système** : Gère les transitions automatiques de statuts virtuels

### Valeur métier
- **Expérience client améliorée** : Le client peut suivre sa commande immédiatement après paiement
- **Transparence** : Le client voit toujours quelque chose, même avant que le vrai tracking soit disponible
- **Flexibilité** : Le système s'adapte automatiquement une fois le vrai tracking disponible

## 2. Architecture Laravel

### Modèles (Models)

#### SourcingOrder (modifications)
- [x] Attribut `fsb_tracking_number` existe déjà (calculé)
- [ ] Ajouter colonne `fsb_tracking_created_at` (timestamp de création du FSB)
- [ ] Ajouter colonne `real_tracking_assigned_at` (timestamp d'association du vrai tracking) - **CHAMP INTERNE, NON AFFICHÉ AU CLIENT**
- [ ] Ajouter `real_tracking_assigned_at` dans `$hidden` pour ne pas l'exposer dans les APIs
- [ ] Méthode `hasRealTracking()` : vérifie si un vrai tracking est associé
- [ ] Méthode `getVirtualTrackingStatus()` : retourne le statut virtuel basé sur le temps
- [ ] Méthode `shouldUseVirtualStatus()` : détermine si on doit utiliser le statut virtuel

#### Migration nécessaire
```php
Schema::table('sourcing_orders', function (Blueprint $table) {
    $table->timestamp('fsb_tracking_created_at')->nullable()->after('tracking_number');
    $table->timestamp('real_tracking_assigned_at')->nullable()->after('fsb_tracking_created_at');
});
```

### Services

#### Nouveau Service : `VirtualTrackingStatusService`
- [ ] Méthode `getVirtualStatus(SourcingOrder $order)` : Calcule le statut virtuel
  - Si < 24h depuis `fsb_tracking_created_at` → `shipment_preparing`
  - Si >= 24h depuis `fsb_tracking_created_at` → `in_transit_china`
- [ ] Méthode `shouldUseVirtualStatus(SourcingOrder $order)` : Vérifie si on doit utiliser le statut virtuel
  - Retourne `true` si `real_tracking_assigned_at` est null
  - Retourne `false` si un vrai tracking est associé

#### Modification : `UnifiedTrackingService`
- [ ] Modifier `resolveAlias()` pour gérer les statuts virtuels
  - Si pas de vrai tracking ET statut virtuel disponible → retourner statut virtuel
  - Si vrai tracking disponible → utiliser le vrai tracking (comportement actuel)
- [ ] Nouvelle méthode `getVirtualTrackingResponse()` : Retourne une réponse de tracking avec statut virtuel

### Événements & Listeners

#### Nouvel Événement : `SourcingOrderPaid`
- [ ] Déclencher quand le statut passe à `paid`
- [ ] Enregistrer `fsb_tracking_created_at` dans la base de données

#### Nouvel Listener : `InitializeFsbTracking`
- [ ] Écouter `SourcingOrderStatusChanged` quand nouveau statut = `paid`
- [ ] Définir `fsb_tracking_created_at = now()`
- [ ] Notifier le client que son FSB tracking est disponible

#### Modification : `SourcingOrderWorkflow` (Livewire)
- [ ] Quand admin entre un vrai tracking number
- [ ] Définir `real_tracking_assigned_at = now()`
- [ ] Notifier le client que le vrai tracking est maintenant actif

### Contrôleurs

#### Modification : `Client\TrackingController`
- [ ] Modifier `index()` pour gérer les statuts virtuels
- [ ] Si statut virtuel → afficher le statut calculé
- [ ] Si vrai tracking → utiliser le comportement actuel

### Routes
- [x] Routes existantes suffisent (pas de nouvelles routes nécessaires)

### Policies & Autorisations
- [x] Pas de changement nécessaire (les autorisations existantes suffisent)

### Vues (Blade)

#### Modification : `client/tracking/index.blade.php`
- [ ] Afficher un indicateur visuel si c'est un statut virtuel
- [ ] Message informatif : "En attente du numéro de tracking réel"
- [ ] Afficher le statut virtuel avec une icône différente
- [ ] **NE PAS** afficher `real_tracking_assigned_at` (champ interne uniquement)

#### Modification : `client/sourcing-orders/show.blade.php`
- [ ] Afficher le FSB tracking number dès que le statut est `paid`
- [ ] Indiquer visuellement si c'est un statut virtuel ou réel
- [ ] **NE PAS** afficher `real_tracking_assigned_at` (champ interne uniquement)

### Tests
- [ ] Test Feature : Vérifier que FSB tracking est disponible après paiement
- [ ] Test Feature : Vérifier transition automatique après 24h
- [ ] Test Feature : Vérifier que vrai tracking remplace le virtuel
- [ ] Test Unit : `VirtualTrackingStatusService` - calcul des statuts

## 3. Étapes d'Implémentation (par priorité)

### Phase 1 : Fondations (Base de données & Modèle)
- [ ] Créer migration pour `fsb_tracking_created_at` et `real_tracking_assigned_at`
- [ ] Ajouter les colonnes au modèle `SourcingOrder`
- [ ] Ajouter `real_tracking_assigned_at` dans `$hidden` pour ne pas l'exposer au client
- [ ] Ajouter les méthodes helper dans `SourcingOrder` :
  - `hasRealTracking()`
  - `getVirtualTrackingStatus()`
  - `shouldUseVirtualStatus()`

### Phase 2 : Service de Statuts Virtuels
- [ ] Créer `VirtualTrackingStatusService`
- [ ] Implémenter la logique de calcul des statuts basée sur le temps
- [ ] Tester le service avec différents scénarios temporels

### Phase 3 : Intégration avec UnifiedTrackingService
- [ ] Modifier `resolveAlias()` pour gérer les statuts virtuels
- [ ] Créer `getVirtualTrackingResponse()` pour retourner les réponses virtuelles
- [ ] S'assurer que le vrai tracking prend le dessus quand disponible

### Phase 4 : Événements & Listeners
- [ ] Créer listener `InitializeFsbTracking`
- [ ] Enregistrer dans `EventServiceProvider`
- [ ] Tester que `fsb_tracking_created_at` est défini au bon moment

### Phase 5 : Interface Admin
- [ ] Modifier `SourcingOrderWorkflow` pour définir `real_tracking_assigned_at`
- [ ] Ajouter notification quand vrai tracking est associé

### Phase 6 : Interface Client
- [ ] Modifier les vues pour afficher les statuts virtuels
- [ ] Ajouter indicateurs visuels pour différencier virtuel/réel
- [ ] Améliorer les messages informatifs

### Phase 7 : Tests & Optimisations
- [ ] Écrire tests complets
- [ ] Optimiser les requêtes si nécessaire
- [ ] Vérifier les performances

## 4. Considérations Techniques

### Performance
- **Indexes** : Ajouter index sur `fsb_tracking_created_at` pour les requêtes temporelles
- **Cache** : Considérer cache pour les statuts virtuels (TTL court)
- **Queries** : Éviter N+1 queries lors de la récupération des statuts

### Sécurité
- **Validation** : Valider que seul un admin peut associer un vrai tracking
- **Autorisations** : Vérifier les permissions avant de modifier les timestamps
- **Champs cachés** : `real_tracking_assigned_at` doit être dans `$hidden` du modèle pour ne pas être exposé au client
- **API** : S'assurer que les réponses JSON n'incluent pas `real_tracking_assigned_at` pour les clients

### Intégrations
- **Notifications** : Notifier le client quand :
  1. FSB tracking devient disponible
  2. Vrai tracking est associé
- **Tracking Providers** : S'assurer que les providers existants fonctionnent toujours

## 5. Logique de Statuts Virtuels

### Timeline
```
T+0h  : Order paid → FSB disponible → Status: "Shipping Preparing"
T+24h : Automatiquement → Status: "In Transit China"
T+3-4j: Admin entre vrai tracking → Status: Utilise vrai tracking (remplace virtuel)
```

### Calcul du Statut Virtuel
```php
public function getVirtualStatus(SourcingOrder $order): string
{
    if (!$order->fsb_tracking_created_at) {
        return 'pending_payment'; // Pas encore payé
    }
    
    $hoursSinceCreation = now()->diffInHours($order->fsb_tracking_created_at);
    
    if ($hoursSinceCreation < 24) {
        return 'shipment_preparing';
    }
    
    return 'in_transit_china';
}
```

### Résolution de Tracking
```php
protected function resolveAlias(string $trackingNumber, ?string $carrier): array
{
    if (str_starts_with(strtoupper($trackingNumber), 'FSB')) {
        $order = SourcingOrder::find($id);
        
        // Si pas de vrai tracking ET statut virtuel disponible
        if (!$order->hasRealTracking() && $order->shouldUseVirtualStatus()) {
            return [
                $trackingNumber,
                null,
                true,
                $this->getVirtualTrackingResponse($order)
            ];
        }
        
        // Sinon, comportement actuel (vrai tracking)
        if ($order->tracking_number) {
            return [
                $order->tracking_number,
                $order->tracking_carrier,
                true,
                null
            ];
        }
        
        // Pas de tracking du tout
        return [/* error */];
    }
}
```

## 6. Points d'Attention

### Contraintes existantes
- Le système actuel retourne une erreur si pas de tracking number réel
- Il faut modifier `UnifiedTrackingService` pour gérer les cas virtuels

### Dépendances
- Dépend de l'événement `SourcingOrderStatusChanged`
- Dépend de `UnifiedTrackingService` existant

### Migrations de données
- Pour les commandes existantes avec statut `paid` :
  - Définir `fsb_tracking_created_at = created_at` (ou `updated_at` si plus récent)
  - Si déjà un `tracking_number` → définir `real_tracking_assigned_at = updated_at`

### Questions à clarifier
1. **Notification** : Le client doit-il être notifié quand le statut virtuel change après 24h ?
2. **Rétroactivité** : Comment gérer les commandes déjà payées avant cette implémentation ?
3. **Statuts manuels** : Si l'admin change manuellement le statut, doit-il remplacer le statut virtuel ?
4. **Annulation** : Que se passe-t-il si la commande est annulée pendant le statut virtuel ?

## 7. Structure des Fichiers

```
app/
├── Models/
│   └── SourcingOrder.php (modifications)
├── Services/
│   ├── Tracking/
│   │   ├── UnifiedTrackingService.php (modifications)
│   │   └── VirtualTrackingStatusService.php (nouveau)
├── Events/
│   └── SourcingOrderPaid.php (nouveau, optionnel)
├── Listeners/
│   └── InitializeFsbTracking.php (nouveau)
└── Livewire/
    └── Admin/
        └── SourcingOrderWorkflow.php (modifications)

database/
└── migrations/
    └── YYYY_MM_DD_HHMMSS_add_fsb_tracking_timestamps_to_sourcing_orders.php (nouveau)

resources/
└── views/
    ├── client/
    │   ├── tracking/
    │   │   └── index.blade.php (modifications)
    │   └── sourcing-orders/
    │       └── show.blade.php (modifications)
```

## 8. Exemple de Réponse de Tracking Virtuel

```php
[
    'success' => true,
    'tracking_number' => 'FSB000123',
    'status' => 'shipment_preparing', // ou 'in_transit_china'
    'status_text' => 'Shipping Preparing', // Traduit
    'is_virtual' => true,
    'virtual_until' => '2026-02-20T10:00:00Z', // Quand le vrai tracking sera disponible
    'message' => 'Your order is being prepared. Real tracking number will be available soon.',
    'events' => [
        [
            'date' => '2026-02-19T10:00:00Z',
            'status' => 'shipment_preparing',
            'description' => 'Order confirmed and being prepared for shipment',
            'location' => 'China'
        ]
    ],
    'provider' => 'FSB',
    'last_updated_at' => now()->toIso8601String(),
    'source' => 'virtual'
]
```

## 9. Confidentialité des Données

### Champs Internes (Non exposés au client)
- `real_tracking_assigned_at` : **CHAMP INTERNE UNIQUEMENT**
  - Enregistré en base de données pour la logique système
  - **NE DOIT PAS** apparaître dans les réponses API pour les clients
  - **NE DOIT PAS** être affiché dans les vues client
  - Ajouté dans `$hidden` du modèle `SourcingOrder`
  - Utilisé uniquement par le système pour déterminer si on utilise le tracking virtuel ou réel

### Champs Visibles au Client
- `fsb_tracking_number` : Visible et utilisable par le client
- `tracking_number` : Visible une fois assigné par l'admin
- Statuts de tracking : Visibles mais calculés différemment selon le contexte

## 10. Checklist de Validation

Avant de déployer :
- [ ] Migration testée sur environnement de développement
- [ ] `real_tracking_assigned_at` ajouté dans `$hidden` du modèle
- [ ] Vérifier que `real_tracking_assigned_at` n'apparaît pas dans les réponses JSON pour les clients
- [ ] Vérifier que `real_tracking_assigned_at` n'apparaît pas dans les vues Blade client
- [ ] Service de statuts virtuels testé avec différents timestamps
- [ ] Intégration avec `UnifiedTrackingService` fonctionnelle
- [ ] Listener déclenché correctement au paiement
- [ ] Interface admin permet d'associer le vrai tracking
- [ ] Interface client affiche correctement les statuts virtuels
- [ ] Notifications envoyées aux bons moments
- [ ] Tests Feature passent
- [ ] Tests Unit passent
- [ ] Migration de données pour commandes existantes testée
