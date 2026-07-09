## Context

Le projet utilise Laravel 12 + Livewire 3 + Alpine.js. Le formulaire de création de demande d'approvisionnement (`resources/views/client/sourcing-requests/create.blade.php`) contient deux popups conditionnels (protégés par `@featureVisible('shipping_fees_popup')`) :

1. **Choose Shipping Routing** (premier popup) — fonctionnel, affiche les options de routage direct (Chine) et indirect (Dubai) avec les tarifs correspondants
2. **Shipping Fees Summary** (deuxième popup) — UI existante mais jamais déclenchée (`showFeeModal` toujours `false`, `feeDestinations` toujours `[]`)

Actuellement, `confirmRoutingPopup()` soumet directement le formulaire après la sélection du routage, contournant complètement le popup récapitulatif.

## Goals / Non-Goals

**Goals:**
- Activer l'affichage du popup "Shipping Fees Summary" dans le flux de création
- Récupérer et afficher les frais d'expédition pour chaque pays de destination sélectionné
- Permettre au client de voir le détail des frais par pays avant de confirmer la soumission
- Soumettre le formulaire uniquement après confirmation dans le popup récapitulatif

**Non-Goals:**
- Ne pas modifier le modèle de données ni ajouter de migration
- Ne pas modifier le premier popup "Choose Shipping Routing" (déjà fonctionnel)
- Ne pas modifier le comportement après soumission (le submit reste identique)
- Ne pas modifier l'API `getShippingFee()` (déjà fonctionnelle, sera réutilisée)

## Decisions

### D1. Flux modifié : routage → récapitulatif → soumission

**Décision :** Modifier `confirmRoutingPopup()` pour :
1. Fermer le popup de routage (`showRoutingPopup = false`)
2. Définir `sourcing_location` comme avant
3. Ouvrir le popup récapitulatif (`showFeeModal = true`) avec `loadingFees = true`
4. Pour chaque bloc de destination, appeler `getFeeUrl(countryId, transport, sourcing)` 
5. Remplir `feeDestinations` avec les résultats
6. Attendre que l'utilisateur clique sur "Confirm & Submit" dans le récapitulatif pour soumettre via `confirmSubmit()`

**Justification :** C'est le flux attendu par l'UI existante. Le template du popup récapitulatif est déjà codé et ne nécessite que d'être alimenté avec des données.

### D2. Récupération des frais par destination via l'API existante

**Décision :** Utiliser la méthode `getFeeUrl()` existante dans le composant Alpine.js pour construire l'URL de l'API `getShippingFee()` pour chaque destination. Cette API retourne les items de frais avec `price_per_kg`, `currency`, `unit`, `estimation_days` pour un pays, transport et sourcing donnés.

**Alternative envisagée :** Appeler `getRatesForPopup()` qui retourne les deux routes (direct/indirect) — rejeté car pour le récapitulatif on a déjà choisi une route spécifique (china/dubai), donc `getShippingFee()` est plus approprié.

**Justification :** Réutilisation de l'existant, pas de nouvelle API nécessaire. L'endpoint `getShippingFee()` prend déjà en compte la visibilité des transports et les mappings de transport_type legacy.

### D3. Gestion des erreurs et des cas sans frais

**Décision :** Si un pays n'a pas de frais configurés (`fee === null`), afficher un message "No shipping rates available for this destination and transport method." (déjà présent dans le template via `dest.items.length === 0`). Si toutes les destinations échouent, permettre quand même la soumission.

**Justification :** L'UI gère déjà le cas vide. Il ne faut pas bloquer la soumission si les frais ne sont pas disponibles pour certains pays.

## Risks / Trade-offs

| Risque | Mitigation |
|---|---|
| Appels API multiples (un par destination) | Les appels sont en parallèle via `Promise.all()` pour éviter de bloquer l'UI |
| Le select Tom Select de `sourcing_location` doit être synchronisé | Déjà fait dans `confirmRoutingPopup()` actuel — à conserver |
| La quantité doit être transmise au popup récapitulatif | Lire la quantité depuis le champ `input` de chaque destination au moment de l'ouverture du popup |
