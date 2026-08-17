## ADDED Requirements

### Requirement: Quality photo zoom modal on edit page
La page d'édition des quotations SHALL afficher un modal de zoom photo lorsqu'un utilisateur clique sur une image de qualité (low, medium, good).

#### Scenario: Click on quality photo opens zoom modal
- **WHEN** l'utilisateur clique sur une image de qualité dans la section "Real Product Quality Image" sur la page d'édition
- **THEN** un modal s'affiche en plein écran avec l'image en haute résolution, avec un fond noir semi-transparent et un bouton de fermeture

#### Scenario: Close zoom modal via close button
- **WHEN** le modal de zoom est ouvert et l'utilisateur clique sur le bouton X
- **THEN** le modal se ferme et l'utilisateur retourne à la page d'édition

#### Scenario: Close zoom modal via escape key
- **WHEN** le modal de zoom est ouvert et l'utilisateur appuie sur la touche Escape
- **THEN** le modal se ferme

#### Scenario: Close zoom modal via background click
- **WHEN** le modal de zoom est ouvert et l'utilisateur clique sur le fond noir (en dehors de l'image)
- **THEN** le modal se ferme
