# Analyse Approfondie des Frais d'Expédition (Shipping Fees)

## 1. Architecture Générale — Dualité du Système

L'application possède **deux systèmes distincts** liés aux frais d'expédition :

| Système | Rôle | Tables |
|---|---|---|
| **Grille Tarifaire** (Rate Card) | Afficher des prix estimés au client, calculer une estimation lors de la création de demande | `shipping_fees`, `shipping_fee_items` |
| **Suivi Financier des Commandes** (Order Costs) | Tracker les vrais coûts d'expédition, calculer la marge bénéficiaire | `sourcing_orders.shipping_cost_real`, `quotations.estimated_shipping_cost` |

---

## 2. Modèle de Données Relationnel

### 2.1 `shipping_fees` — Frais par Pays

| Champ | Type | Description |
|---|---|---|
| `id` | bigint PK | |
| `country_id` | FK → `countries.id` (unique, cascade delete) | Pays desservi |
| `currency` | varchar(255), default `'USD'` | Devise des prix |
| `unit` | varchar(255), default `'kg'` | Unité legacy (backward compat) |
| `air_unit` | varchar(10), nullable | Unité pour le transport aérien (`KG` ou `CBM`) |
| `sea_unit` | varchar(10), nullable | Unité pour le transport maritime |
| `train_unit` | varchar(10), nullable | Unité pour le transport train/hub Dubai |
| `air_arrival_time` | varchar(255), nullable | Délai estimé (ex. `"7-9"`) |
| `sea_arrival_time` | varchar(255), nullable | Délai estimé (ex. `"30-45"`) |
| `train_arrival_time` | varchar(255), nullable | Délai estimé (ex. `"15-20"`) |
| `created_at` / `updated_at` | timestamp | |

### 2.2 `shipping_fee_items` — Lignes Tarifaires par Catégorie

| Champ | Type | Description |
|---|---|---|
| `id` | bigint PK | |
| `shipping_fee_id` | FK → `shipping_fees.id` (cascade delete) | |
| `transport_type` | varchar(255) | `air`, `sea` ou `train` |
| `item_style` | varchar(255) | Catégorie de marchandise (ex. `"General Cargo (No Brand)"`) |
| `price_per_kg` | decimal(10,2), nullable | Prix par unité (kg ou CBM) |
| `estimation_days` | varchar(255), nullable | Délai de livraison (ex. `"7-9"`, `"about 5-8 days"`) |
| `estimation_unit` | varchar(255), default `'days'` | Unité du délai (`days` ou `months`) |
| `created_at` / `updated_at` | timestamp | |

**Index unique :** `(shipping_fee_id, transport_type, item_style)`

### 2.3 `shipping_companies` — Transporteurs

| Champ | Type | Description |
|---|---|---|
| `id` | bigint PK | |
| `name` | varchar(255) | Nom du transporteur |
| `tracking_provider` | varchar(255), nullable | `itdida`, `faster`, `choicexp`, `ups`, `gcc` |
| `carrier_options` | json, nullable | Sous-transporteurs (tableau) |
| `google_sheet_id` | varchar(255), nullable | Intégration Google Sheets |
| `sheet_name` | varchar(255), default `'sourcing'` | Nom de la feuille |
| `is_active` | tinyint(1), default `true` | Actif ou non |
| `lark_app_id / secret / base_token / table_id` | varchar(255), nullable | Intégration Lark |
| `created_at` / `updated_at` | timestamp | |

### 2.4 `sourcing_order_destination_shipments` — Expéditions par Destination

| Champ | Type | Description |
|---|---|---|
| `id` | bigint PK | |
| `sourcing_order_id` | FK → `sourcing_orders.id` (cascade delete) | |
| `sourcing_request_destination_id` | FK → `sourcing_request_destinations.id` (cascade delete) | |
| `tracking_number` | varchar(255), nullable | Numéro de suivi |
| `tracking_carrier` | varchar(255), nullable | Transporteur utilisé |
| `shipping_company_id` | FK → `shipping_companies.id` (null on delete), nullable | |
| `created_at` / `updated_at` | timestamp | |

**Index unique :** `(sourcing_order_id, sourcing_request_destination_id)` nommé `order_destination_unique`

### 2.5 Champs d'Expédition dans `sourcing_orders`

| Champ | Type | Description |
|---|---|---|
| `shipping_cost_real` | decimal(10,2), nullable | Coût réel constaté |
| `initial_estimated_shipping_cost` | decimal(10,2), nullable | Copié depuis la soumission acceptée |
| `shipping_company_id` | FK → `shipping_companies.id` (null on delete), nullable | Transporteur assigné |

### 2.6 Champs d'Expédition dans `quotations`

| Champ | Type | Description |
|---|---|---|
| `estimated_shipping_cost` | decimal(10,2), nullable | Estimation saisie par l'admin |

---

## 3. Relations Entité

```
Country (1) ───── (1) ShippingFee (1) ───── (*) ShippingFeeItem
  │                                            ├─ transport_type (air/sea/train)
  │                                            ├─ item_style (catégorie)
  │                                            ├─ price_per_kg (tarif)
  │                                            └─ estimation_days
  │
  └─── SourcingRequestDestination ── country_id

ShippingCompany (1) ──── (*) SourcingOrder
  │                           ├─ shipping_cost_real
  │                           ├─ initial_estimated_shipping_cost
  │                           └─ shipping_company_id (FK)
  │
  └─── (*) SourcingOrderDestinationShipment
          ├─ tracking_number
          ├─ tracking_carrier
          └─ shipping_company_id (FK)

Quotation (1) ──── (1) SourcingOrder
  ├─ estimated_shipping_cost  → initial_estimated_shipping_cost
  ├─ estimated_product_cost   → product_cost_price
  └─ estimated_other_costs    → rejection_loss_cost
```

---

## 4. Algorithme de Calcul des Frais

### 4.1 Principe Fondamental

Le système utilise une **table de lookup** (pas de formule dynamique). Le calcul est :

```
Coût d'expédition = Quantité (kg ou CBM) × price_per_kg
```

Où `price_per_kg` est en réalité un **prix unitaire** (l'unité peut être KG ou CBM selon le mode de transport).

### 4.2 Détermination de l'Unité (`getUnitForTransport`)

```php
// ShippingFee.php
public function getUnitForTransport(string $transportType): string
{
    // 1. Utilise l'unité spécifique au transport (air_unit, sea_unit, train_unit)
    // 2. Sinon, utilise l'unité legacy ($this->unit)
    // 3. Sinon, défaut : 'CBM' pour sea, 'KG' pour air/train
}
```

### 4.3 Catégories de Marchandises (8 par défaut)

1. Electr & Magnet (No Brand)
2. Electr & Magnet (With Brand)
3. General Cargo (No Brand)
4. General Cargo (With Brand)
5. Power Bank, Battery, Cosmetic
6. Screens, Electr & Mag (No Brand)
7. Screens, Electr & Mag (With Brand)
8. Health Care Products

### 4.4 Évolution Historique

1. **Version originale** : Colonnes `sea_fee`, `train_fee`, `air_normal_fee`, `air_brand_fee`, `air_battery_fee`, `air_liquid_fee` directement dans `shipping_fees`
2. **Refonte v1** : Table `shipping_fee_items` avec paliers de volume (`price_16_49`, `price_50_99`, `price_100_499`, `price_plus_500`)
3. **Refonte v2** (2026-01-01) : Remplacement par un `price_per_kg` unique, ajout des `arrival_time` par transport
4. **Final** : Ajout des unités indépendantes par transport (`air_unit`, `sea_unit`, `train_unit`), et `estimation_unit` (`days`/`months`)

---

## 5. Flux Détaillés

### 5.1 FLUX A — Client consulte la grille tarifaire

```
1. User clique "Shipping Fees" dans le menu
2. GET /{locale}/client/shipping-fees
3. Client\ShippingFeeController@index → vue client.shipping-fees.index
4. Livewire: <livewire:client.shipping-fees-list>
5. ShippingFeesList::render()
   → Country::with('shippingFee.items')
           ->whereHas('shippingFee')
           ->orderBy('name')->paginate(12)
6. User clique sur un pays → selectCountry($countryId)
   → Country::with('shippingFee.items')->findOrFail($countryId)
7. User navigue entre les onglets Air/Sea/Train
   → filterItemsForTransportTab() filtre par transport_type
   → Logique spéciale Dubai : les items "dubai/uae/emirates/united arab"
     sont TOUJOURS sous l'onglet Train (même si stockés en air/sea)
8. Affichage :
   - Prix : {{ number_format($price_per_kg, 2) }} USD
   - Estimation : ex. "7-9 days"
```

### 5.2 FLUX B — Création d'une Demande (Sourcing Request)

```
1. User remplit le formulaire de demande
2. Sélectionne :
   - shipping_method = 'air' | 'sea'
   - sourcing_location = 'china' | 'dubai'
   - Destinations (pays, quantité, adresse)
3. Submit → Alpine.js submitForm()
4. Pour chaque destination :
   GET /{locale}/client/shipping-fees/{countryId}?transport={method}&sourcing={location}
   → Client\ShippingFeeController@getShippingFee()
   → Country::load('shippingFee.items')
   → Filtre items par transport_type
   → Retourne JSON avec items, devise, unité, délais
5. Modal affiché avec tableau récapitulatif :
   - Item Style | Price/Unit | Delay | Est. Total
   - Est. Total = price_per_kg × quantity
6. User confirme → submitFormDirectly() envoie le formulaire
```

### 5.3 FLUX C — Admin gère la grille tarifaire

```
1. Admin → "Shipping Fees" → vue admin.shipping-fees.index
2. Livewire: <livewire:admin.shipping-fees-table>
   → Liste tous les pays avec badges (air/sea/train)
3. Admin clique "Edit" sur un pays
   → GET /admin/shipping-fees/{country}/edit
   → Livewire: <livewire:admin.shipping-fee-edit :country="$country">
4. Form affiche 3 onglets (Air/Sea/Train)
   Chaque onglet : 8 catégories prédéfinies + possibilité d'en ajouter
5. Admin remplit :
   - Currency (TomSelect depuis config('currencies'))
   - Unités par transport (KG ou CBM)
   - Arrival times
   - Par catégorie : price_per_kg, estimation_days, estimation_unit
6. Save() → ShippingFee::updateOrCreate() + sync des items
   → Validation : pas de doublons de catégories dans un même transport
   → Soft-delete via flag _deleted, suppression physique à la sauvegarde
7. Redirect vers l'index
```

### 5.4 FLUX D — Import Excel

```
1. Admin → "Importer Excel"
2. Upload fichier .xlsx
3. POST /admin/shipping-fees/import/excel
4. ShippingFeesFromAirFreightDDPImport (Maatwebsite/LaravelExcel)
5. Détection automatique de la ligne d'en-tête (scan 40 premières lignes)
   Colonnes reconnues :
   - item_style : "item style", "service", "goods type", "product type"
   - destination : "destination", "country", "pays"
   - price : "charge weight", "price", "unit price"
   - estimation : "arrive time", "arrival time", "transit time", "delivery", "working days", "days"
6. Gestion des cellules fusionnées (fill-down)
7. Split des destinations multiples (virgule, point-virguile, slash)
8. Résolution des noms de pays (code/nom exact puis LIKE)
9. Création/Mise à jour des enregistrements (transport_type = 'air' forcé)
10. Rapport : importés, ignorés, erreurs
```

### 5.5 FLUX E — De la Soumission à la Commande

```
1. Admin crée un devis (quotation) avec estimated_shipping_cost
2. Client accepte le devis
3. Événement QuotationAccepted déclenché
4. Listener CopyEstimatesToSourcingOrder :
   - estimated_shipping_cost → initial_estimated_shipping_cost
   - estimated_shipping_cost → shipping_cost_real (valeur initiale)
5. Admin peut modifier shipping_cost_real plus tard
6. SourcingOrderObserver::saving() :
   - net_profit_or_loss = sales - (productCost + shipping + loss + refund)
7. getCostVariance() :
   - shipping_variance = shipping_cost_real - initial_estimated_shipping_cost
```

---

## 6. Détails Techniques par Composant

### 6.1 Modèles

#### `ShippingFee` (`app/Models/ShippingFee.php`)
- `$fillable`: `country_id`, `*_arrival_time`, `currency`, `unit`, `*_unit`
- Relations: `belongsTo(Country)`, `hasMany(ShippingFeeItem)`
- Méthode: `getUnitForTransport(string $transportType): string`
  - Ordre de résolution : unité spécifique → unité legacy → défaut (KG/CBM)

#### `ShippingFeeItem` (`app/Models/ShippingFeeItem.php`)
- `$fillable`: `shipping_fee_id`, `transport_type`, `item_style`, `price_per_kg`, `estimation_days`, `estimation_unit`
- Relation: `belongsTo(ShippingFee)`
- Pas de cast spécifique (price_per_kg est decimal(10,2) en base)

#### `ShippingCompany` (`app/Models/ShippingCompany.php`)
- `$casts`: `is_active => boolean`, `carrier_options => array`
- Méthode: `getCarrierOptionsWithLabels(): array`
  - Mappe les clés (ex. `gcc`) vers les labels depuis `config('tracking.carrier_labels')`

#### `SourcingOrderDestinationShipment` (`app/Models/SourcingOrderDestinationShipment.php`)
- `$fillable`: `sourcing_order_id`, `sourcing_request_destination_id`, `tracking_number`, `tracking_carrier`, `shipping_company_id`
- Relations: `belongsTo(SourcingOrder)`, `belongsTo(SourcingRequestDestination)`, `belongsTo(ShippingCompany)`

### 6.2 Contrôleurs

#### `Admin\ShippingFeeController`
- `index()`: Retourne la vue admin avec Livewire
- `edit(Country $shipping_fee)`: Attention — le paramètre est nommé `$shipping_fee` mais reçoit un `Country` à cause du resource binding
- `importForm()` / `import()`: Upload et import Excel

#### `Client\ShippingFeeController`
- `index(string $locale)`: Page publique des frais
- `getShippingFee(string $locale, Country $country, Request $request)`:
  - Endpoint AJAX pour le formulaire de demande
  - Paramètres: `transport` (air/sea), `sourcing` (china/dubai)
  - Retour JSON avec items, devise, unité, délais

#### `Admin\ShippingCompanyController`
- `index()`: Vue de gestion des transporteurs

### 6.3 Livewire Components

#### `Admin\ShippingFeesTable`
- Pagination (20 pays/page)
- Recherche par nom ou code
- Vue liste avec badges de statut (air/sea/train)

#### `Admin\ShippingFeeEdit`
- Gère tout le formulaire d'édition
- États: `currency`, `transportUnits`, `itemsData[air|sea|train][]`
- Validation: doublons de catégories, devise valide
- Sauvegarde via `updateOrCreate` et synchronisation des items

#### `Client\ShippingFeesList`
- Pagination (12 pays/page)
- Recherche
- Sélection de pays → onglets de détail (air/sea/train)
- Logique UAE Hub : les items "dubai/uae/emirates/united arab" sont affichés sous l'onglet Train uniquement
- `filterItemsForTransportTab()` : filtre les items par transport + exclut/inclut les items UAE

#### `Admin\ShippingCompanyManager`
- CRUD complet pour les transporteurs
- Intégrations Google Sheets et Lark
- Gestion des options de transporteurs enfants (`carrier_options`)
- Tests de connexion

### 6.4 Services

#### `ShippingLabelImageService`
- Génère une image PNG d'étiquette d'expédition
- Convertit PDF → PNG via Imagick
- Caching sur disque public
- Deux vues : générale (admin) ou par destination

#### `ShippingCompanySheetService`
- Intégration Google Sheets API
- Upsert des lignes de commande
- Formatage (en-têtes, hauteurs de ligne, couleurs)
- Annulation : fond rouge clair + barré

### 6.5 Import Excel

#### `ShippingFeesFromAirFreightDDPImport`
- Implémente `Maatwebsite\Excel\Concerns\ToCollection`
- Détection multi-langue (EN/FR) des en-têtes
- Gestion cellules fusionnées
- Parsing prix flexible (`$12.50`, `12,50`, `12.50`)
- Support destinations multiples par cellule
- Force le `transport_type` à `'air'`

---

## 7. Routes

### Admin (`/admin`)

| Méthode | URI | Controller@method | Nom |
|---|---|---|---|
| GET | `/shipping-fees` | `Admin\ShippingFeeController@index` | `admin.shipping-fees.index` |
| GET | `/shipping-fees/create` | `Admin\ShippingFeeController@create` | `admin.shipping-fees.create` |
| POST | `/shipping-fees` | `Admin\ShippingFeeController@store` | `admin.shipping-fees.store` |
| GET | `/shipping-fees/{shipping_fee}/edit` | `Admin\ShippingFeeController@edit` | `admin.shipping-fees.edit` |
| PUT/PATCH | `/shipping-fees/{shipping_fee}` | `Admin\ShippingFeeController@update` | `admin.shipping-fees.update` |
| DELETE | `/shipping-fees/{shipping_fee}` | `Admin\ShippingFeeController@destroy` | `admin.shipping-fees.destroy` |
| GET | `/shipping-fees/import/excel` | `@importForm` | `shipping-fees.import` |
| POST | `/shipping-fees/import/excel` | `@import` | `shipping-fees.import.run` |

### Client (`/{locale}/client`)

| Méthode | URI | Controller@method | Nom |
|---|---|---|---|
| GET | `/shipping-fees` | `Client\ShippingFeeController@index` | `shipping-fees.index` |
| GET | `/shipping-fees/{country}` | `Client\ShippingFeeController@getShippingFee` | `shipping-fees.get` |

---

## 8. Cas Particuliers et Logiques Spéciales

### 8.1 UAE Hub Item Style

Les items dont l'`item_style` contient "dubai", "uae", "emirates" ou "united arab" sont :
- **Toujours affichés sous l'onglet Train** (même si enregistrés comme air/sea)
- **Exclus des onglets Air et Sea**
- Logique gérée par `ShippingFeesList::filterItemsForTransportTab()` et `isUaeHubItemStyle()`

### 8.2 Absence de Tarifs

- Pas de `shippingFee` pour un pays → API retourne `{fee: null}`
- Pas d'items pour un mode → message "No rates for this transport mode."
- Aucun pays avec frais → "No rates indexed for this selection."
- Dans le modal de création de demande : si aucun item n'est trouvé, le formulaire est soumis directement

### 8.3 Import Excel Flexible

- Détection automatique des colonnes (multi-langue)
- Parsing des prix : nettoyage des caractères non numériques
- Résolution partielle des noms de pays (LIKE)
- Cellules fusionnées gérées par fill-down

### 8.4 Calcul de Marge / Variance

```php
// Dans SourcingOrderObserver::saving()
$shipping = $sourcingOrder->shipping_cost_real ?? 0;
$netProfit = $sales - ($productCost + $shipping + $loss + $refund);

// Dans SourcingOrder::getCostVariance()
'shipping_variance' => ($this->shipping_cost_real ?? 0) - ($this->initial_estimated_shipping_cost ?? 0)
```

### 8.5 Pas de Seuils de Livraison Gratuite

Le système n'a pas de logique de "free shipping above X€", ni de zonage tarifaire avancé. Le modèle est purement un lookup par pays × transport × catégorie.

---

## 9. Configuration

### `config/currencies.php`
- Tableau associatif de tous les codes ISO 4217 avec leurs libellés
- Utilisé par le TomSelect du formulaire admin

### `config/tracking.php`
- Tableau `carrier_labels` : mapping clé → label pour les transporteurs
- Ex. : `'gcc' => 'GCC / Faster'`, `'ups' => 'UPS'`

### Defaults (codés en dur)

| Propriété | Valeur par défaut |
|---|---|
| Devise | `USD` |
| Unité Air | `kg` |
| Unité Sea | `CBM` |
| Unité Train | `kg` |
| Arrival Time Air | `7-9` |
| Arrival Time Sea | `30-45` |
| Arrival Time Train | `15-20` |

---

## 10. Formatage des Montants

| Contexte | Format | Exemple |
|---|---|---|
| Page client (Blade) | `number_format((float)$price_per_kg, 2)` + `currency` | `12.50 USD` |
| Modal demande (Alpine.js) | `parseFloat(price_per_kg).toFixed(2)` | `12.50` |
| Estimation totale (Alpine.js) | `(price_per_kg * quantity).toFixed(2)` | `625.00` |
| Admin commandes | `${{ number_format($shipping_cost_real, 2) }}` | `$625.00` |
| Formulaires devis | `<input type="number" step="0.01">` | stocké en decimal(10,2) |

---

## 11. Tests

Un seul test d'intégration existe :
- `tests/Feature/ShippingFeesListTest.php` — Teste l'affichage de la liste des frais côté client

---

## 12. Résumé des Points Clés

1. **Deux systèmes distincts** : grille tarifaire (rate card) et coûts réels (order financials)
2. **Pas d'algorithme de calcul dynamique** : simple lookup `price_per_kg × quantité`
3. **Pas de paliers volumétriques** : l'historique (`price_16_49`, etc.) a été migré vers un prix unique
4. **Architecture par pays** : chaque pays a sa propre grille avec devise et unités configurables
5. **Hub UAE** : traité comme un mode "train" avec logique de filtrage spéciale
6. **Intégrations** : les transporteurs s'intègrent avec Google Sheets et Lark pour le suivi commandes
7. **Pas de free shipping** ni de zonage tarifaire avancé
8. **Formatage standardisé** : 2 décimales, devise en USD (majoritairement)
