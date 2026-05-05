# Plan d'Intégration Multi-Transporteurs

Ce document décrit l'architecture technique pour supporter plusieurs transporteurs (ex: Faster.ae, DHL, FedEx) et router automatiquement les requêtes de tracking vers la bonne API.

## 🎯 Objectif
Permettre à l'admin de sélectionner un transporteur lors de l'attribution du tracking, et faire en sorte que la page client interroge automatiquement la bonne API sans action supplémentaire du client.

---

## 🏗️ Architecture des Données

### 1. Configuration des Transporteurs
Créer un fichier de configuration `config/shipping.php` pour lister les transporteurs supportés et leurs détails.

```php
return [
    'carriers' => [
        'faster_ae' => [
            'name' => 'Faster.ae',
            'api_driver' => 'App\Services\Tracking\Drivers\FasterAeDriver',
            'url' => 'https://op-api.faster.ae/service/status-logs/listWithBooking',
        ],
        'dhl' => [
            'name' => 'DHL Express',
            'api_driver' => 'App\Services\Tracking\Drivers\DhlDriver',
            'url' => 'https://api-eu.dhl.com/track/shipments',
        ],
        'fedex' => [
            'name' => 'FedEx',
            'api_driver' => 'App\Services\Tracking\Drivers\FedExDriver',
            'url' => 'https://apis.fedex.com/track/v1/trackingnumbers',
        ],
    ]
];
```

### 2. Base de Données
La colonne `tracking_carrier` dans la table `sourcing_orders` existe déjà.
- **Action** : Standardiser les valeurs stockées (ex: utiliser les clés `faster_ae`, `dhl` au lieu de texte libre).
- **Migration** : *Optionnelle*, mais un script de nettoyage pour les anciennes données peut être nécessaire.

---

## 🖥️ Interface Admin (Backend)

### Modification de la Vue Admin (`show.blade.php`)
Remplacer le champ texte actuel "Transporteur" par un menu déroulant (`<select>`).

- **Input** : Select box
- **Options** : Dynamiques basées sur `config('shipping.carriers')`
- **Exemple de Code** :
```html
<select name="tracking_carrier" class="...">
    @foreach(config('shipping.carriers') as $key => $carrier)
        <option value="{{ $key }}" {{ $order->tracking_carrier == $key ? 'selected' : '' }}>
            {{ $carrier['name'] }}
        </option>
    @endforeach
</select>
```

---

## 🔌 Gestionnaire de Drivers (Factory Pattern)

Pour garder le code propre et extensible, nous utiliserons le *Factory Pattern* pour instancier le bon service API.

### Interface Driver
Chaque transporteur devra implémenter une interface commune pour garantir un format de réponse unifié pour le frontend.

```php
interface TrackingDriverInterface {
    public function track(string $number): array;
}
```

### Response Formatter
Standardiser la réponse JSON renvoyée au frontend, peu importe ce que renvoie l'API d'origine (DHL, FedEx, etc.).

```json
{
    "status": "In Transit",
    "location": "Dubai Hub",
    "date": "2024-12-16 14:00:00",
    "history": [ ... ]
}
```

---

## 🚀 Logique Controller (`TrackingController`)

La méthode `data()` sera mise à jour pour :
1. Récupérer la commande associée au numéro de tracking (si possible) ou accepter le paramètre `carrier` via l'URL.
2. Instancier le bon driver via une factory.
3. Appeler l'API externe.

```php
public function data(Request $request) {
    $number = $request->number;
    
    // 1. Trouver le transporteur associé à ce numéro dans notre DB
    $order = SourcingOrder::where('tracking_number', $number)->first();
    
    if (!$order) {
        return response()->json(['error' => 'Numéro inconnu'], 404);
    }
    
    $carrierKey = $order->tracking_carrier; // ex: 'faster_ae'
    
    // 2. Factory: Obtenir le bon driver
    $driver = TrackingDriverFactory::make($carrierKey);
    
    // 3. Récupérer les données
    $data = $driver->track($number);
    
    return response()->json(['data' => $data]);
}
```

---

## 🌐 Frontend (Client)

Le code JavaScript `index.blade.php` n'aura **PAS** besoin de changements majeurs car le Controller renverra toujours une structure JSON unifiée (grâce au *Response Formatter*).

Le client entre juste son numéro, et le backend "sait" quel transporteur interroger.

---

## ✅ Étapes d'Implémentation Résumées

1.  **Config** : Créer `config/shipping.php`.
2.  **Interface** : Créer `TrackingDriverInterface`.
3.  **Drivers** : Créer `FasterAeDriver.php` (déplacer la logique actuelle ici) et préparer les classes vides pour DHL/FedEx.
4.  **Factory** : Créer `TrackingDriverFactory.php`.
5.  **Admin UI** : Changer l'input texte en Dropdown dans la vue Admin.
6.  **Controller** : Mettre à jour `TrackingController` pour utiliser la Factory.

Ceci rend votre système **robuste** et **prêt pour l'avenir**. Ajouter un nouveau transporteur consistera simplement à ajouter une classe Driver et une ligne dans la config !




et autre chose dons le sourcing order ona changement de sourcing order manulelle alors si le sourcing order has a shipping company assosited alors onva remplacer chaque fois le client a user tracking number fonctionalites o recupere last statue et le affiche comme sourcing order statue mais si order pas de shipping compnay assosieted on laise le changemenr de statue manuelle