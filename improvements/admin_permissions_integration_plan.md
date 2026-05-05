# Plan d'Intégration : Système de Permissions pour Administrateurs

Ce document détaille la stratégie pour implémenter un contrôle d'accès granulaire géré par le **Super Admin** et applicable aux **Admins**.

---

## 1. Objectif
Permettre au Super Admin de définir précisément ce que chaque administrateur peut voir et faire au sein de la plateforme, afin de sécuriser les données et de segmenter les responsabilités.

## 2. Architecture Technique
Nous utiliserons le package **Spatie Laravel Permission** (standard de l'industrie) pour sa robustesse et sa facilité d'intégration avec Laravel.

### Modèle de Données :
*   **Roles** : Groupes de permissions (ex: "Sourcing Specialist", "Finance Admin").
*   **Permissions** : Actions atomiques (ex: `quotation.approve`, `order.sync_google_sheet`).
*   **Liaison polymorphic** : Les permissions seront directement attachées aux utilisateurs avec le role `admin`.

---

## 3. Catégorisation des Permissions

### A. Sourcing (Requêtes)
*   `sourcing.view_all` : Voir les requêtes de tous les admins (versus seulement les siennes).
*   `sourcing.edit` : Modifier les détails d'une requête.
*   `sourcing.assign` : Assigner ou réassigner une requête.
*   `sourcing.delete` : Supprimer une requête.

### B. Devis (Quotations)
*   `quotation.create` : Créer un nouveau devis.
*   `quotation.edit` : Modifier un devis existant (non approuvé).
*   `quotation.approve` : Valider et approuver un devis (action critique).
*   `quotation.delete` : Supprimer un devis.

### C. Commandes (Orders)
*   `order.update_status` : Changer l'état d'avancement d'une commande.
*   `order.manage_financials` : Accès aux coûts réels, marges et paiements.
*   `order.sync_google_sheet` : Déclencher manuellement la synchronisation.
*   `order.refund` : Gérer les demandes de remboursement.

### D. Paramètres & Système
*   `settings.google_sheets` : Accès à la configuration API Google Sheets.
*   `settings.social_media` : Modifier les liens WhatsApp/Réseaux.
*   `management.users` : Gérer les comptes clients.
*   `management.admins` : (Réservé Super Admin) Gérer les autres admins.

---

## 4. Interface de Gestion (Super Admin)

Une nouvelle vue dans l'espace Super Admin permettra de :
1.  **Lister les admins** (déjà existant).
2.  **Écran de configuration par admin** : Une grille de "Checkboxes" regroupées par catégories (Sourcing, Devis, Commandes, etc.).
3.  **Logs d'actions** : Tracer quel admin a fait quelle action (Audit Trail).

---

## 5. Étapes d'Implémentation

### Étape 1 : Installation & Migration
1.  Installer `spatie/laravel-permission`.
2.  Exécuter les migrations pour créer les tables `permissions`, `roles`, etc.
3.  Initialiser les permissions de base via un Seeder.

### Étape 2 : Sécurisation du Backend
1.  Utiliser les **Policies** de Laravel dans les contrôleurs :
    `$this->authorize('quotation.approve', $quotation);`
2.  Ajouter des middlewares aux routes sensibles.

### Étape 3 : Adaptation de l'UI (Blade)
1.  Masquer les boutons/menus via les directives `@can` :
    ```blade
    @can('quotation.approve')
        <button>Approuver le devis</button>
    @endcan
    ```

### Étape 4 : Interface de Gestion
1.  Créer le `PermissionController` pour le Super Admin.
2.  Développer la vue Blade avec Alpine.js pour une gestion fluide des cases à cocher.

---

## 6. Bénéfices Attendus
*   **Sécurité** : Protection des données financières sensibles.
*   **Clarté** : Les admins ne voient que les outils nécessaires à leur mission.
*   **Évolutivité** : Facilité d'ajouter de nouveaux rôles ou permissions à l'avenir.
