# Analyse et Plan d'Amélioration : Notifications Push (FCM)

Ce document analyse l'implémentation actuelle des notifications push via Firebase Cloud Messaging (FCM) et propose des axes d'amélioration pour optimiser la réactivité et l'engagement client.

---

## 1. Analyse de l'Implémentation Actuelle

### Points Forts
*   **Intégration terminée** : L'infrastructure de base (Firebase JS SDK, Service Worker, et Laravel Notifications) est fonctionnelle.
*   **Multi-canaux** : Les notifications sont envoyées via Email, Base de données (Badge UI) et FCM (Push).
*   **Persistance des Tokens** : Le système capture et met à jour les tokens FCM (`fcm_token`) dans la table `users`.
*   **Retry Logic** : Les notifications importantes comme `QuotationCreated` possèdent une logique de tentative (`tries`) et de backoff.

### Points Faibles / Lacunes
*   **Dépendance à l'Acceptation** : Aucun mécanisme n'incite l'utilisateur à réactiver les notifications s'il les a bloquées par erreur.
*   **Stale Tokens** : Pas de nettoyage automatique des tokens FCM invalides ou expirés dans la base de données.
*   **Expérience "Foreground"** : Lorsque l'application est ouverte, la notification push s'affiche mais l'UI (comme le compteur de notifications) ne se met pas toujours à jour sans rafraîchissement ou action de l'utilisateur (bien qu'un listener JS existe).
*   **Segmentation** : Les notifications sont envoyées de manière brute, sans personnalisation poussée du timing (ex: "Ne pas déranger").

---

## 2. Plan d'Amélioration

### A. Fiabilité et Nettoyage (Backend)
1.  **Gestion des Échecs FCM** : Écouter l'événement `NotificationFailed` de Laravel pour détecter les tokens révoqués par Google et les supprimer de la table `users` afin d'éviter des requêtes inutiles.
2.  **Synchronisation des Statuts** : S'assurer que chaque changement de statut critique (voir tableau ci-dessous) déclenche systématiquement une notification FCM.

### B. Expérience Utilisateur (Front-end)
1.  **Invite de Permission "Soft"** : Au lieu de demander la permission au chargement (ce qui est souvent bloqué), créer une petite bannière ou un bouton "Activer les notifications" dans le profil client pour expliquer la valeur ajoutée (ex: "Recevez vos devis en temps réel").
2.  **Update UI en Temps Réel** : Utiliser le listener `onMessage` (déjà présent) pour rafraîchir dynamiquement le compteur de notifications dans le header sans rechargement de page.

### C. Contenu et Personnalisation
1.  **Actions Directes (FCM Actions)** : Ajouter des boutons directement dans la notification push (ex: "Accepter le devis", "Voir le suivi").
2.  **Rich Media** : Inclure l'image du produit directement dans la notification push via le champ `image` de FCM pour une reconnaissance immédiate par le client.

---

## 3. Matrice des Notifications Critiques à Optimiser

| Événement | Titre Push | Amélioration suggérée |
| :--- | :--- | :--- |
| **Nouveau Devis** | 📄 Nouveau devis reçu ! | Ajouter le montant et le nom du produit dans le titre. |
| **Paiement Validé** | ✅ Paiement confirmé | Inclure une étape "Prochaine étape : Préparation" |
| **Colis en UAE** | 🇦🇪 Arrivée à Dubaï | Notification locale géo-spécifique. |
| **Livraison Échouée**| ⚠️ Échec de livraison | Ajouter un bouton "Appeler le livreur" ou "Reprogrammer". |

---

## 4. Prochaines Étapes Techniques RECOMMANDÉES

1.  **Audit du Service Worker** : Optimiser `firebase-messaging-sw.js` pour gérer les "Badges" d'icônes sur mobile (le petit point rouge sur l'icône de l'app).
--------------------------------------
2.  **Dashboard de Santé** : Créer une vue Super Admin pour voir combien d'utilisateurs ont un token FCM actif et le taux de succès des envois.
3.  **Logs de Debug** : Centraliser les erreurs Firebase dans un canal Slack ou une table `notification_errors` pour un diagnostic rapide.
