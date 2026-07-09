## Context

Le popup de routage utilise `getRatesForPopup()` dans `ShippingFeeController.php` pour récupérer les tarifs directs (Chine) et indirects (via Dubai). Actuellement :

- Les items directs sont filtrés selon le transport : `air_direct`/`air` si transport=air, `sea` si transport=sea
- Les items indirects sont TOUJOURS chargés (`air_indirect`/`train`) sans vérification du transport

Le routage indirect (Chine → Dubai → pays destination) est exclusivement aérien. Il ne doit donc être proposé que lorsque le client sélectionne le transport aérien.

## Goals / Non-Goals

**Goals:**
- Cacher l'option "Indirect Shipping" quand le transport sélectionné est "Sea"
- Ne montrer l'option indirecte que quand le transport est "Air"

**Non-Goals:**
- Ne pas modifier le comportement des items directs
- Ne pas modifier l'UI du popup (le `x-show` existe déjà)
- Ne pas modifier le calcul des prix indirects

## Decisions

### D1. Condition sur le transport pour les items indirects

**Décision :** Ajouter `$transportType === 'air'` à la condition existante dans `getRatesForPopup()` :

```php
$indirectItems = collect();
if ($transportType === 'air' && ($fee->is_air_indirect_visible ?? true)) {
    $indirectItems = $fee->items->filter(
        fn ($i) => in_array(strtolower((string) $i->transport_type), ['air_indirect', 'train'])
    )->values();
}
```

**Justification :** Changement minimal, une ligne. Le frontend cache déjà l'option indirecte via `x-show="ratesData?.indirect?.items?.length > 0"` — si `indirect.items` est vide, l'option ne s'affiche pas et la grille passe en `md:grid-cols-1`.

### D2. Aucun changement frontend nécessaire

**Décision :** Ne pas modifier le template Blade/Alpine.js. La condition `x-show="ratesData?.indirect?.items?.length > 0"` à la ligne 425 et le conditionnel de classe `ratesData?.indirect?.items?.length > 0 ? 'md:grid-cols-2' : 'md:grid-cols-1'` à la ligne 347 gèrent déjà l'affichage correct.

## Risks / Trade-offs

| Risque | Mitigation |
|---|---|
| Un pays configuré uniquement en indirect (pas de transport direct) serait caché en mode Sea | Dans ce cas, le direct aura aussi des items vides, et le popup montrera "No direct rates indexed" sans option indirecte — acceptable car le transport maritime ne peut pas passer par Dubai |
