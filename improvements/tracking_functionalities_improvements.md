# Sourcing App: Tracking Functionalities Improvements

Ce document détaille les idées d'amélioration pour la gestion et le suivi des expéditions (Tracking) au sein de la plateforme.

## 1. Intégrations Multi-Transporteurs
- **API Multiplexée** : Intégration avec des agrégateurs comme AfterShip, 17Track ou Shippo pour supporter automatiquement des centaines de transporteurs mondiaux.
- **Détection Automatique du Transporteur** : Identifier le transporteur à partir de la structure du numéro de suivi.
- **Webhooks de Mise à Jour** : Recevoir des notifications en temps réel des transporteurs pour mettre à jour le statut de la commande dans l'app sans action manuelle.

## 2. Expérience Client (Notification & Visibilité)
- **Portail de Suivi Personnalisé (Branding)** : Une page de suivi aux couleurs de l'entreprise où le client peut voir l'historique complet de son colis sur une carte.
- **Notifications Automatisées** : Envoi de SMS ou d'emails à chaque étape clé (Pris en charge, En transit, Arrivé au pays de destination, En cours de livraison).
- **Estimation de Livraison (ETA)** : Calcul dynamique de la date de livraison estimée basée sur les performances historiques du transporteur.

## 3. Logistique & Gestion Interne
- **Suivi des Multi-Colis** : Possibilité d'associer plusieurs numéros de suivi à une seule commande sourcing si celle-ci est expédiée en plusieurs fois.
- **Historique Interne Détaillé** : Journalisation de tous les changements de statut avec timestamp et localisation pour une traçabilité totale.
- **Preuve de Livraison (POD)** : Possibilité pour le transporteur ou l'admin d'uploader une photo de la signature ou du colis livré.

## 4. Analyse & Performance
- **Dashboard de Transit Time** : Analyser le temps moyen de livraison par transporteur et par pays pour optimiser les choix logistiques futurs.
- **Alertes d'Anomalies** : Notification automatique si un colis est bloqué en douane ou s'il n'y a pas eu de mouvement pendant plus de 48h.
- **Rapport de Coût de Transport** : Comparaison entre le coût estimé et le coût réel facturé par le transporteur.

## 5. Fonctionnalités Avancées
- **Intégration QR Code** : Générer un QR code sur la facture ou le bon de livraison permettant au client de scanner pour accéder directement au suivi.
- **Suivi de la Main-d'œuvre (Sourcing)** : Suivi de l'étape "préparation en entrepôt" avant même que le transporteur ne récupère le colis.
- **Gestion des Retours (Reverse Logistics)** : Interface dédiée pour générer des étiquettes de retour et suivre les colis renvoyés par les clients.

## 6. Améliorations de l'Interface Admin
- **Bulk Update Tracking** : Possibilité d'uploader un fichier CSV pour mettre à jour les numéros de suivi de plusieurs centaines de commandes en un clic.
- **Vue Map Global** : Carte interactive montrant la position en temps réel de toutes les expéditions en cours pour le Super Admin.
