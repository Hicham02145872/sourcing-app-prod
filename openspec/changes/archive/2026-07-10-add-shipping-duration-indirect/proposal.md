## Why

L'itinéraire de transport indirect (Chine → Dubaï → pays destination) utilise actuellement un seul champ `air_indirect_arrival_time` pour représenter la durée totale du trajet. Il est impossible de communiquer séparément la durée Chine→Dubaï et la durée Dubaï→destination, ce qui est nécessaire pour informer correctement les clients de chaque étape du transport.

## What Changes

- Ajout de deux colonnes dans la table `shipping_fees` : `china_to_dubai_duration` et `dubai_to_destination_duration` (varchar, ex: "5-7")
- L'admin peut maintenant saisir séparément la durée Chine→Dubaï et la durée Dubaï→destination dans l'onglet indirect
- Le champ `air_indirect_arrival_time` existant reste pour backward compatibility (peut être calculé ou rempli manuellement)
- L'affichage client et le popup de sourcing retournent les deux durées séparément pour l'itinéraire indirect

## Capabilities

### New Capabilities
- `indirect-shipping-duration`: Permet à l'admin de configurer et d'afficher les durées séparées pour chaque segment de l'itinéraire indirect (Chine→Dubaï et Dubaï→destination)

### Modified Capabilities
- `shipping-fees-dubai-source`: Les specs existantes seront mises à jour pour inclure les durées segmentées dans l'onglet indirect

## Impact

- **Database**: Migration pour ajouter 2 colonnes à `shipping_fees`
- **Models**: `ShippingFee` model mis à jour avec les nouveaux champs
- **Admin Livewire**: `ShippingFeeEdit` et la vue `shipping-fee-edit.blade.php` modifiés pour afficher 2 champs de durée dans l'onglet indirect
- **Client**: `ShippingFeesList`, `create.blade.php` (popup) et `ShippingFeeController` mis à jour pour retourner/afficher les durées segmentées
- **API**: `getRatesForPopup()` retourne `china_to_dubai_duration` et `dubai_to_destination_duration` dans l'objet `indirect`
