# Plan d'Implémentation : Système de Suivi Multi-Sources (Multi-Source Tracking System)

Ce document décrit l'architecture pour unifier le suivi des colis provenant de multiples transporteurs sous un numéro de suivi interne unique.

## 1. Architecture du Système (Strategy Pattern)

Le défi est de gérer des sources de données hétérogènes (API JSON, Page Web HTML, Saisie Manuelle) de manière transparente. Nous utiliserons le **Strategy Pattern**.

### 1.1. Interface Standard (`TrackingProviderInterface`)
Chaque transporteur doit implémenter cette interface unifiée.

```php
interface TrackingProviderInterface
{
    /**
     * Retourne le nom interne du transporteur (ex: 'company_x', 'company_y')
     */
    public function getProviderCode(): string;

    /**
     * Récupère les infos de tracking normalisées
     * @param string $realTrackingNumber
     * @return TrackingStatusDTO
     */
    public function getTrackingStatus(string $realTrackingNumber): TrackingStatusDTO;
}
```

### 1.2. Objet de Transfert de Données (DTO)
Pour uniformiser la réponse quel que soit le transporteur.
```php
class TrackingStatusDTO
{
    public string $status;          // ex: 'delivered', 'in_transit'
    public string $location;        // ex: 'Dubai Hub'
    public string $timestamp;       // ex: '2024-12-14 10:00:00'
    public string $description;     // ex: 'Package received at facility'
    public array $history;          // Liste des événements passés
}
```

---

## 2. Base de Données

Les champs existent déjà dans la table `sourcing_orders`, nous allons les exploiter pleinement.

- **`tracking_carrier`** : Servira à stocker le **Nom du Driver** (ex: `dhl`, `fedex`, `china_post`, `manual`).
- **`tracking_number`** : Stockera le **Numéro Réel** du transporteur (ex: `Y-11223344`).

> **Note :** Le "Numéro Interne" visible par le client est simplement l'ID de la commande (ex: `#10050`) ou une référence unique existante.

---

## 3. Implémentation des Drivers (Adaptateurs)

### 3.1. Driver API (Ex: Entreprise X)
Utilise `Http::get()` pour interroger une API REST standard.

```php
class CompanyXProvider implements TrackingProviderInterface
{
    public function getTrackingStatus(string $trackingNumber): TrackingStatusDTO
    {
        $response = Http::get("https://api.company-x.com/track/{$trackingNumber}");
        // Mapping de la réponse JSON vers TrackingStatusDTO
    }
}
```

### 3.2. Driver Scraper (Ex: Entreprise Y)
Utilise `Goutte` ou `Symfony DomCrawler` pour extraire des données d'une page HTML publique.

```php
class CompanyYScraperProvider implements TrackingProviderInterface
{
    public function getTrackingStatus(string $trackingNumber): TrackingStatusDTO
    {
        $html = Http::get("https://company-y.com/exclude/tracking?id={$trackingNumber}");
        // Parsing HTML pour trouver <div class="status">...</div>
        // Mapping vers TrackingStatusDTO
    }
}
```

### 3.3. Driver Manuel (Fallback)
Permet à l'admin de saisir manuellement les étapes si aucun tracking automatique n'est possible.

---

## 4. Intégration dans l'App (`TrackingService`)

Le service central (`TrackingService`) agit comme le chef d'orchestre.

1.  Il reçoit une commande (`SourcingOrder`).
2.  Il lit `tracking_carrier`.
3.  Il instancie le bon Provider (`Factory Pattern`).
4.  Il appelle `getTrackingStatus`.
5.  Il retourne le résultat unifié.

```php
class TrackingService
{
    public function track(SourcingOrder $order)
    {
        $provider = TrackingProviderFactory::make($order->tracking_carrier);
        return $provider->getTrackingStatus($order->tracking_number);
    }
}
```

---

## 5. Interface Utilisateur (UI)

### 5.1. Admin (Configuration)
Dans la page détail de commande (`admin.sourcing-orders.show`) :
- Liste déroulante **Transporteur** : (Peuplée dynamiquement par les Providers disponibles).
- Champ texte **Numéro de Suivi Réel**.

### 5.2. Client (Consultation)
Dans le tableau de bord client :
- Le client voit "Commande #10050".
- Il clique sur "Suivre mon colis".
- L'app appelle le `TrackingService` en arrière-plan (AJAX).
- Affiche une timeline unifiée (Design propre à votre app, masquant la complexité réelle).

---

## 6. Étapes d'Exécution

1.  **Backend Core :** Créer l'interface `TrackingProviderInterface` et le DTO.
2.  **Providers :** Implémenter 1 ou 2 providers exemples (ex: DummyProvider pour dev + un vrai si dispo).
3.  **Service :** Créer le `TrackingService` et la Factory.
4.  **Frontend Admin :** Mettre à jour le formulaire d'expédition pour choisir le Provider.
5.  **Frontend Client :** Créer la vue de timeline unifiée.
