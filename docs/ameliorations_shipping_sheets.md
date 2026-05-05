# Améliorations Possibles pour l'Intégration Google Sheets (Shipping Companies)

Ce document décrit des pistes d'amélioration pour la fonctionnalité de synchronisation des commandes avec les Google Sheets des compagnies d'expédition.

## 1. Robustesse et Fiabilité

### Gestion des Erreurs et Logs
*   **Problème actuel** : Les erreurs sont loggées de manière générique (`Log::error`). Si l'API Google échoue (quota, invalid graph), on a juste un message dans les logs.
*   **Amélioration** :
    *   Utiliser des **Exceptions typées** (ex: `Google\Service\Exception`) pour différencier les erreurs de quota des erreurs de configuration.
    *   Mettre en place un système de **notification aux admins** (via Slack/Email/Notification Database) si la synchro échoue plusieurs fois consécutives.
    *   Vérifier spécifiquement si le fichier `credentials.json` est unique ou partagé, et gérer son accessibilité.

### Rate Limiting (Limitation de débit)
*   **Problème** : L'API Google Sheets a des limites de requêtes par minute (60/min par utilisateur par projet).
*   **Amélioration** :
    *   Utiliser le middleware de Job Laravel `ThrottlesExceptions` ou `WithoutOverlapping` pour le listener `SyncOrderToShippingCompanySheet`.
    *   **Batching** : Si beaucoup de commandes sont traitées en même temps, grouper les mises à jour pour faire un `BatchUpdate` en une seule requête API au lieu de 50 requêtes individuelles.

### Validation des Crédentials
*   **Amélioration** : Créer une commande Artisan `php artisan sheets:check-status` qui tente une connexion simple pour vérifier que le fichier `credentials.json` est valide et que l'app a les droits d'accès.

## 2. Fonctionnalités et Données

### URLs des Images
*   **Point d'attention** : La formule `=IMAGE("url")` nécessite une URL publique accessible par les serveurs de Google.
*   **Amélioration** :
    *   S'assurer que le `asset('storage/...')` génère bien une URL publique (HTTPS) en production.
    *   En environnement local, utiliser un outil comme **Ngrok** ou ne pas envoyer l'image pour éviter les carrés gris dans le Sheet.

### Intégrité des Données
*   **Synchronisation des Suppressions/Annulations** : Actuellement, si une commande est annulée (`shipment_canceled`), le listener s'exécute mais la logique d'upsert ne change pas visuellement le statut de manière forte.
*   **Amélioration** :
    *   Barer (Strikethrough) toute la ligne dans le Google Sheet si le statut est `shipment_canceled`.
    *   Changer la couleur de fond de la ligne en rouge clair.

### Mapping Dynamique
*   **Problème actuel** : Les colonnes sont codées en dur dans `ShippingCompanySheetService::AVAILABLE_FIELDS`.
*   **Amélioration** :
    *   Ajouter une colonne JSON `sheet_column_mapping` dans la table `shipping_companies`.
    *   Créer une interface Admin pour laisser l'utilisateur choisir : "La colonne A correspond à l'ID", "La colonne B au Tracking", etc.

## 3. Expérience Utilisateur (Admin)

### Statut de Synchronisation
*   **Amélioration** : Ajouter une colonne "Statut Sheet" dans la liste des commandes Admin (`sourcing_orders` table ou vue).
    *   🟢 Synchro OK
    *   🔴 Erreur Synchro
    *   Ce feedback permet d'agir rapidement.

### Action Manuelle
*   **Amélioration** : Ajouter un bouton "Forcer la synchronisation" dans la vue de détail de la commande (`admin.sourcing-orders.show`).
    *   Utile pour corriger une erreur ponctuelle ou forcer une mise à jour après modification manuelle.

### Accès Rapide
*   **Amélioration** : Afficher un lien direct "Voir le Google Sheet" sur la page de la commande ou dans la liste des compagnies d'expédition.

## 4. Architecture et Code

### Pattern DTO (Data Transfer Object)
*   **Amélioration** : Extraire la logique de `toShippingCompanySheetArray` du modèle `SourcingOrder` vers une classe dédiée (ex: `ShippingSheetRowDTO` ou `OrderToSheetMapper`). Cela sépare la responsabilité de "Modèle Eloquent" de "Présentation pour Google Sheets".

### Synchronisation Bidirectionnelle (Optionnel)
*   **Idée** : Récupérer des infos saisies par le transporteur dans le Sheet (ex: Poids réel, Notes).
*   **Solution** : Un Job planifié (Cron) qui scanne le Sheet toutes les heures et met à jour les champs correspondants dans `sourcing_orders`.
