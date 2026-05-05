# Stratégie d'Indexation de la Base de Données - Sourcing App

Ce rapport présente une stratégie d'indexation pour optimiser les performances des requêtes de recherche, de filtrage et de tri dans l'application Sourcing App.

## 1. Analyse des Performances Actuelles
L'application utilise principalement des filtrages par statut et par administrateur assigné, ainsi que des recherches textuelles sur les noms de produits. Actuellement, seules les clés primaires et étrangères sont indexées par défaut.

### Problématiques Identifiées
- **Scans de Table** : Les filtres par `status` sur des milliers de lignes ralentissent l'affichage des tableaux de bord.
- **Recherches LIKE** : Les recherches `LIKE %search%` sur `product_name` ne bénéficient pas des index B-Tree standards.
- **Tris Complexes** : Les tris combinant l'assignation et la date de création (`assigned_to_admin_id` + `created_at`) peuvent être gourmands.

---

## 2. Recommandations d'Indexation

### A. Index de Filtrage Rapide (Single Column)
Ces colonnes sont utilisées dans presque toutes les listes d'administration.

- **Table `sourcing_requests`** : `status`, `assigned_to_admin_id`.
- **Table `sourcing_orders`** : `status`, `assigned_to_admin_id`, `tracking_number`.
- **Table `quotations`** : `status`, `assigned_to_admin_id`.
- **Table `users`** : `role`.

### B. Index Composites (Multi-Column)
Optimisent les requêtes qui combinent plusieurs filtres ou un filtre et un tri.

1. **Table `sourcing_requests`** : `(assigned_to_admin_id, status)`
   *Optimise le dashboard admin où l'on compte les demandes par statut pour un admin spécifique.*

2. **Table `sourcing_orders`** : `(assigned_to_admin_id, created_at DESC)`
   *Optimise le tri par défaut de la liste des commandes.*

### C. Full-Text Search (Recherche Textuelle)
Pour accélérer les recherches sur les produits dans `sourcing_requests`.

```php
Schema::table('sourcing_requests', function (Blueprint $table) {
    $table->fullText(['product_name', 'note']);
});
```
*Note : Nécessite MySQL 5.7+ ou MariaDB 10.0.5+.*

---

## 3. Exemple d'Implémentation (Migration)

Voici à quoi devrait ressembler la migration d'optimisation :

```php
public function up(): void
{
    Schema::table('sourcing_requests', function (Blueprint $table) {
        $table->index('status');
        $table->index(['assigned_to_admin_id', 'status']);
        $table->fullText('product_name');
    });

    Schema::table('sourcing_orders', function (Blueprint $table) {
        $table->index('status');
        $table->index('tracking_number');
        $table->index(['assigned_to_admin_id', 'created_at']);
    });

    Schema::table('notifications', function (Blueprint $table) {
        $table->index('read_at'); // Accélère le décompte des non-lues
    });
}
```

---

## 4. Bénéfices Attendus
- **Vitesse** : Chargement des listes admin divisé par 5 à 10 sur de gros volumes.
- **Scalabilité** : L'application restera fluide même avec des dizaines de milliers de commandes.
- **Réduction CPU** : Moins de charge sur le serveur de base de données (XAMPP/MySQL).

---

> [!TIP]
> Utilisez la commande `EXPLAIN` avant vos requêtes dans Tinker pour vérifier que les nouveaux index sont bien utilisés (colonne `key`).
