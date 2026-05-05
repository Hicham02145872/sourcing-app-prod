# Analyse du module Remboursements (Refund) et améliorations

## 1. Synthèse du flux actuel

### Côté client
- **Liste** (`client.refund-requests.index`) : commandes livrées éligibles, demandes en attente/approuvées, stats (éligibles, en attente, approuvées, total remboursé).
- **Création** : uniquement pour les commandes `delivered`, avec calcul du montant restant (total − somme des `amount_approved` déjà approuvés). Types full/partial, catégorie de raison, description, quantité endommagée, preuves (images/vidéos).
- **Détail** (`client.refund-requests.show`) : statut, message support si approuvé/rejeté, montant approuvé, preuve remboursement, preuves client.
- **Autorisation** : `SourcingOrderPolicy::requestRefund` (propriétaire de la commande) ; `RefundRequestPolicy` pour voir/modifier les demandes.

### Côté admin
- **Liste** (`admin.refund-requests.index`) : KPIs (Pending, Approved, Total Refunded), filtres (statut, admin assigné, montant min, date), pagination. Les admins standards ne voient que leurs demandes assignées ou non assignées.
- **Détail** (`admin.refund-requests.show`) : infos client, type/catégorie/montant demandé, comparaison visuelle 3-way (original / sourcé / preuves client), panneau Décision (Approuver / Rejeter), montant approuvé, modèles de réponses, preuve de remboursement.
- **Actions** : `updateStatus` (approved/rejected), `assignToMe` (uniquement si pas encore assigné).
- **Autorisation** : `RefundRequestPolicy` (super_admin voit tout ; admin voit assigné ou non assigné).

### Règles métier
- Une commande peut avoir plusieurs demandes de remboursement (partiels successifs).
- À l’approbation : somme des `amount_approved` (y compris la demande en cours) ne doit pas dépasser `sourcing_order.total_amount`.
- Statuts de la demande : `pending`, `under_review`, `approved`, `processed`, `rejected` (en base). Dans le code, seuls **pending**, **approved**, **rejected** sont utilisés.
- Statuts commande : `waiting_for_refund` à la création d’une demande ; `refund_approved` ou `refunded` à l’approbation ; `refund_rejected` au rejet.
- Notifications : à la mise à jour de la demande, `RefundRequestUpdated` → `SendRefundStatusNotification` → `RefundStatusUpdated` (mail, database, FCM si token).

---

## 2. Points forts existants

- Policy dédiée et `authorize()` dans les contrôleurs.
- Vérification du plafond de remboursement (somme des approuvés + montant en cours).
- Notifications multi-canal (mail, BDD, FCM) au changement de statut.
- Filtres admin (statut, admin, montant, date) et KPIs.
- Rapport financier admin : `FinancialReportController::refunds()` avec périodes et totaux.
- Feature flag `refunds` pour activer/désactiver le module côté client.
- Config `config/refunds.php` (ex. `auto_approve_limit` prévu pour plus tard).

---

## 3. Améliorations recommandées (par priorité)

### Priorité haute

| # | Amélioration | Détail |
|---|--------------|--------|
| 1 | **Montant remboursé cumulé sur la commande** | À l’approbation, mettre `sourcing_order.refund_amount` à la **somme** de tous les `amount_approved` des demandes approuvées pour cette commande (au lieu du seul montant de la demande courante). *Correction prévue dans le code.* |
| 2 | **Utiliser le statut `under_review`** | Quand un admin clique « Assign to me », passer la demande en `under_review` pour distinguer « non traitée » et « en cours d’examen ». Ajouter `under_review` dans les filtres et KPIs admin. |
| 3 | **Filtre admin : statut `under_review`** | Ajouter l’option « Under review » dans le select statut de la liste admin. |

### Priorité moyenne

| # | Amélioration | Détail |
|---|--------------|--------|
| 4 | **Timeline client** | Afficher une timeline (soumission → en cours / assignation → décision) sur `client.refund-requests.show` pour suivre l’avancement (idéalement réutiliser ou étendre un TimelineService existant). |
| 5 | **Nom produit + miniature dans la liste admin** | Dans `admin.refund-requests.index`, afficher le nom du produit et une miniature (depuis `sourcingOrder.quotation.sourcingRequest`) pour identifier rapidement la commande. |
| 6 | **Réassignation** | Permettre à un super_admin de réassigner une demande (nouvelle route ou champ « Assign to » avec liste d’admins), pas seulement « Assign to me » quand `assigned_to_admin_id` est null. |
| 7 | **Montant restant remboursable** | Sur la fiche commande (admin) et dans le formulaire de création (client), afficher clairement le « montant restant remboursable » (total − somme des `amount_approved` approuvés). |

### Priorité basse

| # | Amélioration | Détail |
|---|--------------|--------|
| 8 | **Statut `processed`** | Optionnel : utiliser `processed` quand la preuve de remboursement est uploadée (ou après un délai), et notifier le client « Remboursement effectué ». |
| 9 | **Auto-approbation des petits montants** | Implémenter la logique liée à `refunds.auto_approve_limit` : si `amount_requested <= limite` et critères simples OK, approuver automatiquement et logger l’action. |
| 10 | **Recherche et export** | Recherche par nom client, email ou nom produit ; export Excel/CSV (et éventuellement PDF) des demandes filtrées pour la compta. |
| 11 | **Compression des preuves** | Utiliser un service d’images (ex. `ImageProcessingService`) pour compresser les pièces jointes avant stockage. |
| 12 | **Paramètres remboursements (Super Admin)** | Page Réglages pour modifier la limite d’auto-approbation, catégories de raison, etc., sans toucher au fichier de config. |

---

## 4. Cohérence avec les autres docs

- `docs/improvements/refund_functionality_improvements.md` : policies déjà en place ; timeline, filtres, notifications, auto-approve, reporting et tests restent des pistes à implémenter.
- `features/refund_analysis_improvements.md` : ce document s’aligne sur les idées (produit dans la liste, discussion/timeline, balance restante, config UI, recherche/export, compression, bulk actions) et les priorise.

---

## 5. Résumé technique

- **Modèle** : `RefundRequest` (relations `sourcingOrder`, `user`, `assignedAdmin`), événement `RefundRequestUpdated` sur `updated`.
- **Contrôleurs** : `Client\RefundRequestController`, `Admin\RefundRequestController` ; policy utilisée partout où nécessaire.
- **Notifications** : `RefundRequestCreated` (admin assigné + super admins), `RefundStatusUpdated` (client, mail + BDD + FCM).
- **Config** : `config/refunds.php` (`auto_approve_limit` à 0).
- **Rapport** : `Admin\FinancialReportController::refunds()`.

Les améliorations listées ci-dessus s’appuient sur ces briques et ne dupliquent pas les fonctionnalités déjà présentes.
