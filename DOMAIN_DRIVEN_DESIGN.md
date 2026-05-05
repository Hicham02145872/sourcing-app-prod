# Appliquer le Domain-Driven Design (DDD) à l'application de Sourcing

Ce document décrit comment les principes du Domain-Driven Design (DDD) peuvent être appliqués à cette application Laravel pour améliorer sa structure, sa maintenabilité et son alignement avec les besoins du métier.

## 1. Concepts Clés du DDD

- **Langage Ubiquitaire (Ubiquitous Language) :** Un langage commun partagé par les développeurs, les experts du domaine et les utilisateurs pour décrire le domaine métier.
- **Contextes Bornés (Bounded Contexts) :** Des frontières claires à l'intérieur desquelles un modèle de domaine spécifique et son langage ubiquitaire s'appliquent.
- **Agrégats (Aggregates) :** Un groupe d'objets de domaine (Entités et Objets de Valeur) qui sont traités comme une seule unité. Chaque agrégat a une racine (Aggregate Root) qui est le seul point d'entrée pour les modifications.
- **Entités (Entities) :** Objets qui ont une identité distincte qui perdure dans le temps (ex: un `Utilisateur`, une `Commande`).
- **Objets de Valeur (Value Objects) :** Objets qui décrivent des caractéristiques et n'ont pas d'identité propre. Ils sont définis par la valeur de leurs attributs (ex: une `Adresse`, un `Montant`).

---

## 2. Appliquer le DDD à votre Projet

En analysant votre code, nous pouvons identifier plusieurs concepts DDD déjà présents implicitement et proposer une structure plus explicite.

### Langage Ubiquitaire

Votre code utilise déjà un langage clair qui reflète le domaine :
- **SourcingRequest :** La demande initiale d'un client.
- **Quotation :** La proposition de devis par un administrateur.
- **SourcingOrder :** La commande finale après acceptation du devis.
- **Proof of Payment :** La preuve de paiement téléchargée par le client.
- **Client, Admin, Super Admin :** Les différents rôles utilisateurs.
- **Status :** (`pending`, `in_review`, `quoted`, `paid`, `shipped`, etc.) : Les états clés du cycle de vie.

Ce vocabulaire doit être utilisé de manière cohérente partout (code, base de données, UI, documentation).

### Contextes Bornés Potentiels

Votre application peut être divisée en plusieurs contextes bornés, chacun avec ses propres responsabilités :

1.  **Contexte `Sourcing` (Approvisionnement) :**
    - **Responsabilités :** Gérer le processus initial de demande et de devis.
    - **Modèles clés :** `SourcingRequest`, `Quotation`, `Category`, `SourcingRequestDestination`.
    - **Logique métier :** Création des demandes, validation, création des devis, acceptation/rejet des devis.

2.  **Contexte `Ordering` (Commandes) :**
    - **Responsabilités :** Gérer le cycle de vie d'une commande après l'acceptation d'un devis.
    - **Modèles clés :** `SourcingOrder`, `PaymentMethod`.
    - **Logique métier :** Gestion des paiements, suivi des statuts de livraison (`pending_payment`, `paid`, `in_transit_china`, etc.), gestion des preuves de paiement.

3.  **Contexte `Identity & Access` (Identité et Accès) :**
    - **Responsabilités :** Gérer les utilisateurs, les rôles et l'authentification.
    - **Modèles clés :** `User`.
    - **Logique métier :** Inscription, connexion, gestion des rôles (`client`, `admin`, `super_admin`).

4.  **Contexte `Administration` :**
    - **Responsabilités :** Gérer les données de support de l'application.
    - **Modèles clés :** `Country`, `Service`, `WelcomePageContent`, `SocialMediaLink`.

### Agrégats, Entités et Objets de Valeur

- **Entités (Entities) :**
    - `User`
    - `SourcingRequest` (Aggregate Root)
    - `Quotation`
    - `SourcingOrder` (Aggregate Root)
    - `Category`
    - `Country`

- **Agrégats (Aggregates) :**
    - **`SourcingRequest` Aggregate :**
        - **Racine :** `SourcingRequest`.
        - **Inclus :** `SourcingRequestDestination`. Les destinations n'ont de sens qu'à travers une `SourcingRequest`. Toute modification des destinations devrait passer par l'objet `SourcingRequest`.
    - **`SourcingOrder` Aggregate :**
        - **Racine :** `SourcingOrder`.
        - **Inclus :** Pourrait inclure des objets liés au paiement ou à la livraison qui dépendent directement de la commande.
    - **`User` Aggregate :**
        - **Racine :** `User`.
        - Peut-être que les `SocialMediaLink` pourraient être considérés comme partie de l'agrégat `User` s'ils sont spécifiques à un utilisateur.

- **Objets de Valeur (Value Objects) :**
    - `Address` : Au lieu d'avoir `address`, `latitude`, `longitude` comme champs primitifs dans `SourcingRequest`, vous pourriez créer un objet de valeur `Address`.
    - `Money` : Au lieu d'utiliser un type `decimal` pour `amount` et un `string` pour `currency` dans `Quotation`, vous pourriez créer un objet de valeur `Money` qui encapsule le montant et la devise, et contient la logique de formatage ou de calcul.

---

## 3. Proposition de Structure de Répertoires

Pour refléter ces contextes, vous pourriez réorganiser votre répertoire `app/` :

```
app/
├── Sourcing/
│   ├── Domain/
│   │   ├── Models/
│   │   │   ├── SourcingRequest.php  (Entity, Aggregate Root)
│   │   │   ├── Quotation.php        (Entity)
│   │   │   └── Category.php         (Entity)
│   │   ├── Events/
│   │   │   ├── SourcingRequestStatusChanged.php
│   │   │   └── QuotationAccepted.php
│   │   └── Services/
│   │       └── TimelineService.php
│   ├── Application/
│   │   └── Listeners/
│   │       └── SendSourcingRequestStatusChangeNotification.php
│   └── Infrastructure/
│       └── Http/
│           ├── Controllers/
│           │   ├── AdminSourcingRequestController.php
│           │   └── SourcingRequestController.php
│           └── Requests/
│
├── Ordering/
│   ├── Domain/
│   │   ├── Models/
│   │   │   ├── SourcingOrder.php    (Entity, Aggregate Root)
│   │   │   └── PaymentMethod.php    (Entity)
│   │   ├── Events/
│   │   │   └── SourcingOrderStatusChanged.php
│   │   └── ValueObjects/
│   │       └── Money.php
│   ├── Application/
│   │   └── ...
│   └── Infrastructure/
│       └── Http/
│           └── Controllers/
│               ├── SourcingOrderController.php
│
├── IdentityAccess/
│   ├── Domain/
│   │   └── Models/
│   │       └── User.php
│   └── ...
│
└── Shared/
    ├── Domain/
    └── Infrastructure/

```

- **Domain :** Contient la logique métier pure, les entités, les objets de valeur et les événements du domaine. Ce répertoire ne devrait avoir aucune dépendaison avec le framework (comme les `Request` ou `Controller` Laravel).
- **Application :** Orchestre la logique métier. Contient les `Listeners` d'événements, les `Services` applicatifs qui utilisent le domaine.
- **Infrastructure :** Contient tout ce qui est lié à la technologie : Contrôleurs HTTP, Commandes Console, migrations, etc. Dépend du domaine, mais pas l'inverse.
- **Shared :** Code partagé entre plusieurs contextes.

## 4. Prochaines Étapes Actionnables

1.  **Définir les Objets de Valeur :** Commencez par identifier et créer des Objets de Valeur comme `Address` et `Money` pour remplacer les types primitifs.
2.  **Isoler un Contexte :** Choisissez un contexte (par exemple `Ordering`) et commencez à réorganiser les fichiers dans la nouvelle structure de répertoires.
3.  **Rendre les Agrégats Explicites :** Renforcez les règles de vos racines d'agrégats. Par exemple, assurez-vous que toute création ou modification d'une `SourcingRequestDestination` se fait via une méthode sur le modèle `SourcingRequest`.
4.  **Découpler du Framework :** Dans vos modèles du domaine, supprimez les dépendances directes à Laravel (par exemple, n'utilisez pas `Illuminate\Http\Request` dans une méthode de modèle). La logique métier doit être indépendante du framework.

---

### 5. Détail des Concepts (avec Exemples de Code)

#### **Contexte Borné (Bounded Context) : Communication via Événements de Domaine**

Les Contextes Bornés communiquent de manière lâche via des événements de domaine. Cela permet un fort découplage, où un contexte n'a pas besoin de connaître les détails internes de l'autre, seulement les événements qu'il émet ou écoute.

**Exemple : Création d'une `SourcingOrder` après acceptation d'une `Quotation`**

1.  **Dans le Contexte `Sourcing` (lors de l'acceptation du devis) :**
    ```php
    // app/Sourcing/Domain/Models/Quotation.php (ou un Service d'Application)

    namespace App\Sourcing\Domain\Models;

    use Illuminate\Database\Eloquent\Model;
    use App\Sourcing\Domain\Events\QuotationAccepted; // Votre événement de domaine

    class Quotation extends Model
    {
        // ... propriétés et méthodes existantes ...

        public function accept(User $user)
        {
            // Valider que le devis peut être accepté (ex: statut 'sent', par le bon client)
            if ($this->status !== 'sent' || $this->sourcingRequest->user_id !== $user->id) {
                throw new \DomainException("Cannot accept this quotation.");
            }

            $this->status = 'accepted';
            $this->save();

            // Déclencher un événement de domaine
            event(new QuotationAccepted(
                $this->id,
                $this->sourcing_request_id,
                $this->sourcingRequest->user_id,
                $this->amount, // Le montant final du devis
                $this->currency
            ));

            return true;
        }
    }
    ```
    *Ceci implique la création de l'événement `App\Sourcing\Domain\Events\QuotationAccepted` :*
    ```php
    // app/Sourcing/Domain/Events/QuotationAccepted.php
    namespace App\Sourcing\Domain\Events;

    use Illuminate\Foundation\Events\Dispatchable;
    use Illuminate\Queue\SerializesModels;

    class QuotationAccepted
    {
        use Dispatchable, SerializesModels;

        public function __construct(
            public int $quotationId,
            public int $sourcingRequestId,
            public int $clientId,
            public float $amount,
            public string $currency
        ) {}
    }
    ```

2.  **Dans le Contexte `Ordering` (écoute de l'événement) :**
    Un `Listener` dans le contexte `Ordering` va réagir à cet événement pour créer la `SourcingOrder`.

    ```php
    // app/Ordering/Application/Listeners/CreateSourcingOrderFromAcceptedQuotation.php
    namespace App\Ordering\Application\Listeners;

    use App\Sourcing\Domain\Events\QuotationAccepted;
    use App\Ordering\Domain\Models\SourcingOrder;
    use App\Shared\Domain\ValueObjects\Money; // Si Money est déjà un VO

    class CreateSourcingOrderFromAcceptedQuotation
    {
        public function handle(QuotationAccepted $event)
        {
            // Vérifier si une commande n'existe pas déjà pour ce devis
            if (SourcingOrder::where('quotation_id', $event->quotationId)->exists()) {
                return; // Ou lever une exception si c'est une condition d'erreur
            }

            // Créer l'entité SourcingOrder dans le contexte Ordering
            // Les données proviennent de l'événement, pas d'une requête au contexte Sourcing
            SourcingOrder::create([
                'user_id' => $event->clientId,
                'quotation_id' => $event->quotationId,
                'total_amount' => $event->amount, // Utilisez Money VO si implémenté
                'status' => 'pending_payment', // Premier statut dans le contexte Ordering
                // ... autres champs nécessaires ...
            ]);
        }
    }
    ```
    *Il faudra enregistrer ce Listener dans `app/Providers/EventServiceProvider.php` :*
    ```php
    protected $listen = [
        // ...
        \App\Sourcing\Domain\Events\QuotationAccepted::class => [
            \App\Ordering\Application\Listeners\CreateSourcingOrderFromAcceptedQuotation::class,
        ],
    ];
    ```

#### **Agrégats : `SourcingRequest` et gestion de ses `SourcingRequestDestination`**

L'objectif de l'agrégat `SourcingRequest` est de garantir que toutes les `SourcingRequestDestination`s sont toujours dans un état cohérent avec la `SourcingRequest` parente. On manipule les `SourcingRequestDestination`s uniquement via la `SourcingRequest`.

```php
// app/Sourcing/Domain/Models/SourcingRequest.php
namespace App\Sourcing\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User; // Garder User pour la relation, mais idéalement, ce serait un ID de User
use App\Sourcing\Domain\ValueObjects\Address; // Si vous avez implémenté Address VO

class SourcingRequest extends Model
{
    use HasFactory;

    // ... STATUSES et $fillable existants ...

    // Cast pour l'objet de valeur Address (nécessite un cast personnalisé)
    // protected $casts = [
    //     'address' => AddressCast::class,
    // ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function destinations()
    {
        return $this->hasMany(SourcingRequestDestination::class);
    }

    /**
     * Ajoute une nouvelle destination à la demande de sourcing.
     * C'est la seule façon d'ajouter des destinations, protégeant ainsi l'invariant de l'agrégat.
     */
    public function addDestination(Country $country, int $quantity, ?string $notes = null): SourcingRequestDestination
    {
        if ($this->status !== 'pending' && $this->status !== 'in_review') {
            throw new \DomainException("Cannot add destinations to a sourcing request with status '{$this->status}'.");
        }
        if ($quantity <= 0) {
            throw new \InvalidArgumentException("Quantity must be positive.");
        }
        // Ajouter d'autres règles métier si nécessaire (ex: max 10 destinations)

        return $this->destinations()->create([
            'country_id' => $country->id,
            'quantity' => $quantity,
            'notes' => $notes,
        ]);
    }

    /**
     * Met à jour une destination existante de la demande de sourcing.
     */
    public function updateDestination(SourcingRequestDestination $destination, int $newQuantity): SourcingRequestDestination
    {
        if ($this->id !== $destination->sourcing_request_id) {
            throw new \DomainException("Destination does not belong to this sourcing request.");
        }
        if ($this->status !== 'pending' && $this->status !== 'in_review') {
            throw new \DomainException("Cannot update destinations for a sourcing request with status '{$this->status}'.");
        }
        if ($newQuantity <= 0) {
            throw new \InvalidArgumentException("Quantity must be positive.");
        }

        $destination->quantity = $newQuantity;
        $destination->save();

        return $destination;
    }

    /**
     * Supprime une destination de la demande de sourcing.
     */
    public function removeDestination(SourcingRequestDestination $destination): void
    {
        if ($this->id !== $destination->sourcing_request_id) {
            throw new \DomainException("Destination does not belong to this sourcing request.");
        }
        if ($this->status !== 'pending' && $this->status !== 'in_review') {
            throw new \DomainException("Cannot remove destinations from a sourcing request with status '{$this->status}'.");
        }

        $destination->delete();
    }

    // Les méthodes canTransitionTo et transitionTo sont déjà de bons exemples de logique métier dans l'agrégat.
}
```

**Utilisation dans un Contrôleur (Infrastructure) :**

```php
// app/Sourcing/Infrastructure/Http/Controllers/Client/SourcingRequestDestinationController.php

namespace App\Sourcing\Infrastructure\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Sourcing\Domain\Models\SourcingRequest;
use App\Sourcing\Domain\Models\SourcingRequestDestination;
use App\Models\Country; // Garder Country pour la relation
use Illuminate\Http\Request;

class SourcingRequestDestinationController extends Controller
{
    public function store(Request $request, SourcingRequest $sourcingRequest)
    {
        $request->validate([
            'country_id' => 'required|exists:countries,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:255',
        ]);

        try {
            $country = Country::findOrFail($request->input('country_id'));
            $sourcingRequest->addDestination($country, $request->input('quantity'), $request->input('notes'));

            return back()->with('success', 'Destination added successfully.');
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, SourcingRequest $sourcingRequest, SourcingRequestDestination $destination)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        try {
            $sourcingRequest->updateDestination($destination, $request->input('quantity'));
            return back()->with('success', 'Destination updated successfully.');
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(SourcingRequest $sourcingRequest, SourcingRequestDestination $destination)
    {
        try {
            $sourcingRequest->removeDestination($destination);
            return back()->with('success', 'Destination removed successfully.');
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
```

Ces exemples montrent comment le DDD pousse la logique métier dans le domaine, rendant les contrôleurs plus légers et les règles plus robustes.

J'espère que ces détails supplémentaires et ces exemples de code vous seront très utiles pour commencer à appliquer le DDD dans votre projet.