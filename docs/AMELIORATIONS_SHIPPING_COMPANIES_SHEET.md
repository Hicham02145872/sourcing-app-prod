# Améliorations – Shipping Companies Sheet (Google Sheet & Lark Sheet)

Document de propositions d’amélioration pour l’intégration des feuilles de calcul (Google Sheet ou Lark Sheet) des sociétés d’expédition.

---

## 1. État actuel

### 1.1 Choix de la plateforme

| Plateforme   | Condition d’utilisation                    | Service utilisé            |
|-------------|---------------------------------------------|----------------------------|
| **Google Sheet** | `shipping_companies.google_sheet_id` renseigné | `GoogleSheetService`       |
| **Lark Sheet**   | `lark_app_id` + `lark_base_token` renseignés   | `LarkSheetService`        |

Une société ne peut utiliser qu’**une** des deux (priorité à Google si les deux sont remplis).

### 1.2 Déclencheurs de synchronisation

- **SourcingOrderStatusChanged** (changement de statut de commande)
- **ProofOfPaymentUploadedEvent** (preuve de paiement uploadée)
- **Sync manuel** : bouton « Sync to sheet » (ou équivalent) sur la fiche commande admin

### 1.3 Données synchronisées (ordre → feuille)

- Identifiant (display_id ou id×5 selon implémentation), date, statut, nom client, produit, quantité, montant, tracking, adresse, téléphone, image produit, etc.
- Mapping détaillé : `SourcingOrder::toShippingCompanySheetArray()`, `GoogleSheetService::AVAILABLE_FIELDS`, `LarkSheetService::mapOrderToRow()`.

### 1.4 Fonctionnalités existantes

- **Test de connexion** (Lark / Google) depuis la fiche société
- **Installation des en-têtes** (headers) + mise en forme (Lark : validation statut, formatage conditionnel, style en-têtes)
- **Sync d’une commande** : upsert ou append selon le service
- **Mise à jour du statut** : mise à jour de la cellule Statut pour une ligne existante (Lark : colonne C)
- **Idempotence** : verrou court (cache) pour éviter les syncs en double sur le même événement

---

## 2. Améliorations proposées

### 2.1 Colonnes / champs feuille

- **FSB (numéro client)**  
  - Ajouter une colonne **« FSB »** ou **« Client Tracking ID »** avec `fsb_tracking_number` (ex. FSB000044) pour que le transporteur puisse identifier la commande côté client.
- **Transporteur (carrier)**  
  - Ajouter une colonne **« Carrier »** (ex. GCC, UPS) quand la société a des child carriers, pour alignement avec le choix admin.
- **Poids / notes**  
  - Remplir **poids** et **notes** si les données existent (sourcing request, commande, ou champs dédiés) au lieu de laisser vides.
- **Cohérence des libellés**  
  - Unifier les noms de colonnes entre Google et Lark (même ordre et libellés dans `AVAILABLE_FIELDS` / `mapOrderToRow`) pour faciliter la doc et les exports.

### 2.2 Fiabilité et erreurs

- **Log des échecs**  
  - Persister les échecs de sync (table `sheet_sync_errors` ou champs sur `sourcing_orders`) : order_id, company_id, plateforme, erreur, created_at.
- **Retry automatique**  
  - Conserver ou étendre le backoff du listener (ex. 3 tentatives avec backoff 10s, 60s, 180s) et documenter le comportement.
- **Indicateur côté admin**  
  - Afficher sur la fiche commande : « Dernière sync sheet : date » et éventuellement « Échec : message » avec lien vers une page de log ou réessai.

### 2.3 UX admin (sociétés & commandes)

- **Page sociétés (Shipping Companies)**  
  - Résumé par société : « Dernière sync OK », « Nombre de commandes synchronisées ce mois », « Dernière erreur » (si log d’erreurs).
  - Bouton **« Réessayer les syncs en échec »** (pour les commandes de cette société).
- **Fiche commande**  
  - Bouton **« Sync to sheet »** toujours visible quand une société avec sheet est assignée, avec feedback (succès / erreur).
  - Petite note du type : « Les mises à jour de statut et de preuve de paiement sont envoyées automatiquement au sheet. »

### 2.4 Sync en lot (batch)

- **Export / sync de toutes les commandes d’une société**  
  - Commande Artisan ou action admin du type : `php artisan sheet:sync-company {shipping_company_id}` pour (re)synchroniser toutes les commandes assignées à cette société (avec option `--since=YYYY-MM-DD`).
  - Éviter les doublons : utiliser un identifiant stable (ex. order id ou display_id) pour upsert au lieu d’append systématique.

### 2.5 Google Sheet

- **Credentials par société (optionnel)**  
  - Aujourd’hui les credentials Google sont globales. Si besoin, permettre un `credentials_path` ou un identifiant OAuth par société (avancé).
  - Sinon, documenter clairement que toutes les sociétés Google partagent le même compte de service.
- **Onglet (sheet name)**  
  - S’assurer que `sheet_name` (ex. « sourcing ») est bien pris en compte pour cibler le bon onglet et le documenter dans l’UI (placeholder ou aide).
- **Quotas / rate limit**  
  - En batch, respecter les limites Google (ex. 100 requêtes / 100 s) : throttling ou découpage en petits lots.

### 2.6 Lark Sheet

- **Token / feuille**  
  - Documenter où récupérer le **Lark Spreadsheet Token** et le **Sheet Title** (onglet), et les afficher en aide dans le formulaire société (tooltip ou lien vers doc).
- **Gestion du token expiré**  
  - Si l’API renvoie une erreur d’auth, logger l’erreur et afficher un message clair à l’admin : « Token Lark expiré ou invalide. Veuillez le mettre à jour dans la fiche société. »
- **Colonnes et format**  
  - Aligner les colonnes avec Google (FSB, Carrier, etc.) et garder la validation / formatage conditionnel sur la colonne Statut.

### 2.7 Documentation et opérations

- **Guide admin (MD ou wiki)**  
  - Créer un **SHIPPING_SHEET_SETUP.md** : création d’une société, choix Google vs Lark, configuration (ID feuille, token, onglet), installation des en-têtes, test de connexion, signification des colonnes (y compris FSB et Carrier).
  - Inclure les captures ou liens vers les endroits dans Lark/Google où trouver le token et l’ID de feuille.
- **Checklist déploiement**  
  - Variables d’env (Google credentials path, etc.), droits du compte de service (Google) ou app Lark, et vérification que les événements (status change, proof upload) déclenchent bien le listener.

---

## 3. Priorisation suggérée

| Priorité | Amélioration                                      | Impact |
|----------|---------------------------------------------------|--------|
| Haute    | Colonne FSB + colonne Carrier dans la feuille     | Alignement avec le flux client et multi-transporteur |
| Haute    | Log des échecs + indicateur « Dernière sync » sur la commande | Visibilité et debug |
| Moyenne  | Sync batch (commande Artisan ou bouton par société) | Rattrapage et onboarding |
| Moyenne  | Doc SHIPPING_SHEET_SETUP (Google + Lark)          | Autonomie des admins |
| Basse    | Poids / notes réels, cohérence des libellés       | Qualité des données et maintenance |

---

## 4. Fichiers concernés (référence)

- `app/Services/SheetIntegrationFactory.php` – choix Google vs Lark
- `app/Services/GoogleSheetService.php` – sync Google
- `app/Services/LarkSheetService.php` – sync Lark
- `app/Services/ShippingCompanySheetService.php` – ancien service Google (à clarifier vs GoogleSheetService)
- `app/Listeners/SyncOrderToSheet.php` – déclenchement automatique
- `app/Models/SourcingOrder.php` – `toShippingCompanySheetArray()`
- `app/Contracts/SheetIntegrationInterface.php` – contrat commun
- Admin : Shipping Companies (Livewire), fiche commande (bouton sync)

---

## 5. Résumé

- **État actuel** : intégration Google Sheet et Lark Sheet opérationnelle, sync sur changement de statut et preuve de paiement, installation des en-têtes et formatage (Lark).
- **Améliorations clés** : ajout des colonnes **FSB** et **Carrier**, **log d’erreurs** et **indicateur de sync** sur la commande, **sync batch** et **documentation** pour une utilisation et un dépannage plus simples par les admins.

Ce document peut servir de base pour un plan de tâches (issues, sprints) ou une mise à jour du produit.
