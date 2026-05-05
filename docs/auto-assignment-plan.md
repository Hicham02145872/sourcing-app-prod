# Plan d'Implémentation : Système d'Auto-Assignation (Admin Claiming System)

Ce document détaille la logique pour le mécanisme de "claiming" automatique des dossiers par les administrateurs.

## 1. Objectifs
- Éviter que deux admins travaillent sur le même dossier.
- Assigner automatiquement un dossier à un admin dès qu'il l'ouvre.
- Restreindre la vue des dossiers pour ne montrer que ceux libres ou assignés à l'admin.

## 2. Structure de Données (Rappel)
Modification de la table `sourcing_requests` (déjà intégrée dans la migration globale, mais rappel ici pour contexte).

```php
$table->foreignId('assigned_to_admin_id')->nullable()->constrained('users');
$table->timestamp('assigned_at')->nullable();
```

---

## 3. Logique du Contrôleur (`AdminSourcingRequestController`)

### 3.1. Méthode `show()` (Auto-Claim)
C'est ici que la magie opère. Dès l'ouverture de la page de détail :

```php
public function show(SourcingRequest $sourcingRequest): View
{
    $currentUser = auth()->user();

    // 1. Si le dossier n'est assigné à personne -> On l'assigne à l'admin courant
    if (is_null($sourcingRequest->assigned_to_admin_id)) {
        $sourcingRequest->update([
            'assigned_to_admin_id' => $currentUser->id,
            'assigned_at' => now(),
        ]);
        
        session()->flash('success', 'Dossier automatiquement assigné à vous.');
    }
    
    // 2. Si assigné à quelqu'un d'autre -> Vérification de sécurité
    elseif ($sourcingRequest->assigned_to_admin_id !== $currentUser->id) {
        // Le Super Admin peut voir n'importe quel dossier (mode supervision)
        if ($currentUser->isSuperAdmin()) {
            session()->flash('warning', 'Attention: Ce dossier est assigné à un autre administrateur.');
        } else {
            // Un admin normal est bloqué
            abort(403, 'Ce dossier est verrouillé par un autre administrateur.');
        }
    }

    $sourcingRequest->load('category', 'user', 'destinations.country', 'destinations.service');
    return view('admin.sourcing-requests.show', compact('sourcingRequest'));
}
```

### 3.2. Méthode `index()` (Filtrage de Liste)
L'administrateur ne doit pas être pollué par les dossiers des autres.

```php
public function index(Request $request): View
{
    $query = SourcingRequest::with('category', 'user', 'destinations.country', 'destinations.service');

    // ... filtres existants ...

    // FILTRE DE SÉCURITÉ
    // Si pas Super Admin, on ne montre que :
    // - Mes dossiers (assigned_to_me)
    // - Les dossiers libres (assigned_to_null)
    if (!auth()->user()->isSuperAdmin()) {
        $query->where(function($q) {
            $q->where('assigned_to_admin_id', auth()->id())
              ->orWhereNull('assigned_to_admin_id');
        });
    }

    $sourcingRequests = $query->latest()->paginate(10);

    return view('admin.sourcing-requests.index', compact('sourcingRequests'));
}
```

---

## 4. Interface Utilisateur (Vues)

### 4.1. Badge d'Assignation (Index & Show)
Ajouter un indicateur visuel pour savoir si un dossier est "Libre" ou "Assigné à Moi".

**Dans la liste (`index.blade.php`) :**
```blade
@if($request->assigned_to_admin_id == auth()->id())
    <span class="badge badge-success">Mon Dossier</span>
@elseif(is_null($request->assigned_to_admin_id))
    <span class="badge badge-info">Libre</span>
@else
    <span class="badge badge-warning">Assigné à {{ $request->admin->name }}</span>
@endif
```

### 4.2. Bouton "Libérer le dossier" (Optionnel)
Ajouter un bouton dans la vue `show` pour permettre à l'admin de se "désassigner" le dossier s'il ne peut pas le traiter.

Route : `POST /admin/sourcing-requests/{id}/release`
Logique : `update(['assigned_to_admin_id' => null])`


### 4.3. Assignation Manuelle (Super Admin Only)
Le Super Admin aura la possibilité d'assigner manuellement un dossier à un admin spécifique.

**Route :** `POST /admin/sourcing-requests/{id}/assign`

**Contrôleur (`assign`) :**
```php
public function assign(Request $request, SourcingRequest $sourcingRequest)
{
    // Sécurité : Seul le Super Admin peut faire ça
    if (!auth()->user()->isSuperAdmin()) {
        abort(403);
    }

    $validated = $request->validate([
        'admin_id' => 'required|exists:users,id'
    ]);

    $sourcingRequest->update([
        'assigned_to_admin_id' => $validated['admin_id'],
        'assigned_at' => now()
    ]);

    return back()->with('success', 'Dossier assigné avec succès.');
}
```

**Vue (`index.blade.php` & `show.blade.php`) :**
Ajout d'un dropdown "Assigner à..." visible uniquement pour le Super Admin.
```blade
@if(auth()->user()->isSuperAdmin())
    <form action="{{ route('admin.sourcing-requests.assign', $request->id) }}" method="POST">
        @csrf
        <select name="admin_id" onchange="this.form.submit()">
            <option value="">Sélectionner un admin...</option>
            @foreach($admins as $admin)
                <option value="{{ $admin->id }}">{{ $admin->name }}</option>
            @endforeach
        </select>
    </form>
@endif
```


### 4.4. Désassignation (Super Admin Only)
Permettre au Super Admin de libérer un dossier (le remettre à "Libre").

**Route :** `POST /admin/sourcing-requests/{id}/unassign`

**Contrôleur (`unassign`) :**
```php
public function unassign(SourcingRequest $sourcingRequest)
{
    if (!auth()->user()->isSuperAdmin()) {
        abort(403);
    }

    $sourcingRequest->update([
        'assigned_to_admin_id' => null,
        'assigned_at' => null
    ]);

    return back()->with('success', 'Dossier libéré avec succès.');
}
```

---

## 5. Étapes d'Intégration
1. **Migration :** S'assurer que les champs sont créés (voir plan module financier).
2. **Controller :** Appliquer la logique `show` et `index`.
3. **Views :** Ajouter les indicateurs visuels.
