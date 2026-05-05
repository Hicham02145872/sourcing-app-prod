# Plan de Conception : Système de Reporting sur la Marge Commerciale

## 1. Introduction et Objectif

L'objectif est de créer un rapport pour les **Admins** et **Super Admins** afin de visualiser la rentabilité de chaque vente. Ce rapport affichera des informations clés sur les produits vendus, incluant les prix, les coûts, et les marges.

Le rapport devra contenir les colonnes suivantes pour chaque commande (`SourcingOrder`) :
- **ID de la Commande**
- **Nom du Produit**
- **Quantité**
- **Destination**
- **Date de Création**
- **Prix de Vente Total** (Prix du produit `amount` de la `SourcingOrder`)
- **Prix de Vente Unitaire** (`unit_price` de la `Quotation`)
- **Prix de Vente Total (-20%)** (Calculé à partir du `Prix de Vente Total`)
- **Coût d'Achat Calculé (€)** (`unit_price * 0.90 * quantity` de la `SourcingRequest`)
- **Marge Brute (€)** (Prix de Vente Total - Coût d'Achat Calculé)
- **Marge Brute (%)** (Calcul basé sur Prix de Vente Total et Coût d'Achat Calculé)

---

## 2. La Meilleure Approche : Une Solution Intégrée et Dynamique

Plutôt qu'un simple export de données, la meilleure approche est de construire une **page de rapport interactive** directement dans le panel d'administration, enrichie d'une fonction d'export.

Nous utiliserons **Laravel Livewire**. C'est l'approche la plus moderne et efficace pour ce besoin dans l'écosystème Laravel car elle permet de créer des interfaces dynamiques (filtres, tri, pagination) avec la simplicité de PHP, sans avoir à écrire de JavaScript complexe.

**Avantages de cette approche :**
- **Expérience Utilisateur (UX) Supérieure** : Les filtres (par date, par destination) s'appliquent instantanément sans recharger la page.
- **Performance** : Livewire ne recharge que les parties de la page qui changent.
- **Développement Rapide** : Toute la logique reste en PHP (contrôleur, composant).
- **Intégration Parfaite** : S'intègre nativement avec l'authentification et les policies de Laravel.

---

## 4. Étape 2 : Implémentation du Rapport avec Livewire

### 4.1. Création du Composant Livewire
```bash
php artisan make:livewire Admin/Reports/SalesMarginReport
```
Cette commande générera deux fichiers :
- `app/Http/Livewire/Admin/Reports/SalesMarginReport.php` (la logique)
- `resources/views/livewire/admin/reports/sales-margin-report.blade.php` (la vue)

### 4.2. Logique du Composant (`SalesMarginReport.php`)
```php
<?php
namespace App\Http\Livewire\Admin\Reports;

use Livewire\Component;
use App\Models\SourcingOrder;
use Livewire\WithPagination;

class SalesMarginReport extends Component
{
    use WithPagination;

    public $startDate;
    public $endDate;
    public $destinationFilter;
    public $sortColumn = 'created_at';
    public $sortDirection = 'desc';

    public function render()
    {
        $query = SourcingOrder::with('quotation.sourcingRequest.destinations.country', 'user')
                    ->where('status', 'paid'); // Ou d'autres statuts pertinents

        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }

        // ... ajouter la logique pour le filtre par destination ...

        $orders = $query->orderBy($this->sortColumn, $this->sortDirection)->paginate(20);

        return view('livewire.admin.reports.sales-margin-report', [
            'orders' => $orders,
        ]);
    }
    
    // Méthode pour le tri des colonnes
    public function sortBy($column)
    {
        // ...
    }
}
```

### 4.3. Vue du Composant (`sales-margin-report.blade.php`)
```html
<div>
    <!-- Section des Filtres -->
    <div class="filters">
        <input type="date" wire:model="startDate">
        <input type="date" wire:model="endDate">
        <!-- ... autres filtres ... -->
    </div>

    <!-- Tableau des Résultats -->
    <table>
        <thead>
            <tr>
                <th wire:click="sortBy('id')">ID</th>
                <th>Produit</th>
                <th>Destination</th>
                <th wire:click="sortBy('created_at')">Date</th>
                <th>Prix Vente Total</th>
                <th>Prix Vente Unitaire</th>
                <th>Prix Vente Total (-20%)</th>
                <th>Coût d'Achat Calculé (€)</th>
                <th>Marge (€)</th>
                <th>Marge (%)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->quotation->sourcingRequest->product_name }}</td>
                    <td>{{ $order->quotation->sourcingRequest->destinations->first()->country->name }}</td>
                    <td>{{ $order->created_at->format('d/m/Y') }}</td>
                    <td>{{ $order->total_amount }}</td>
                    <td>{{ number_format($order->quotation->unit_price * 0.80 * $order->quotation->sourcingRequest->quantity, 2) }}</td>
                    @php
                        $calculatedCost = $order->quotation->unit_price * 0.90 * $order->quotation->sourcingRequest->quantity;
                        $margin = $order->total_amount - $calculatedCost;
                        $marginPercentage = ($order->total_amount > 0) ? ($margin / $order->total_amount) * 100 : 0;
                    @endphp
                    <td>{{ number_format($calculatedCost, 2) }}</td>
                    <td>{{ number_format($margin, 2) }}</td>
                    <td>{{ number_format($marginPercentage, 2) }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $orders->links() }}
</div>
```

---

## 5. Étape 3 : Export des Données (CSV/Excel)

La meilleure librairie pour cela est **Laravel Excel** (`maatwebsite/excel`).

1.  **Installation :**
    ```bash
    composer require maatwebsite/excel
    ```
2.  **Créer une classe d'Export :**
    ```bash
    php artisan make:export SalesMarginExport --model=SourcingOrder
    ```
3.  **Ajouter un bouton d'export dans la vue Livewire :**
    ```html
    <button wire:click="export">Exporter en Excel</button>
    ```
4.  **Ajouter la méthode `export()` dans le composant Livewire :**
    ```php
    // Dans SalesMarginReport.php

    use App\Exports\SalesMarginExport;
    use Maatwebsite\Excel\Facades\Excel;

    public function export()
    {
        // On utilise la même logique de requête que pour l'affichage
        $data = $this->getFilteredQuery()->get(); // Méthode à créer qui retourne la query
        
        return Excel::download(new SalesMarginExport($data), 'rapport_marges.xlsx');
    }
    ```

---

## 6. Étape 4 : Visualisation des Données - Tableaux de Bord et Graphiques

Pour une analyse visuelle, nous intégrerons des graphiques dynamiques.

**Technologie recommandée :**
- **Chart.js** : Une bibliothèque JavaScript flexible et populaire pour créer des graphiques.
- **Alpine.js** : Pour initialiser les graphiques et faire le lien entre les données préparées par Livewire (PHP) et Chart.js (JavaScript).

### 6.1. Intégration
1.  **Installation** : Ajoutez les scripts de Chart.js et Alpine.js dans votre page de layout principale (si ce n'est pas déjà fait).
2.  **Flux de données** : Le composant Livewire préparera des ensembles de données (labels, valeurs) en PHP. Ces données seront ensuite passées à la vue et utilisées par Alpine.js pour construire les graphiques.

### 6.2. Graphiques à Implémenter

#### A. Profit Hebdomadaire (Marge Brute)
- **Objectif** : Visualiser l'évolution des profits semaine par semaine.
- **Type de graphique** : Graphique à barres ou en courbes.
- **Logique Backend** : Dans le composant Livewire, créer une méthode qui regroupe les commandes par semaine et calcule la somme des marges pour chaque semaine.
- **Exemple de données préparées par Livewire :**
  ```json
  {
    "labels": ["Semaine 48", "Semaine 49", "Semaine 50"],
    "data": [1250.50, 2300.00, 1850.75]
  }
  ```

#### B. Produits Expédiés avec Succès
- **Objectif** : Suivre le volume d'opérations terminées.
- **Type de graphique** : Graphique en courbes.
- **Logique Backend** : Compter le nombre de `SourcingOrder` qui sont passées au statut `delivered` ou `order_completed`, groupées par semaine ou par mois.

#### C. Top 5 des Produits les Plus Commandés
- **Objectif** : Identifier les produits les plus populaires.
- **Type de graphique** : Graphique à barres horizontales.
- **Logique Backend** : Compter le nombre de fois que chaque `product_name` (via `SourcingRequest`) apparaît dans les commandes, puis trier pour obtenir le top 5.

### 6.3. Exemple d'implémentation dans la vue Livewire
```html
<!-- Dans sales-margin-report.blade.php -->

<div class="charts-container grid grid-cols-1 md:grid-cols-2 gap-6">
    
    <!-- Graphique Profit Hebdomadaire -->
    <div class="chart-box"
         wire:init="prepareWeeklyProfitChartData"
         x-data="{
             labels: @entangle('weeklyProfitChart.labels'),
             data: @entangle('weeklyProfitChart.data'),
             init() {
                 new Chart($refs.canvas, {
                     type: 'bar',
                     data: {
                         labels: this.labels,
                         datasets: [{
                             label: 'Profit Hebdomadaire',
                             data: this.data,
                             backgroundColor: 'rgba(54, 162, 235, 0.5)',
                         }]
                     }
                 });
             }
         }">
        <canvas x-ref="canvas"></canvas>
    </div>

    <!-- Autres graphiques ici... -->

</div>

### 6.4. Autres Idées de Visualisation Pertinentes

Pour un tableau de bord encore plus complet, voici d'autres indicateurs clés (KPIs) à considérer :

#### D. Performance par Destination
- **Objectif** : Identifier les marchés les plus porteurs et les plus rentables.
- **Visualisation** :
    - **Simple** : Un tableau triable "Chiffre d'Affaires / Marge par Pays".
    - **Avancé** : Une carte du monde (Choroplèthe) où les pays sont colorés en fonction de leur volume de ventes ou de leur profit.
- **Logique** : Grouper les commandes par pays de destination et sommer le `total_amount` et la marge.

#### E. Délai de Traitement Moyen des Commandes
- **Objectif** : Mesurer l'efficacité opérationnelle de la chaîne logistique.
- **Visualisation** : Une carte KPI affichant le "Temps moyen entre le paiement (`paid`) et la livraison (`delivered`)".
- **Logique (Avancé)** : Cette métrique nécessite de stocker l'historique des changements de statut avec leur date. Une approche plus simple serait d'ajouter des colonnes `paid_at` et `delivered_at` à la table `sourcing_orders`, qui seraient remplies automatiquement lors du changement de statut.

#### F. Top 5 des Clients
- **Objectif** : Identifier et fidéliser vos clients les plus importants.
- **Visualisation** : Un graphique à barres "Top 5 des Clients par Chiffre d'Affaires".
- **Logique** : Grouper les commandes par `user_id`, sommer le `total_amount`, et joindre avec la table `users` pour obtenir les noms.

#### G. Répartition des Revenus (Produits vs. Commission)
- **Objectif** : Comprendre d'où proviennent les revenus.
- **Visualisation** : Un graphique en secteurs (camembert) montrant la part du `commission_service` par rapport au coût des produits.
- **Logique** : Sommer le `commission_service` de toutes les `Quotations` pour la période et le comparer au total des ventes.
```

---

## 7. Étape 5 : Routage et Sécurité

1.  **Ajouter la Route :** Dans `routes/web.php`, à l'intérieur du groupe `admin` :
    ```php
    // Dans le groupe de routes pour 'admin'
    Route::get('/reports/sales-margin', \App\Http\Livewire\Admin\Reports\SalesMarginReport::class)->name('reports.sales-margin');
    ```

2.  **Sécurité :** La route est automatiquement protégée par le middleware `role:admin`, qui s'applique à tout le groupe. L'accès est donc déjà limité aux Admins et Super Admins.

---

## 8. Conclusion

Cette approche fournit une solution de reporting complète, professionnelle et évolutive. Elle est bien supérieure à un simple export de données car elle offre aux administrateurs un **outil de travail dynamique** pour analyser la performance des ventes en temps réel. L'utilisation de Livewire garantit une expérience utilisateur moderne et l'ajout de la fonction d'export répond aux besoins d'analyse hors ligne.
