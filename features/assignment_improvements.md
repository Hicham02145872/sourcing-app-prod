# Améliorations des Fonctionnalités d'Assignation

Ce document propose des pistes d'amélioration pour le système d'assignation des demandes de sourcing et des devis. L'objectif est d'optimiser la répartition de la charge de travail et d'améliorer la réactivité de l'équipe admin.

## 1. Algorithme "Smart Round Robin" (Équilibrage de Charge)
Actuellement, le système assigne les dossiers au "prochain" admin disponible de manière circulaire, sans tenir compte de sa charge actuelle.

**Proposition :**
*   Calculer le "Score de Charge" de chaque admin (ex: nombre de dossiers avec statut "En cours" ou "Pending").
*   Assigner le nouveau dossier à l'admin ayant le score le plus bas.
*   **Avantage :** Évite de surcharger un admin qui a déjà 50 dossiers complexes alors qu'un autre n'en a que 2.

## 2. Gestion de la Disponibilité (Mode "Absent/Présent")
Permettre aux administrateurs de définir leur statut de disponibilité.

**Proposition :**
*   Ajouter un "toggle" dans le profil admin ou la barre latérale : *En ligne / Absent / Ne pas déranger*.
*   Si un admin est marqué "Absent", l'algorithme d'assignation automatique le saute.
*   **Avantage :** Évite d'assigner des dossiers urgents à quelqu'un en vacances ou en arrêt maladie.

## 3. Système de "Pick from Pool" (Libre Service)
Au lieu d'une assignation automatique forcée, créer un flux de travail basé sur le volontariat ou la rapidité.

**Proposition :**
*   Les nouveaux dossiers arrivent dans un statut "Non assigné" (Pool commun).
*   Un bouton "Prendre ce dossier" est disponible pour tous les admins.
*   Possibilité de combiner : Assignation auto après X heures si personne ne prend le dossier.
*   **Avantage :** Favorise la proactivité et permet aux admins de choisir des dossiers qu'ils maîtrisent.

## 4. Assignation par Spécialisation (Catégories)
Certains admins peuvent être experts en électronique, d'autres en textile.

**Proposition :**
*   Lier des administrateurs à des catégories de produits (Tags ou configuration dans le profil).
*   Si une demande concerne la catégorie "Électronique", elle est prioritairement routée vers les experts de ce domaine.
*   **Avantage :** Meilleure qualité de réponse et traitement plus rapide grâce à l'expertise métier.

## 5. Gestionnaires de Compte (Account Managers)
Pour les clients VIP ou récurrents, il est préférable de garder le même interlocuteur.

**Proposition :**
*   Associer un "Gestionnaire Dédié" à un Client (User).
*   Toute nouvelle demande de ce client est automatiquement assignée à son gestionnaire, contournant le Round Robin.
*   **Avantage :** Relation client renforcée et meilleur suivi historique.

## 6. SLA et Alertes de Retard
Détecter les goulots d'étranglement.

**Proposition :**
*   Si un dossier assigné reste sans action (changement de statut ou note) pendant X heures (ex: 24h), envoyer une notification à l'admin concerné ou au Super Admin.
*   Possibilité de réassignation automatique si aucune réponse sous 48h.
*   **Avantage :** Garantit qu'aucun dossier ne tombe dans l'oubli.

## 7. Transfert de Dossier Simplifié
Faciliter la collaboration et le passage de relais.

**Proposition :**
*   Interface UI simple pour transférer un dossier à un collègue avec une note interne obligatoire ("Je te passe ce dossier car je pars en réunion, client pressé").
*   Notification immédiate au nouvel assigné.

## 8. Assignation en Cascade (Devis & Commandes)
Assurer la continuité du dossier.

**Proposition :**
*   Si l'Admin A traite la "Sourcing Request", il devrait automatiquement être assigné au "Quotation" généré et au "Sourcing Order" qui en découle.
*   Actuellement, cela pourrait nécessiter une confirmation explicite dans le code pour garantir cette continuité sur tous les objets liés.
