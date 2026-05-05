# Plan d'Intégration Multi-Transporteurs et Suivi Automatisé

Ce document détaille l'approche technique pour intégrer 3 compagnies de transport (Shipping Companies), associer des numéros de suivi aux commandes, et automatiser la mise à jour des statuts.

## 1. Objectifs
- Permettre à l'admin de sélectionner un transporteur parmi 3 options pour chaque commande.
- Saisir un numéro de suivi (Tracking Number).
- Permettre au client de suivre son colis via une page dédiée (le système choisira l'API appropriée selon le transporteur associé).
- **Mise à jour automatique** du statut de la commande (`SourcingOrder`) en fonction du résultat de l'API de suivi.

## 2. Architecture Technique

### A. Base de Données
Modification de la table `sourcing_orders` pour ajouter :
- `carrier_name` (ou `carrier_id`): Pour stocker le transporteur choisi (ex: 'dhl', 'fedex', 'ups').
- `tracking_number`: Déjà prévu ou à confirmer.

Une nouvelle table `carriers` (optionnel) ou une configuration dans `config/shipping.php` pour gérer les clés API et les URLs de chaque transporteur.

### B. Gestion des Transporteurs (Design Pattern Strategy)
Pour gérer proprement 3 APIs différentes, nous utiliserons le pattern "Strategy" ou "Driver".
Création d'une interface `ShippingProviderInterface` :
- `getTrackingDetails(string $trackingNumber)`
- `mapStatusToSystemStatus(string $apiStatus)`

Implémentation de 3 classes (exemples) :
1. `DHLShippingProvider`
2. `FedExShippingProvider`
3. `UPSShippingProvider` (ou autres selon vos besoins réels)

### C. Interface Admin
Sur la page `index` ou `show` des commandes (`SourcingOrder`) :
- Ajouter un sélecteur (Select Box) : "Choisir le Transporteur".
- Champ texte : "Numéro de Suivi".
- Bouton "Associer / Mettre à jour".

### D. Interface Client (Tracking)
Sur la page de suivi :
- Le client entre son numéro de suivi (ou voit ses commandes).
- Le système détecte à quelle commande ce numéro appartient (ou l'utilise si le lien est cliquable depuis l'espace client).
- Le backend instancie le bon `ShippingProvider` basé sur la colonne `carrier_name` de la commande.
- L'API du transporteur est interrogée en temps réel (ou via cache).

### E. Automatisation des Statuts (Le point clé)
Pour que "le statut de l'order change par rapport au résultat" :

**Option 1 : Mise à jour à la consultation (On-Demand)**
À chaque fois que le client ou l'admin consulte le suivi, on interroge l'API, on reçoit le statut (ex: "DELIVERED"), et on met à jour le statut de la commande en BDD (`status` = 'delivered').

**Option 2 : Tâche Planifiée (Cron Job) - Recommandé**
Une commande artisan `shipping:sync-statuses` qui tourne toutes les heures :
1. Récupère toutes les commandes en cours d'expédition.
2. Pour chaque commande, interroge l'API du transporteur associé.
3. Compare le statut API avec le statut interne.
4. Si le statut API est "Livré" -> Met à jour la commande en "Delivered" et notifie le client.

## 3. Détails des 3 APIs (À définir)
Il faudra obtenir les documentations API pour les 3 transporteurs choisis.
*Exemple de mapping de statuts :*
- API "In Transit" -> Système `shipped`
- API "Out for Delivery" -> Système `shipped` (ou sous-statut)
- API "Delivered" -> Système `delivered`
- API "Exception/Failed" -> Système `issue`

## 4. Plan d'Implémentation (Étapes)

1.  **Backend Setup** : Créer l'interface et les classes Driver vides.
2.  **Database** : Ajouter la migration pour `carrier` sur `sourcing_orders`.
3.  **Admin UI** : Ajouter le formulaire de sélection de transporteur dans l'admin.
4.  **Integration API** : Connecter une première API (ex: 17Track ou API directe transporteur) pour tester.
5.  **Logique de Mapping** : Coder la logique qui transforme le statut API en statut `SourcingOrder`.
6.  **Automatisation** : Mettre en place la mise à jour automatique.

## 5. Questions pour vous
- Quels sont les 3 transporteurs spécifiques ? (DHL, FedEx, UPS, Aramex, etc. ?)
- Avez-vous déjà les clés API pour ces transporteurs ?
- Préférez-vous utiliser un agrégateur (comme 17Track, AfterShip) qui gère 100+ transporteurs avec une seule API, ou vraiment intégrer 3 APIs distinctes manuellement ? (L'agrégateur est souvent plus simple et moins cher à maintenir).

---
Ce plan est prêt. Si vous validez, nous pourrons passer à l'étape 1 (Création de la structure).
