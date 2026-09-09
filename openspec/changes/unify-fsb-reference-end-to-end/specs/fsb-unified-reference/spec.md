## ADDED Requirements

### Requirement: Une demande reçoit un numéro FSB unique à sa création
À la création d'une demande (`SourcingRequest`), le système DOIT générer un numéro de référence unique au format `FSB` suivi de 6 chiffres (ex. `FSB000005`), issu de la même séquence auto-incrémentée de 5 utilisée auparavant pour les `SB****`. La référence publique de la demande (`reference_id`) DOIT afficher ce numéro FSB partout (dashboard client, liste des demandes, page détail, bulk payment, back-office admin).

#### Scenario: Création d'une nouvelle demande
- **GIVEN** une nouvelle demande en cours de création
- **WHEN** la demande est enregistrée
- **THEN** sa valeur `shared_id` est au format `FSB` + 6 chiffres (ex. `FSB000005`)
- **AND** sa référence affichée (`reference_id`) est ce même numéro `FSB`

#### Scenario: Séquence par pas de 5
- **GIVEN** le dernier numéro FSB généré a la valeur numérique `N`
- **WHEN** la demande suivante est créée
- **THEN** son numéro FSB a pour valeur numérique `N + 5` (ex. `FSB000005`, `FSB000010`, `FSB000015`)

### Requirement: La commande porte le même numéro FSB que sa demande
Lorsqu'une commande (`SourcingOrder`) est créée depuis une demande, le système DOIT lui transmettre le numéro FSB de la demande (`shared_id` hérité). La référence publique de la commande (`reference_id`) DOIT être égale à son numéro de suivi (`fsb_tracking_number`) et au numéro FSB de la demande. Pour les commandes existantes dont le `shared_id` n'est pas au format FSB (légacy `SB****` ou absent), le comportement actuel (FSB dérivé de l'id) DOIT être conservé.

#### Scenario: Création d'une commande depuis une demande
- **GIVEN** une demande avec `shared_id = FSB000005`
- **WHEN** une commande est créée depuis cette demande
- **THEN** la commande reçoit `shared_id = FSB000005`
- **AND** `reference_id` et `fsb_tracking_number` retournent tous deux `FSB000005`

#### Scenario: Commande légacy sans FSB
- **GIVEN** une commande existante avec `shared_id = SB00015` ou sans `shared_id`, et `id = 43`
- **WHEN** `reference_id` ou `fsb_tracking_number` est demandé
- **THEN** le numéro retourné reste dérivé de l'id (ex. `FSB000043` selon l'encodage actuel)

### Requirement: Le numéro FSB de la demande sert de numéro de tracking de la commande
Le numéro FSB porté par la demande et sa commande DOIT être résolu par la page de suivi (client) et par le moteur de tracking (`UnifiedTrackingService`, `VirtualTrackingStatusService`). Saisir le numéro FSB du cycle de vie (identique sur la demande et la commande) DOIT retourner le statut de la commande, virtuel (FSB) ou réel (numéro transporteur assigné).

#### Scenario: Suivi d'une commande avec le FSB hérité
- **GIVEN** une commande avec `shared_id = FSB000005`, payée et sans numéro transporteur réel
- **WHEN** le client saisit `FSB000005` sur la page de suivi
- **THEN** le système résout la commande par `shared_id`
- **AND** affiche son statut virtuel FSB (ex. `shipment_preparing` ou `in_transit_china`)

#### Scenario: Suivi d'une commande avec numéro transporteur réel
- **GIVEN** une commande avec `shared_id = FSB000005` et un `tracking_number` transporteur réel renseigné
- **WHEN** le client saisit `FSB000005` sur la page de suivi
- **THEN** le système retourne le statut du transporteur réel correspondant

### Requirement: Les demandes de remboursement affichent le numéro FSB
Les pages de remboursement (création, liste, détail — côté client et admin) DOIVENT afficher le numéro FSB de la commande concernée (`reference_id`) au lieu d'un identifiant interne brut (`display_id`). Le numéro FSB affiché DOIT être identique à celui de la demande liée et servir de référence dans le suivi du remboursement.

#### Scenario: Affichage de la référence d'un remboursement
- **GIVEN** un remboursement lié à une commande avec `shared_id = FSB000005`
- **WHEN** la page de remboursement affiche la référence de la commande
- **THEN** la valeur affichée est `FSB000005`

### Requirement: Les enregistrements SB existants restent fonctionnels
Les demandes et commandes existantes portant un `shared_id` au format `SB****` (légacy) DOIVENT continuer de fonctionner sans modification : leur référence affichée reste stable, la recherche admin par `SB****` continue de les retrouver, et le suivi existant continue de fonctionner. Le nouveau format FSB ne s'applique qu'aux enregistrements créés après cette évolution.

#### Scenario: Recherche admin d'un enregistrement SB légacy
- **GIVEN** une commande existante avec `shared_id = SB00015`
- **WHEN** l'admin recherche `SB00015` dans les listes de commandes ou demandes
- **THEN** l'enregistrement correspondant est trouvé et retourné

#### Scenario: Recherche admin d'un enregistrement FSB
- **GIVEN** une commande avec `shared_id = FSB000005`
- **WHEN** l'admin recherche `FSB000005` dans les listes de commandes ou demandes
- **THEN** l'enregistrement correspondant est trouvé et retourné