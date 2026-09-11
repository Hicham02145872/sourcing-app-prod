## Why

Les admins peuvent réclamer et passer en « In-Review » un nombre illimité de dossiers simultanément, ce qui fait s'accumuler les demandes non traitées et dégrade le SLA client. Le owner impose un plafond de 5 dossiers In-Review par admin.

## What Changes

- Ajout d'une règle workflow optionnelle (feature flag `fsb.workflow_rules_enabled`) : un admin ne peut pas avoir plus de 5 dossiers en statut `in_review` en même temps.
- Rejet côté serveur à 422 avec code d'erreur `WORKFLOW_LIMIT_REACHED` et message (arabe/français) expliquant la limite lors d'une tentative de passage à `in_review` au-delà du plafond.
- Garde-fou au niveau du composant Livewire admin (claim / changement de statut) avec message inline, en plus de la règle serveur.
- Les super admins sont exemptés de la limite.
- Aucune modification des transitions de statut existantes ; la règle est additive et désactivable.

## Capabilities

### New Capabilities
- `admin-in-review-limit`: limite de 5 dossiers In-Review simultanés par admin, validation serveur + UI, exemption super admin, contrôlée par feature flag.

### Modified Capabilities
<!-- Aucune spec existante concernée (status-toggle-filter ne change pas de comportement) -->

## Impact

- `app/Livewire/Admin/SourcingRequestWorkflow.php` : point de transistion vers `in_review` (claim + changement de statut).
- Nouveau service `App\Services\WorkflowLimitService` (ou similaire) ; nouvelle config `config/fsb.php` (flag + limite).
- `app/Models/SourcingRequest.php` : la validation est greffée sans toucher `transitionTo()` existant (validation additive appelée avant transition dans les flux admin).
- Vues Livewire admin (message d'erreur) ; tests Feature dédiés.
- Aucune migration, aucun changement de schéma.