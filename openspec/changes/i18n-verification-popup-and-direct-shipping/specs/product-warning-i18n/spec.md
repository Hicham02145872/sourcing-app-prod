## ADDED Requirements

### Requirement: Product warning popup is fully translatable

The system SHALL display the product-warning (fake product verification) popup text in the user's selected locale.

#### Scenario: Popup renders English text for English locale

- **WHEN** a user with English locale opens the sourcing request creation page
- **THEN** the product-warning popup SHALL display all text in English

#### Scenario: Popup renders French text for French locale

- **WHEN** a user with French locale opens the sourcing request creation page
- **THEN** the product-warning popup SHALL display all text in French

#### Scenario: Popup renders Arabic text for Arabic locale

- **WHEN** a user with Arabic locale opens the sourcing request creation page
- **THEN** the product-warning popup SHALL display all text in Arabic

### Requirement: Translation keys use English as source language

The `__()` helper calls in `product-warning-popup.blade.php` SHALL use English phrases as translation keys, not French phrases.

#### Scenario: All strings use English keys

- **WHEN** inspecting `product-warning-popup.blade.php`
- **THEN** every `__('...')` call SHALL contain an English string as the key argument

### Requirement: Translation entries exist in all locale files

Each English key used in the popup SHALL have corresponding translations in `lang/fr.json`, `lang/en.json`, and `lang/ar.json`.

#### Scenario: French translations exist

- **WHEN** inspecting `lang/fr.json`
- **THEN** all popup keys SHALL have French translation values

#### Scenario: Arabic translations exist

- **WHEN** inspecting `lang/ar.json`
- **THEN** all popup keys SHALL have Arabic translation values

#### Scenario: English translations exist (identity)

- **WHEN** inspecting `lang/en.json`
- **THEN** all popup keys SHALL have English translation values (identical to key)
