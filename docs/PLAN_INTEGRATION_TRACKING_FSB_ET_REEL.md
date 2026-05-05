# Plan d’intégration – Tracking FSB puis tracking réel

**Document** : Plan d’intégration  
**Objectif** : Décrire le flux complet depuis le paiement jusqu’à l’affichage du tracking réel au client, sans intervention admin pour la phase initiale.  
**Format** : Markdown (ouvrable dans Word / exportable en .docx).

---

## 1. Résumé du flux

| Étape | Qui | Quand | Résultat côté client |
|--------|-----|--------|------------------------|
| 1 | Système | Statut commande = **paid** | Numéro FSB généré et envoyé au client (aucune saisie admin) |
| 2 | Client | Recherche avec le numéro FSB | Affichage **Shipping preparing** |
| 3 | Client | Nouvelle recherche après **24 h** | Affichage **In transit China** |
| 4 | Admin | Saisit le **vrai** numéro de tracking + nom du transporteur | — |
| 5 | Client | Recherche avec le numéro FSB (ou le numéro réel) | Affichage des **résultats réels** du transporteur |

---

## 2. Détail des étapes

### 2.1 Statut commande = **paid** → génération FSB et envoi au client (sans admin)

- **Déclencheur** : la commande passe au statut `paid`.
- **Actions automatiques (sans saisie admin)** :
  - Génération / association du **numéro FSB** (ex. `FSB44` pour la commande 44).
  - Enregistrement de la date de création du tracking FSB (`fsb_tracking_created_at`).
  - **Aucun** numéro de tracking réel ni nom de transporteur saisis à ce stade.
- **Notification client** :
  - Email + notification in-app (et push si FCM configuré) pour indiquer que le **numéro FSB** est prêt.
  - Lien vers la page de suivi (tracking) avec ce numéro.
- **Fichiers concernés** :
  - `App\Listeners\InitializeFsbTracking` (écoute `SourcingOrderStatusChanged` lorsque le statut devient `paid`).
  - `App\Notifications\FsbTrackingGenerated` (envoi au client).

**Règle** : tant que l’admin n’a pas renseigné le numéro de tracking réel et le transporteur, le client ne voit que le tracking **virtuel** (étapes 2.2 et 2.3).

---

### 2.2 Client recherche avec le numéro FSB → affichage « Shipping preparing »

- **Quand** : le client utilise le numéro FSB (ex. `FSB44`) dans la recherche de suivi, **avant** 24 h après `fsb_tracking_created_at`.
- **Comportement** :
  - Le système reconnaît le préfixe `FSB` et charge la commande correspondante.
  - Comme aucun tracking réel n’est encore associé (`real_tracking_assigned_at` est vide), le **tracking virtuel** est utilisé.
  - Statut affiché : **Shipping preparing** (préparation de l’expédition).
- **Fichiers concernés** :
  - `App\Services\Tracking\VirtualTrackingStatusService` : `getVirtualStatus()` (si < 24 h → `shipment_preparing`).
  - `App\Services\Tracking\UnifiedTrackingService` : `resolveAlias()` pour les numéros `FSB*` et appel au service virtuel si pas de tracking réel.

**Règle** : aucun numéro réel ni nom de transporteur n’est affiché à ce stade ; le client voit uniquement le statut virtuel et le numéro FSB.

---

### 2.3 Même recherche après 24 h → affichage « In transit China »

- **Quand** : le client recherche à nouveau avec le numéro FSB, **au moins 24 h** après `fsb_tracking_created_at`, et toujours **sans** que l’admin ait saisi le tracking réel.
- **Comportement** :
  - Toujours tracking virtuel (car pas de `real_tracking_assigned_at`).
  - Statut affiché : **In transit China** (en transit depuis la Chine).
- **Fichiers concernés** :
  - `VirtualTrackingStatusService::getVirtualStatus()` : si `diffInHours(fsb_tracking_created_at) >= 24` → `in_transit_china`.

**Règle** : le passage « Shipping preparing » → « In transit China » est **automatique** dans le temps ; aucune action admin.

---

### 2.4 Admin saisit le numéro de tracking réel et le transporteur

- **Quand** : l’admin dispose du vrai numéro de suivi (fournisseur / transporteur).
- **Actions** :
  - Saisie du **numéro de tracking réel** (ex. `ME49508327`).
  - Saisie ou sélection du **nom du transporteur** (shipping company).
  - Sauvegarde : mise à jour de la commande (`tracking_number`, `shipping_company_id` ou champ transporteur, et **`real_tracking_assigned_at`** = maintenant).
- **Effet** :
  - Dès ce moment, les recherches de suivi pour cette commande (par FSB ou par numéro réel) utilisent les **données réelles** du transporteur (étape 2.5).
- **Fichiers concernés** :
  - Contrôleur admin qui gère la mise à jour du tracking (ex. `SourcingOrderController::updateTracking()` ou équivalent Livewire).
  - Modèle `SourcingOrder` : champs `tracking_number`, `real_tracking_assigned_at`, et relation transporteur.

**Règle** : après cette saisie, le client ne voit plus le tracking virtuel pour cette commande ; il voit le tracking réel (étape 2.5).

---

### 2.5 Client recherche après saisie du tracking réel → résultats réels du transporteur

- **Quand** : après que l’admin a enregistré le numéro réel et le transporteur.
- **Comportement** :
  - Si le client cherche avec le **numéro FSB** : le système résout l’alias FSB, voit que `real_tracking_assigned_at` est renseigné, et utilise le **numéro réel** + le transporteur pour interroger le fournisseur de tracking (API transporteur, etc.).
  - Si le client cherche avec le **numéro réel** : appel direct au service de tracking avec ce numéro (et éventuellement le transporteur).
  - L’écran client affiche les **événements réels** du transporteur (colis pris en charge, en transit, arrivée hub, etc.).
- **Fichiers concernés** :
  - `UnifiedTrackingService::resolveAlias()` : pour FSB, si `hasRealTracking()` → utilisation de `tracking_number` + transporteur.
  - Services de tracking réels (Aftership, FSB, etc.) pour récupérer les événements.

**Règle** : une fois le tracking réel associé, le client ne voit plus « Shipping preparing » / « In transit China » virtuels ; il voit uniquement les statuts réels du transporteur.

---

## 3. Synthèse des règles métier

1. **Pas d’admin pour la phase initiale** : à partir du statut **paid**, le numéro FSB est généré et envoyé au client sans saisie de numéro réel ni de nom de transporteur.
2. **Recherche client avec FSB** :
   - Avant 24 h : affichage **Shipping preparing**.
   - Après 24 h (toujours sans tracking réel) : affichage **In transit China**.
3. **Admin** : saisit le **numéro de tracking réel** et le **nom du transporteur** quand il les a.
4. **Après saisie admin** : les recherches (FSB ou numéro réel) affichent les **résultats réels** du transporteur.

---

## 4. Éléments techniques existants (référence)

- **Modèle** : `SourcingOrder` – `fsb_tracking_number` (calculé), `fsb_tracking_created_at`, `tracking_number`, `real_tracking_assigned_at`, relation transporteur.
- **Listener** : `InitializeFsbTracking` (statut `paid` → création FSB + notification).
- **Notification** : `FsbTrackingGenerated` (email, database, FCM).
- **Services** : `VirtualTrackingStatusService` (statuts virtuels &lt; 24 h / ≥ 24 h), `UnifiedTrackingService` (résolution FSB → virtuel ou réel).
- **Documentation détaillée** : `docs/FSB_TRACKING_VIRTUAL_STATUS_PLAN.md`.

---

## 5. Checklist d’intégration (vérifications)

- [x] Au passage en **paid**, le numéro FSB est bien créé et la notification envoyée au client sans aucune saisie admin.
- [x] Recherche client avec FSB avant 24 h → affichage **Shipping preparing**.
- [x] Recherche client avec FSB après 24 h (sans tracking réel) → affichage **In transit China**.
- [x] L’admin peut saisir le **numéro de tracking réel** et le **nom du transporteur** (écran dédié ou workflow commande).
- [x] Après cette saisie, `real_tracking_assigned_at` est renseigné.
- [x] Recherche client (FSB ou numéro réel) après saisie admin → affichage des **résultats réels** du transporteur.
- [x] Le champ `real_tracking_assigned_at` n’est pas exposé au client (API / vues).

---

## 6. Conversion en fichier Word

Pour obtenir un fichier .docx à partir de ce plan :

- **Option 1** : Ouvrir ce fichier `.md` dans Word (certaines versions importent le Markdown).
- **Option 2** : Utiliser un convertisseur en ligne ou une commande (ex. Pandoc) :  
  `pandoc PLAN_INTEGRATION_TRACKING_FSB_ET_REEL.md -o PLAN_INTEGRATION_TRACKING_FSB_ET_REEL.docx`

Ce document peut servir de référence unique pour l’intégration du flux FSB → tracking virtuel (24 h) → tracking réel après saisie admin.
