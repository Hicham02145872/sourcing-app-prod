# Améliorations Proposées pour l'Intégration Google Sheets

Basé sur l'analyse de l'existant, voici une liste d'améliorations pour fiabiliser et enrichir la fonctionnalité Google Sheets.

## 1. Fiabilisation & Stabilité (Priorité Haute)

### 🛠️ Corrections Fondamentales
- **Migration & Seeder** : Créer les tables et données initiales manquantes pour éviter les erreurs "Table not found" ou "Settings not configured".
- **Validation Config** : Vérifier automatiquement au démarrage si le fichier `google-credentials.json` et la variable `.env` sont présents.
- **Route Update** : S'assurer que la route de sauvegarde (`PUT`) est bien définie pour que le formulaire Admin fonctionne.

### 🛡️ Gestion d'Erreurs Robuste
- **Test de Connexion** : Ajouter un bouton "Tester la connexion" dans l'admin pour valider les identifiants et l'accès au Sheet avant de tenter une synchro.
- **Retry Automatique** : Si l'API Google échoue (quotas, timeout), réessayer automatiquement après quelques secondes.

## 2. Expérience Administrateur (UX)

### 📊 Dashboard de Synchronisation
- **Statut en Temps Réel** : Afficher la date de la dernière synchro réussie et le nombre de commandes exportées.
- **Logs Visuels** : Une table simple montrant les dernières erreurs (ex: "Permission denied", "Sheet not found") pour aider au diagnostic.

### ⚡ Configuration Simplifiée
- **Auto-Installation** : Un bouton pour créer automatiquement les colonnes (headers) dans le Google Sheet s'ils n'existent pas.
- **Formatage** : Appliquer automatiquement des couleurs ou du gras aux en-têtes via l'API.

## 3. Fonctionnalités Avancées

### 🔄 Synchronisation Bidirectionnelle (Two-Way Sync)
- **Concept** : Permettre de mettre à jour le statut d'une commande dans l'app en changeant une cellule dans le Google Sheet (ex: passer de "Pending" à "Ordered").
- **Avantage** : Idéal si l'équipe logistique préfère travailler sur Excel/Sheets.

### 📑 Support Multi-Feuilles
- **Concept** : Séparer les commandes par onglet selon leur statut (ex: onglet "À Acheter", onglet "Expédié").
- **Avantage** : Meilleure organisation visuelle pour les opérateurs.
    
### 🎨 Formatage Conditionnel Automatique
- **Concept** : Colorer automatiquement les lignes dans le Sheet selon le statut de la commande (Vert pour livrée, Rouge pour annulée).
- **Avantage** : Lecture visuelle immédiate.

## 4. Monitoring & Alertes

### 🔔 Alertes Proactives
- **Email Admin** : Envoyer un email à l'admin si la synchronisation échoue plus de 3 fois de suite (ex: token expiré).
- **Quota Monitoring** : Alerter si l'application approche des limites de l'API Google.

## Résumé des Priorités Suggérées

| Priorité | Fonctionnalité | Impact | Effort Dev |
| :--- | :--- | :--- | :--- |
| 🔴 Critique | Fiabilisation (Routes, Migrations, Env) | ⭐⭐⭐⭐⭐ | Faible |
| 🟠 Haute | Bouton "Test Connexion" | ⭐⭐⭐⭐ | Faible |
| 🟡 Moyenne | Création Auto des Headers | ⭐⭐⭐ | Moyen |
| 🔵 Basse | Synchro Bidirectionnelle | ⭐⭐⭐⭐⭐ | Élevé |



