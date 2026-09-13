# Cahier des charges — 5 modules de gestion admin (application de sourcing FSB)

**Version :** 1.0
**Audience :** client / partie non technique
**Source :** analyse du code source de l'application (`app/`, `config/` et `resources/views/`)

---

## Introduction

Ce document décrit **5 modules fonctionnels** récemment livrés dans l'application de sourcing :

1. Délais d'action (SLA) et blocage automatique de navigation ;
2. Filtre par plage de dates sur les listes ;
3. Étiquette d'expédition PNG ;
4. Étiquettes multi-destination regroupées en ZIP ;
5. Alignement des barres de recherche et des filtres.

Pour chaque module, nous décrivons **ce qu'il fait** (fonctionnalités) et **pourquoi il est complexe dans ce code**, en citant les composants concernés.

---

## Module 1 — Délais d'action (SLA) et blocage automatique de navigation

### Fonctionnalités

- **4 règles métier configurables** dans `config/fsb.php → sla` :
  | Situation | Délai | Action attendue |
  |---|---|---|
  | Demande en statut `in_review` | 24 h | Fixer un prix (vue **Créer un devis**) |
  | Demande en statut `negotiating` | 24 h | Mettre à jour le prix (vue **Mettre à jour le devis**) |
  | Commande en statut `paid` | 24 h | Acheter et expédier (escalade aux super admins) |
  | Commande en statut `in_transit_china` | 48 h | Téléverser la preuve colis (photo + n° de suivi Chine) |
- **Commande planifiée `workflow:check-deadlines`** (`app/Console/Commands/CheckWorkflowDeadlines.php`) :
  - marque chaque dossier en retard (`is_restricted_due_to_delay = true`) ;
  - notifie l'admin assigné ;
  - pour `paid` dépassé, **escalade** à tous les super admins (`User::role('super_admin')`).
- **Horodatage du changement de statut** : colonne `status_changed_at`, maintenue automatiquement par le modèle `SourcingOrder` (hook `updating()`) et rétro-remplie pour les dossiers existants (migration).
- **Blocage de navigation** (middleware `EnforceSlaNavigationLock`) : pour les **non-super admins**, redirection vers l'action la plus urgente quand un retard existe ; liste blanche des routes autorisées pour pouvoir justement régler le retard.
- **Bandeau d'alerte** sur le tableau de bord (`workflow-alert-banner`) avec compteurs et boutons d'action par statut (`AdminDashboardController`).
- **Activation globale par flag** : `sla_deadlines_autolock` (géré par `FeatureFlagService`, table `feature_flags`).

### Pourquoi c'est complexe

- **Deux flux à couvrir** : les demandes (sourcing) ET les commandes, chacun avec ses statuts, ses notifications et ses règles → deux boucles distinctes dans la commande.
- **Priorisation des retards simultanés** : un admin peut avoir plusieurs dossiers en retard ; il faut choisir UN point d'entrée (« la plus urgente ») : priorité `in_review` → `negotiating` → `paid` → `in_transit_china`, puis **le plus ancien** `status_changed_at` (ordre SQL `ORDER BY CASE ... ASC`).
- **Une redirection par règle, avec repli** : `in_review` → page création de devis ; `negotiating` → page de mise à jour du devis (qui n'existe que si un devis a déjà été créé → fallback sur la fiche de la demande) ; commandes → fiche de la commande.
- **Le blocage ne doit pas piéger l'admin** : le middleware doit restreindre la navigation **sans l'empêcher de finir l'action** → liste blanche (`.quotations.create`, `.quotations.edit`, `.update`…). Un oubli = admin bloqué sans pouvoir agir.
- **Cohérence de l'horodatage** : le calcul du retard repose sur `status_changed_at`, maintenu sur 2 modèles et backfillé en base (traitement par lots `chunkById`) pour les anciennes commandes.
- **Deux audiences de notification** : l'admin assigné (prévenir qu'il est en retard) et les super admins (escalade du `paid`) → notification dédiée `SourcingOrderDeadlineExceeded` avec préfixes distincts.
- **L'UI ne doit pas afficher « SLA »** aux utilisateurs → libellés métier traduits en anglais / français / arabe (`lang/*.json`).
- **37 tests de régression** (`SlaNavigationLockTest`, `SlaDeadlineAutolockTest`) couvrent toutes les règles, les fallbacks et les droits.

---

## Module 2 — Filtre par plage de dates sur les listes

### Fonctionnalités

- **Deux champs de filtre** « Date de début » (`date_debut`) et « Date de fin » (`date_fin`) sur **6 listes admin** :
  demandes de sourcing, commandes, devis, demandes de remboursement, utilisateurs, journaux de suivi.
- **Filtre sur `created_at`**, borne de fin **incluse** (comparaison de date `whereDate`).
- Le filtre s'applique **à la fois à la liste paginée ET aux compteurs/KPI** des onglets de statut → les chiffres affichés en haut restent cohérents avec la liste.
- **Conservation des paramètres** : les dates restent actives pendant la pagination et lors des changements d'onglet de statut.
- **Liste des utilisateurs en AJAX** : les mêmes paramètres sont envoyés par la requête `fetchUsers` côté JavaScript.
- Composant partagé : trait `AppliesDateRangeFilter` (requête) + partial `date-range-fields` (champs) réutilisés sur toutes les pages.

### Pourquoi c'est complexe

- **Deux requêtes différentes par page** : chaque page exécute une requête « liste » ET une requête « compteurs par statut » ; il faut appliquer le filtre aux deux, sinon le nombre de résultats et la liste racontée ne correspondent plus.
- **Logique de date inclusive** : la date de fin doit inclure le jour entier (bornes `00:00:00` → fin de journée), et il faut gérer le cas où une seule des deux dates est fournie.
- **Préservation des paramètres** : chaque lien (pagination `withQueryString`, onglets de statut via `request()->except(...)`, bouton Réinitialiser) doit conserver ou purger correctement `date_debut`/`date_fin`.
- **Double source de vérité sur la liste des utilisateurs** : le filtre doit être reproduit dans la route AJAX (JSON) en plus de la page classique.
- **Rétro-compatibilité** : la liste des remboursements possédait déjà un filtre simple par date ; il a fallu le faire coexister avec la nouvelle plage sans casser le comportement existant.
- **Multilingue / RTL** : les libellés « Date de début / Date de fin » sont traduits (anglais, français, arabe).

---

## Module 3 — Étiquette d'expédition en PNG

### Fonctionnalités

- **Rendu PNG natif** à 300 DPI sur un format A4 (2480 × 3508 px) via l'extension GD (`app/Services/ShippingLabelImageService.php`).
- **Mise en page structurée** : logo en haut, tableau à deux colonnes (libellé / valeur) avec pays, nom du vendeur, ID de commande, produit, quantité, adresse de destination, et pied de page avec contact WhatsApp.
- **Auto-remplissage** : quand on ouvre le formulaire d'étiquette, les champs sont pré-remplis avec les valeurs réelles (vendeur, produit, adresse).
- **Téléchargement direct** : cliquer sur « Télécharger » lance le fichier `.png` (`Content-Disposition: attachment`) sans passer par un PDF ; le PDF reste disponible en repli (`?format=pdf`/`html`).
- **Logo redimensionné** (≈ 400 px de large) et **espacement calculé** entre le logo et le tableau à partir de la hauteur réelle du logo rendu.
- **Activation par flag** : `label_image_output` ; par défaut, une fois le flag actif, le format PNG devient le format par défaut.

### Pourquoi c'est complexe

- **Pas de conversion HTML→image** : l'image est dessinée manuellement, pixel par pixel. Chaque élément exige du calcul précis :
  - mesure de la largeur du texte via `imagettfbbox` ;
  - **wrapping** des longues adresses, mot par mot, puis **coupe par caractères** pour les mots uniques trop longs ;
  - hauteur de cellule **dynamique** (dépend du nombre de lignes enroulées) ;
  - **centrage vertical** du texte dans chaque cellule.
- **Dépendances fichiers** : les polices (DejaVu) sont stockées dans le paquet `dompdf` et le logo est facultatif → le rendu **ne doit pas planter** si le logo manque (test dédié).
- **Double format** : la sortie dépend du flag ET du paramètre demandé → fonction `resolveLabelFormat` qui combine flag + forçage (`png`/`pdf`/`html`). Le comportement doit être identique côté admin et côté client (2 contrôleurs à synchroniser).
- **Limites de l'environnement** : vérification de la présence de GD (levée d'une `RuntimeException` si absent).
- **Précision visuelle exigée** : toute erreur de calcul (hauteur de ligne, décalage, centrage) se voit immédiatement sur une image à 300 DPI.

---

## Module 4 — Étiquettes multi-destination en ZIP

### Fonctionnalités

- **Pluralité de destinations** : une commande peut livrer plusieurs pays/adresses ; **chaque destination a sa propre étiquette** (pays, quantité, adresse différents).
- **Téléchargement groupé** : quand la commande a **plusieurs destinations**, le téléchargement produit une archive **ZIP** (`shipping-labels-{id}.zip`) contenant **un PNG par destination**.
- **Fichiers organisés** : chaque PNG est rangé dans un dossier `shipping-label-{id}/` et nommé par ordre + pays (ex. `1-france.png`, `2-arabie-saoudite.png`).
- **Cas simple inchangé** : une seule destination → le téléchargement retourne directement le PNG (comportement identique à avant).
- Même logique côté **admin et client**.

### Pourquoi c'est complexe

- **Branchement selon le nombre de destinations** : la décision « PNG direct » ou « ZIP » doit être prise avant le rendu, et le flux simple ne doit pas être dégradé ni ralenti.
- **Rendu individuel** : chaque destination est dessinée séparément (aucun moyen de « dupliquer » l'image) → il faut itérer et rendre autant d'images que de destinations.
- **Dépendance `ext-zip`** : le ZIP repose sur l'extension PHP `ZipArchive` ; la gestion du fichier temporaire (création → lecture → envoi → suppression `unlink`) doit être propre pour ne pas laisser de fichiers orphelins.
- **Noms de fichiers sûrs** : les noms de pays contiennent accents et espaces → sanitisation par `Str::slug` avant d'écrire dans l'archive.
- **Parité admin/client** : la même logique ZIP doit exister dans les deux contrôleurs pour éviter des comportements divergents.
- **Non-régression** : test dédié qui vérifie que l'archive contient exactement un PNG valide par destination (signature `\x89PNG`).

---

## Module 5 — Alignement des barres de recherche et des filtres

### Fonctionnalités

- **Barres de recherche/filtres alignées** sur les listes (demandes, commandes, utilisateurs, remboursements, journaux de suivi) :
  - tous les champs, boutons et listes déroulantes sont **alignés en bas** (`items-end`) dans la rangée ;
  - les boutons « Filtrer » ont une **hauteur fixe** (34 px) identique aux champs de saisie ;
- **Grille de remboursements corrigée** : le champ de dates occupe désormais une largeur correcte (3 colonnes au lieu de 2, fin du débordement) et les boutons d'action occupent toute la largeur de la ligne.
- **Cohérence visuelle** : les champs de dates, la recherche et les listes déroulantes partagent les mêmes dimensions et styles sur toutes les pages.

### Pourquoi c'est complexe

- **Hauteurs hétérogènes en disposition flex** : les champs avec libellé (mot « Date de début » au-dessus de l'input) sont plus hauts que les champs sans libellé ; avec `items-center`, le bouton « Filtrer » **s'étirait** à la hauteur du bloc le plus grand → il fallait forcer l'alignement en bas et une hauteur fixe pour tous les éléments.
- **Placement automatique des grilles** : sur les remboursements, le conteneur auto-remplit les cellules de la grille et écrasait les deux boutons dans **une seule colonne** → correction par `col-span` explicite.
- **Comportement responsive** : sur mobile les éléments passent en colonne (`flex-col`) tout en restant alignés ; chaque point de rupture doit être vérifié.
- **Uniformisation sur 5+ écrans** : chaque page avait construit sa barre de façon légèrement différente ; l'alignement doit être homogène partout sous peine de dérive visuelle.

---

## Récapitulatif des fichiers clés

| Module | Fichiers principaux |
|---|---|
| 1 — SLA / blocage | `config/fsb.php`, `CheckWorkflowDeadlines.php`, `EnforceSlaNavigationLock.php`, `SourcingOrderDeadlineExceeded.php`, `SlaDeadlineExceeded.php`, `AdminDashboardController.php`, `workflow-alert-banner.blade.php` |
| 2 — Filtre dates | `AppliesDateRangeFilter.php`, `date-range-fields.blade.php`, contrôleurs admin (requests, orders, quotations, refunds, users, tracking), vues `index` correspondantes |
| 3 — Étiquette PNG | `ShippingLabelImageService.php`, `config/fsb.php → label`, contrôleurs admin + client `showShippingLabel` |
| 4 — ZIP multi-destination | Contrôleurs admin + client : `shippingLabelDownloadResponse` (utilise `ZipArchive`) |
| 5 — Alignement UI | `resources/views/admin/**/index.blade.php` (demandes, commandes, utilisateurs, remboursements, journaux) |

---

*Document établi à partir de l'analyse du code source. Les noms de composants cités correspondent aux fichiers réels du projet.*