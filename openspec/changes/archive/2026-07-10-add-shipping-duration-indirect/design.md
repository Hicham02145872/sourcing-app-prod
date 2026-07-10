## Context

L'itinéraire indirect de shipping (Chine → Dubaï → pays destination) utilise actuellement un seul champ `air_indirect_arrival_time` dans la table `shipping_fees` pour représenter la durée totale du trajet. Les prix sont déjà splités en deux colonnes (`price_per_kg_china_to_dubai` et `price_per_kg_dubai_to_africa`) dans `shipping_fee_items`, mais les durées ne le sont pas.

Il est nécessaire de pouvoir afficher séparément la durée Chine→Dubaï et la durée Dubaï→destination pour informer les clients de chaque étape.

## Goals / Non-Goals

**Goals:**
- Permettre à l'admin de saisir deux durées séparées pour l'itinéraire indirect
- Afficher ces durées côté client (popup et liste)
- Maintenir la backward compatibility avec `air_indirect_arrival_time`

**Non-Goals:**
- Modifier la logique de calcul des prix (déjà géré par `price_per_kg_china_to_dubai` + `price_per_kg_dubai_to_africa`)
- Ajouter des durées segmentées pour les transport types directs (air_direct, sea)

## Decisions

### 1. Deux nouvelles colonnes dans `shipping_fees`

**Décision**: Ajouter `china_to_dubai_duration` (varchar) et `dubai_to_destination_duration` (varchar) à la table `shipping_fees`.

**Pourquoi**: Les durées sont des strings comme "5-7" ou "3-5 jours", pas des entiers. Le type varchar est cohérent avec `air_indirect_arrival_time` existant.

**Alternatives considérées**:
- Utiliser des colonnes integer avec conversion → Rejeté car les durées contiennent des plages ("5-7")
- Stocker en JSON dans une seule colonne → Rejeté car moins queryable et moins intuitif pour l'admin

### 2. `air_indirect_arrival_time` reste comme fallback

**Décision**: Le champ `air_indirect_arrival_time` est conservé. Si les nouvelles durées segmentées ne sont pas renseignées, le système affiche `air_indirect_arrival_time` comme fallback.

**Pourquoi**: Backward compatibility avec les données existantes et les intégrations tierces.

### 3. Admin edit: deux champs dans l'onglet indirect

**Décision**: Dans `ShippingFeeEdit`, ajouter deux propriétés publiques `china_to_dubai_duration` et `dubai_to_destination_duration`. La vue blade affiche deux inputs dans l'onglet air_indirect.

**Pourquoi**: Cohérent avec le pattern existant (un arrival_time par onglet, mais ici on en a deux pour l'indirect).

### 4. API popup: champs supplémentaires dans l'objet indirect

**Décision**: `getRatesForPopup()` retourne `china_to_dubai_duration` et `dubai_to_destination_duration` dans l'objet `indirect`. Le popup affiche ces durées à côté de chaque segment de route.

## Risks / Trade-offs

- **Risque**: Données existantes avec `air_indirect_arrival_time` mais sans les nouvelles colonnes → **Mitigation**: Fallback automatique sur `air_indirect_arrival_time` si les nouvelles colonnes sont NULL
- **Trade-off**: Deux champs supplémentaires dans l'admin → Accepté car nécessaire pour la granularité demandée

## Migration Plan

1. Créer la migration pour ajouter `china_to_dubai_duration` et `dubai_to_destination_duration` à `shipping_fees`
2. Mettre à jour le model `ShippingFee` avec les `$fillable` et `$casts`
3. Modifier `ShippingFeeEdit` pour gérer les deux nouvelles propriétés
4. Modifier la vue `shipping-fee-edit.blade.php` pour afficher les deux inputs dans l'onglet indirect
5. Modifier `ShippingFeeController::getRatesForPopup()` pour retourner les durées segmentées
6. Modifier les vues client pour afficher les durées segmentées
