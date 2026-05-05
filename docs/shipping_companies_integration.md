# Documentation: Intégration des Sociétés de Transport & Google Sheets

Ce document détaille le nouveau fonctionnement de l'intégration Google Sheets avec la gestion des sociétés de transport (Shipping Companies).

## 🗂️ Architecture des Données

### Modèle `ShippingCompany`
Chaque société de transport est désormais gérée comme une entité distincte en base de données :
- **Nom** : Le nom de la société (ex: Faster.ae, DHL).
- **Google Sheet ID** : L'identifiant unique du tableur Google dédié à cette société.
- **Statut** : Actif / Inactif.

### Relation avec les Commandes
La table `sourcing_orders` contient maintenant une colonne `shipping_company_id` qui lie une commande à son transporteur désigné.

---

## 🔄 Nouveau Workflow d'Assignation

1.  **Paiement Reçu (`paid`)** :
    - Lorsqu'une commande passe au statut `paid` (Produit payé par le client), le Super Admin doit assigner la commande à une société de transport via le panneau "Workflow" de la commande.
2.  **Préparation de l'expédition (`shipment_preparing`)** :
    - Une fois la société assignée, le passage au statut `shipment_preparing` (ou "Shipping Prepared") déclenche l'envoi des données vers le Google Sheet **spécifique** de la société choisie.
    - Si aucune société n'est assignée, la synchronisation ne se fera pas (ou utilisera le sheet par défaut si configuré ainsi).

---

## 🛠️ Instructions pour le Super Admin

### 1. Gestion des Sociétés
Une nouvelle page est disponible dans le menu Admin sous **"Shipping Companies"**.
- Pour chaque société, vous devez créer une entrée et renseigner son **ID Google Sheet**.
- **Important** : Vous devez inviter l'email du compte de service (Service Account) sur chaque Google Sheet créé pour que l'application puisse y écrire. L'email est : `sourcing-app@...` (voir paramètres Google Sheet).

### 2. Attribution des Commandes
Dans le détail d'une commande (Sourcing Order Dashboard) :
- Repérez la section "Assignation Transporteur".
- Sélectionnez la société dans la liste déroulante une fois que le paiement est confirmé.

---

## ⚙️ Détails Techniques (Modification du Code)

-   **Service `GoogleSheetService`** : Mis à jour pour accepter dynamiquement un `spreadsheetId` lors de son instanciation.
-   **Listeners d'Évènements** : 
    - `SyncOrderToGoogleSheet` : Modifié pour vérifier la société assignée avant de synchroniser.
    - `UpdateOrderStatusInGoogleSheet` : Modifié pour mettre à jour le bon sheet selon l'assignation.
-   **Synchronisation Automatique** : Si une commande est liée à un transporteur disposant d'une API de suivi (futur), le statut de la commande pourra être mis à jour automatiquement en interrogeant cette API.
