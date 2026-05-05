# Rapport d'Analyse : Tracking & Refund Management
**Projet : Sourcing App**
**Cible : Marché Marocain**

---

## 1. Analyse de l'Existant (Audit Technique)

### 🛰️ Système de Tracking
L'application dispose d'une architecture de tracking **hybride et modulaire** :
- **Providers API** : Utilisation de **17Track** comme moteur principal et fallback.
- **Providers Selenium (Scraping)** : Intégration avancée de robots pour extraire les données directement depuis ChoiceXP, Faster, et Itdida.
- **Système d'Alias** : Fonctionnalité de masquage des numéros de suivi réels via des références `FSB`, améliorant la sécurité et l'image de marque.
- **Statut** : Très mature, mais nécessite une maintenance continue pour la partie Scraping.

### 💰 Système de Remboursement
Le système actuel gère le **cycle de vie complet d'une demande de remboursement** :
- **Flux Admin** : Interface de validation/rejet avec calcul automatique de l'impact financier sur la commande.
- **Preuves de Remboursement** : Support de l'upload de preuves (PDF/Images) et logs des actions.
- **Logique** : Intégrité des données garantie (le remboursement ne peut excéder le total payé).
- **Statut** : Robuste, prêt pour une évolution vers un système de "Wallet" (Crédit client).

---

## 2. Devis Freelance (Marché Marocain)

Ce devis est estimé pour un développeur **Fullstack Laravel expérimenté (Maroc)** avec un TJM de **2 500 DH - 3 500 DH**.

| Module                                       | Complexité | Temps       | Estimation (MAD)   |
| :------------------------------------------- | :--------- | :---------- | :----------------- |
| **Maintenance & Fix Tracking Robots**        | Élevée     | 3 - 5 jours | 10 000 - 15 000 DH |
| **Nouveau Provider (API Standard)**          | Basse      | 1 - 2 jours | 4 000 - 6 000 DH   |
| **Logique de Refund "Wallet"**               | Moyenne    | 2 jours     | 5 000 - 7 000 DH   |
| **Timeline Visuelle (Client UX)**            | Moyenne    | 2 jours     | 4 000 - 6 000 DH   |
| **Intégration Paiement Local (CMI/Payzone)** | Élevée     | 3 - 4 jours | 8 000 - 12 000 DH  |

---

## 3. Stratégie & Recommandations

### 📍 Localisation (Dernier Kilomètre Maroc)
- **Amana Integration** : Pour les envois locaux via Poste Maroc, l'ajout d'un service de tracking dédié est une priorité.
- **Paiement à la Livraison (COD)** : Adapter le module de remboursement pour gérer les collectes cash des transporteurs locaux.

### 💳 Optimisation Financière (Le "Wallet")
Le document de brainstorming suggère un système de crédit. Dans le contexte marocain, cela permet de :
1. **Éviter les délais bancaires inter-comptes**.
2. **Fidéliser le client** (l'argent reste dans la plateforme).
3. **Réduire les frais de transaction** pour les petits montants.

### 🚀 Améliorations de l'Engagement (UX)
- **Timeline Visuelle** : Remplacer les statuts textuels complexes par une timeline interactive (Reçu → Examen → Payé).
- **Notifications Web/Mobile** : Exploiter les jobs FCM déjà présents pour envoyer des alertes en temps réel à chaque étape clef du remboursement.

---
*Fin du rapport.*
