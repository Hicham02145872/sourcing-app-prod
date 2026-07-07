## ADDED Requirements

### Requirement: Selected quality card shows prominent visual indicator

The system SHALL display a clearly visible selection state on the active quality option card in the product quality options selector.

The selected state MUST include:
- A filled orange (`bg-[#EF7722]`) background replacing the default white background
- White text color for the quality label and unit price
- A checkmark icon badge positioned at the top-right corner of the card
- The unselected cards MUST remain with the current white background, gray border, and default text colors

The current border-only change (`border-[#EF7722] ring-2 ring-[#EF7722]/20`) SHALL be replaced by the filled background approach.

#### Scenario: User selects a quality option

- **WHEN** the user clicks on an unselected quality card
- **THEN** that card gains a filled orange background, white text, and a checkmark badge
- **AND** all other cards return to their default unselected appearance

#### Scenario: Page loads with a default quality selected

- **WHEN** the sourcing request detail page loads
- **THEN** the default quality card (`$firstQuality`) SHALL display the selected appearance (filled background, white text, checkmark)

#### Scenario: Visual state is consistent across themes

- **WHEN** the page is viewed in dark mode
- **THEN** the selected quality card SHALL use appropriate dark-mode colors (e.g., `dark:bg-[#EF7722]` for background) while remaining visually distinct from unselected cards
