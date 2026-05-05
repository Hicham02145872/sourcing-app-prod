# Améliorations Proposées - Système de Tracking

## État Actuel du Système

### **Fonctionnalités Existantes** ✅

1. **Deux Intégrations API**:
   - **Faster.ae** : Tracking via endpoint `/service/status-logs/listWithBooking`
   - **17TRACK** : Tracking global via service `SeventeenTrackService`

2. **Pages de Suivi Client**:
   - `/client/tracking` - Interface pour Faster.ae
   - `/client/tracking/17track` - Interface pour 17TRACK
   - Design premium avec timeline visuelle

3. **Service 17TRACK** (`SeventeenTrackService.php`):
   - Auto-registration des numéros non trouvés
   - Détection automatique du transporteur
   - Support multi-carriers (GCC56, J&T Express, etc.)
   - Retry logic intelligent

4. **Workflow Admin**:
   - Ajout manuel de numéro de suivi via `SourcingOrderWorkflow`
   - Champs: `tracking_number` et `tracking_carrier`

---

## 🚀 Améliorations Prioritaires

### 1. **Notifications Automatiques de Changement de Statut** 🔔

#### **Problème Actuel**
Les clients doivent vérifier manuellement le statut de leur colis.

#### **Solution Proposée**
- **Job Planifié** (Cron) qui vérifie les statuts toutes les heures
- **Détection de Changements** : comparer le statut actuel avec le précédent
- **Notifications Multi-Canal** :
  - 📧 Email
  - 💬 Notification in-app (base de données)
  - 📱 Push FCM (si token disponible)

#### **Implémentation**

**Nouvelle Table** : `tracking_status_history`
```sql
CREATE TABLE tracking_status_history (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    sourcing_order_id BIGINT NOT NULL,
    tracking_number VARCHAR(255) NOT NULL,
    status VARCHAR(255) NOT NULL,
    location VARCHAR(255) NULL,
    status_date TIMESTAMP NULL,
    raw_data JSON NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sourcing_order_id) REFERENCES sourcing_orders(id) ON DELETE CASCADE,
    INDEX idx_order_tracking (sourcing_order_id, created_at)
);
``

`

**Nouveau Job** : `app/Jobs/SyncTrackingStatusJob.php`
```php
// Vérifie toutes les commandes "en transit"
// Compare avec le dernier statut enregistré
// Si changement → envoie notification
```

**Notification** : `app/Notifications/TrackingStatusUpdated.php`
```php
// Channels: mail, database, fcm
// Data: old_status, new_status, location, date
```

**Avantages** :
- ⭐⭐⭐⭐⭐ Impact client très élevé
- Engagement proactif
- Réduction des demandes de support

---

### 2. **Bouton Admin "Rafraîchir le Statut" 🔄**

#### **Problème Actuel**
L'admin ne peut pas forcer une mise à jour immédiate.

#### **Solution Proposée**
Ajouter un bouton dans `admin/sourcing-orders/show` qui :
1. Appelle `SeventeenTrackService::getTrackInfo()` ou API Faster.ae
2. Met à jour instantanément la base de données
3. Affiche le nouveau statut 

#### **Implémentation**

**Nouveau Controller** : `AdminTrackingController.php`
```php
public function refresh(SourcingOrder $order)
{
    $result = $this->seventeenTrackService->getTrackInfo($order->tracking_number);
    
    if (success) {
        $order->update(['tracking_status' => $result['latest_status']]);
        // Enregistrer dans tracking_status_history
    }
    
    return back()->with('success', 'Statut rafraîchi');
}
```

**Vue** : Dans `admin/sourcing-orders/show.blade.php`
```blade
<button wire:click="refreshTracking" 
        class="btn btn-secondary">
    🔄 Rafraîchir le statut
</button>
```

**Avantages** :
- Contrôle immédiat pour l'admin
- Utile en cas de problème webhook
- Débogage facile

---

### 3. **Estimation de Livraison (ETA) 📅**

#### **Problème Actuel**
Aucune indication de date de livraison estimée.

#### **Solution Proposée**
- **Calcul Basé sur l'Historique** : analyser les commandes passées par transporteur/destination
- **Affichage Dynamique** : badge "Livraison estimée : 3-5 jours"

#### **Implémentation**

**Nouvelle Colonne** : `sourcing_orders.estimated_delivery_date`

**Service** : `app/Services/DeliveryEstimationService.php`
```php
public function estimate(SourcingOrder $order): Carbon
{
    // Rechercher commandes similaires (même transporteur + même pays)
    $avgDays = SourcingOrder::where('tracking_carrier', $order->tracking_carrier)
        ->where('destination_country', $order->destination_country)
        ->whereNotNull('delivered_at')
        ->avg(DB::raw('DATEDIFF(delivered_at, shipped_at)'));
    
    return $order->shipped_at->addDays($avgDays ?? 7);
}
```

**Affichage Client** :
```blade
<div class="eta-badge">
    📅 Livraison estimée: {{ $order->estimated_delivery_date->format('d M Y') }}
</div>
```

**Avantages** :
- Information très demandée par les clients
- Améliore la transparence
- Réduit les inquiétudes

---

### 4. **Dashboard Logistique Admin 📊**

#### **Problème Actuel**
Pas de vue d'ensemble des expéditions en cours.

#### **Solution Proposée**
Widget sur le tableau de bord admin montrant :
- 🚚 En transit : 12
- ⚠️ Bloqué en douane : 2
- ✅ Livrés cette semaine : 45
- 🔴 Retardés (>10 jours) : 3

#### **Implémentation**

**Controller** : `AdminDashboardController.php`
```php
$trackingStats = [
    'in_transit' => SourcingOrder::where('status', 'shipped')->count(),
    'customs_delay' => SourcingOrder::where('tracking_status', 'LIKE', '%customs%')->count(),
    'delivered_this_week' => SourcingOrder::where('status', 'delivered')
        ->whereBetween('delivered_at', [now()->startOfWeek(), now()])->count(),
    'delayed' => SourcingOrder::where('status', 'shipped')
        ->where('shipped_at', '<', now()->subDays(10))->count(),
];
```

**Vue** : Widget Cards dans `admin/dashboard.blade.php`

**Avantages** :
- Visibilité instantanée
- Identifier rapidement les problèmes
- KPIs logistiques

---

### 5. **Suivi Multi-Colis 📦📦**

#### **Problème Actuel**
Une commande ne peut avoir qu'un seul numéro de suivi.

#### **Solution Proposée**
Permettre plusieurs numéros de suivi par commande.

#### **Implémentation**

**Nouvelle Table** : `tracking_numbers`
```sql
CREATE TABLE tracking_numbers (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    sourcing_order_id BIGINT NOT NULL,
    tracking_number VARCHAR(255) NOT NULL,
    carrier VARCHAR(255) NULL,
    status VARCHAR(255) NULL,
    created_at TIMESTAMP,
    FOREIGN KEY (sourcing_order_id) REFERENCES sourcing_orders(id) ON DELETE CASCADE
);
```

**Relation Eloquent** :
```php
// SourcingOrder.php
public function trackingNumbers()
{
    return $this->hasMany(TrackingNumber::class);
}
```

**UI Admin** :
```blade
<!-- Bouton "Ajouter un numéro de suivi" -->
<!-- Liste des numéros existants avec bouton supprimer -->
```

**Avantages** :
- Gestion des commandes partielles
- Flexibilité pour expéditions multiples
- Tracking précis

---

### 6. **Webhook 17TRACK (Push vs Pull) 🔗**

#### **Problème Actuel**
Actuellement en "Pull" (on interroge l'API).

#### **Solution Proposée**
Configurer un Webhook pour recevoir les mises à jour automatiquement.

#### **Implémentation**

**Route** : `routes/web.php`
```php
Route::post('/webhooks/17track', [WebhookController::class, 'handle17Track'])
    ->name('webhooks.17track')
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
```

**Controller** : `WebhookController.php`
```php
public function handle17Track(Request $request)
{
    $payload = $request->all();
    
    // Vérifier signature 17TRACK
    // Trouver la commande par tracking_number
    // Mettre à jour le statut
    // Envoyer notification client
    
    return response()->json(['status' => 'ok']);
}
```

**Configuration 17TRACK** :
- URL Callback : `https://votredomaine.com/webhooks/17track`
- Events : Status Change, Delivered, Exception

**Avantages** :
- Temps réel au lieu d'horaire
- Économie de quota API
- Expérience client améliorée

---

### 7. **Carte Interactive (Map) 🗺️**

#### **Problème Actuel**
Timeline textuelle uniquement.

#### **Solution Proposée**
Afficher le parcours du colis sur une carte (Google Maps / Mapbox).

#### **Implémentation**

**Service** : `app/Services/GeocodingService.php`
```php
// Convertir les noms de villes en coordonnées GPS
public function geocode(string $location): array
{
    // Appel API Google Geocoding
    return ['lat' => 48.8566, 'lng' => 2.3522];
}
```

**Vue** : `client/tracking/map.blade.php`
```blade
<div id="map" style="height: 400px;"></div>

<script>
// Afficher les points de passage sur Google Maps
// Relier les points avec une polyline
</script>
```

**Avantages** :
- "Wow" factor élevé
- Visualisation intuitive
- Différenciation concurrentielle

---

### 8. **Export PDF / Preuve de Livraison 📄**

#### **Problème Actuel**
Pas de preuve téléchargeable.

#### **Solution Proposée**
Bouton "Télécharger la preuve de livraison" générant un PDF avec :
- Numéro de suivi
- Timeline complète
- Date/heure de livraison
- Signature (si disponible)

#### **Implémentation**

**Package** : `barryvdh/laravel-dompdf`

**Controller** :
```php
public function downloadProof(SourcingOrder $order)
{
    $pdf = PDF::loadView('client.tracking.pdf', compact('order'));
    return $pdf->download("tracking-{$order->tracking_number}.pdf");
}
```

**Avantages** :
- Justificatif pour clients B2B
- Professionnalisme
- Archives

---

### 9. **Alertes d'Anomalies ⚠️**

#### **Problème Actuel**
Pas de détection proactive des problèmes.

#### **Solution Proposée**
Job qui détecte et alerte :
- Colis bloqué en douane (>3 jours)
- Aucun mouvement depuis 48h
- Exception signalée par le transporteur

#### **Implémentation**

**Job** : `DetectTrackingAnomaliesJob.php`
```php
// Identifier les commandes suspectes
// Envoyer notification admin + super admin
// Marquer la commande avec un flag "needs_attention"
```

**Notification Admin** :
```
⚠️ Alerte Logistique
Commande #123: Aucun mouvement depuis 3 jours
Tracking: ME49508327
Action requise
```

**Avantages** :
- Support proactif
- Résolution rapide des problèmes
- Satisfaction client

---

### 10. **Intégration WhatsApp (Bonus) 📱**

#### **Problème Actuel**
Communication uniquement par email.

#### **Solution Proposée**
Envoyer les mises à jour de tracking par WhatsApp via Twilio API.

#### **Implémentation**

**Package** : `twilio/sdk`

**Notification** : `TrackingStatusUpdatedWhatsApp.php`
```php
public function toWhatsApp($notifiable)
{
    return (new TwilioMessage)
        ->content("🚚 Mise à jour: Votre colis est maintenant {$this->status}. Suivi: {$this->tracking_number}");
}
```

**Avantages** :
- Canal préféré dans beaucoup de pays
- Taux d'ouverture >90%
- Engagement élevé

---

## Résumé des Priorités

| Priorité | Fonctionnalité | Impact Client | Effort Dev | ROI |
|----------|----------------|---------------|------------|-----|
| 🔴 **Haute** | Notifications Auto (Changement Statut) | ⭐⭐⭐⭐⭐ | Moyen | 🟢 Élevé |
| 🔴 **Haute** | Bouton Admin "Rafraîchir" | ⭐⭐⭐ | Faible | 🟢 Élevé |
| 🟠 **Moyenne** | Estimation Livraison (ETA) | ⭐⭐⭐⭐ | Moyen | 🟡 Moyen |
| 🟠 **Moyenne** | Dashboard Logistique Admin | ⭐⭐⭐ | Faible | 🟡 Moyen |
| 🟠 **Moyenne** | Webhook 17TRACK | ⭐⭐⭐⭐ | Moyen | 🟢 Élevé |
| 🟢 **Basse** | Carte Interactive | ⭐⭐⭐⭐⭐ | Élevé | 🟡 Moyen |
| 🟢 **Basse** | Suivi Multi-Colis | ⭐⭐⭐ | Moyen | 🟡 Moyen |
| 🟢 **Basse** | Export PDF | ⭐⭐ | Faible | 🔴 Faible |
| 🟢 **Basse** | Alertes d'Anomalies | ⭐⭐⭐ | Faible | 🟡 Moyen |
| 🔵 **Nice-to-Have** | WhatsApp Integration | ⭐⭐⭐⭐ | Élevé | 🟡 Moyen |

---

## Plan de Mise en Œuvre Suggéré

### **Phase 1** (Sprint 1-2) : Fondations
1. ✅ Créer table `tracking_status_history`
2. ✅ Implémenter `SyncTrackingStatusJob`
3. ✅ Notification `TrackingStatusUpdated`
4. ✅ Bouton admin "Rafraîchir le suivi"

### **Phase 2** (Sprint 3-4) : Intelligence
5. ✅ Service `DeliveryEstimationService`
6. ✅ Dashboard logistique avec KPIs
7. ✅ Configuration Webhook 17TRACK

### **Phase 3** (Sprint 5-6) : Expérience Premium
8. ✅ Carte interactive (si budget le permet)
9. ✅ Suivi multi-colis
10. ✅ Alertes d'anomalies

---

## Fichiers à Créer/Modifier

### **Nouveaux Fichiers**
- `app/Jobs/SyncTrackingStatusJob.php`
- `app/Jobs/DetectTrackingAnomaliesJob.php`
- `app/Notifications/TrackingStatusUpdated.php`
- `app/Services/DeliveryEstimationService.php`
- `app/Services/GeocodingService.php`
- `app/Http/Controllers/Admin/AdminTrackingController.php`
- `app/Http/Controllers/WebhookController.php`
- `app/Models/TrackingStatusHistory.php`
- `app/Models/TrackingNumber.php` (si multi-colis)
- `resources/views/client/tracking/map.blade.php`
- `resources/views/client/tracking/pdf.blade.php`
- `database/migrations/xxxx_create_tracking_status_history_table.php`
- `database/migrations/xxxx_create_tracking_numbers_table.php`

### **Fichiers à Modifier**
- `app/Models/SourcingOrder.php` (relations)
- `app/Http/Controllers/Client/TrackingController.php` (endpoints)
- `app/Http/Controllers/Admin/AdminDashboardController.php` (widgets)
- `resources/views/admin/sourcing-orders/show.blade.php` (bouton rafraîchir)
- `resources/views/admin/dashboard.blade.php` (dashboard logistique)
- `resources/views/client/tracking/index.blade.php` (ETA, carte)
- `routes/web.php` (webhooks)
- `app/Console/Kernel.php` (cron jobs)

---

## Conclusion

Ces améliorations transformeront le système de tracking en un **outil premium** offrant :
- 🎯 **Proactivité** : notifications automatiques
- 🚀 **Performance** : webhooks temps réel
- 📊 **Insights** : analytics et alertes
- ⭐ **Expérience** : cartes, ETA, multi-canal

**Recommandation** : Commencer par les **3 premières priorités** pour un impact rapide et mesurable. 🚀
