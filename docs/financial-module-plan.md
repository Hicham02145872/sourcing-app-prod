# Plan d'Intégration : Module de Gestion Financière et Contrôle Interne

Ce document détaille le plan technique pour l'intégration du module financier sécurisé et l'amélioration du workflow des commandes.

## 1. Structure de la Base de Données

### 1.1. Données Financières (Table `sourcing_orders`)
Ajout de champs strictement réservés aux administrateurs pour le calcul de rentabilité.

```php
Schema::table('sourcing_orders', function (Blueprint $table) {
    // Coûts Internes (Cachés au client)
    $table->decimal('product_cost_price', 10, 2)->nullable()->comment('Coût d\'achat réel du produit');
    $table->decimal('shipping_cost_real', 10, 2)->nullable()->comment('Coût d\'expédition réel');
    $table->decimal('rejection_loss_cost', 10, 2)->default(0)->comment('Coûts liés aux retours/annulations');
    
    // Résultat Calculé
    $table->decimal('net_profit_or_loss', 10, 2)->nullable()->index()->comment('Résultat net calculé');
});
```

### 1.2. Attribution des Requêtes (Table `sourcing_requests`)
Gestion de l'exclusivité du traitement des dossiers.

```php
Schema::table('sourcing_requests', function (Blueprint $table) {
    $table->foreignId('assigned_to_admin_id')->nullable()->constrained('users')->onDelete('set null');
    $table->timestamp('assigned_at')->nullable();
});
```

---

## 2. Logique Métier et Modèles

### 2.1. Calcul Automatique (SourcingOrder)
Implémentation d'un `Observer` ou de la méthode `booted()` pour garantir que le profit est recalculé à chaque modification d'un coût.

**Formule de Calcul :**
> `Net Profit` = `Total Sales Price` - (`Product Cost` + `Shipping Cost Real` + `Rejection Loss`)

**Logique :**
- Si les coûts sont `NULL`, le profit n'est pas calculé (ou calculé partiellement selon les règles métier).
- Recalcul automatique lors de la mise à jour (Event `saving`).

### 2.2. Contrôle d'Accès et Visibilité
- **Modèles :** Utiliser la propriété `$hidden` pour exclure automatiquement ces champs des conversions JSON/Array par défaut.
- **API Resources :** Créer des ressources distinctes (`AdminOrderResource` vs `ClientOrderResource`) pour garantir qu'aucune donnée financière ne fuite vers le frontend client.

---

## 3. Workflow et Sécurité (Access Control)

### 3.1. Mécanisme de "Verrouillage" (Claiming System)
Lorsqu'un administrateur accède à une demande :
1. **Check :** Si `assigned_to_admin_id` est NULL.
2. **Action :** Assigner immédiatement à l'admin courant (`Auth::id()`).
3. **Restriction :**
   - **Liste des demandes :** Filtrer la vue par défaut pour ne montrer que "Mes Dossiers" + "Non Assignés".
   - **Protection :** Si un Admin B tente d'accéder à un dossier de l'Admin A -> `403 Forbidden` (Sauf Super Admin).

### 3.2. Visibilité des Données (Admin Only)
Utilisation de Policies Laravel (`SourcingOrderPolicy`) :
- `viewFinancials($user, $order)`: Autoriser uniquement si `role === 'admin'` ou `'super_admin'`.

---

## 4. Intégration avec le Système Existant (Sales Margin Report)

### 4.1. Adaptation du Contrôleur de Rapport (`ReportController`)
Actuellement, le rapport calcule le profit à la volée (ex: `unit_price * 0.90`). Nous devons migrer vers l'utilisation des nouveaux champs persistants.

**Logique de Transition :**
```php
// Dans prepareWeeklyProfitChartData et autres méthodes de calcul
$profit = $order->net_profit_or_loss;

// Fallback (si les coûts ne sont pas encore saisis)
if (is_null($profit)) {
    // Logique temporaire ou valeur 0
    // $profit = $order->total_amount - ($estimated_cost); 
}
```

### 4.2. Mise à jour de la Vue `sales-margin.blade.php`
La vue utilise déjà `$order->net_profit_or_loss`. Nous devons nous assurer que :
1. Les colonnes de coûts (`product_cost_price`, `shipping_cost_real`) sont ajoutées aux exports Excel (`SalesMarginExport`).
2. Les filtres ("Marge Négative", "Marge Faible") utilisent ce nouveau champ calculé.

---

## 5. Modifications Frontend (Admin UI)

### 5.1. Interface de Gestion des Coûts (Admin Only)
Ajout d'une section "Contrôle Financier" dans la page de détail de la commande (`resources/views/admin/sourcing-orders/show.blade.php`).

**Composants à ajouter :**
- **Carte "Internal Costs" :** Visible uniquement par Admin/SuperAdmin.
- **Champs d'Édition :**
  - `Product Cost` (Input Type: Number)
  - `Shipping Cost (Real)` (Input Type: Number)
  - `Rejection/Loss` (Input Type: Number)
- **Calculateur Temps Réel :** Affichage dynamique de la marge prévisionnelle en JS avant sauvegarde.
- **Bouton "Update Financials" :** Soumission AJAX pour ne pas recharger toute la page.

### 5.2. Sécurité Frontend
- Encapsuler tout ce bloc HTML dans une directive Blade `@if(auth()->user()->isAdmin()) ... @endif`.
- S'assurer que ces champs ne sont **JAMAIS** rendus dans les vues côté client (`client.sourcing-orders.show`).

---


---


