## MODIFIED Requirements

### Requirement: Admin shipping fee edit shows Direct (China) and Indirect (Dubai) prices
The admin shipping fee edit page SHALL display two price columns per item row: "Direct (China)" and "Indirect (Dubai)". For the indirect tab, the page SHALL also display two separate duration fields for each route segment (Chine→Dubaï and Dubaï→Destination).

#### Scenario: Admin views edit page for a country with shipping fees
- **WHEN** an admin opens the shipping fee edit page for a country
- **THEN** each item row in every transport tab (air/sea/train) SHALL show a "Direct (China)" price column and an "Indirect (Dubai)" price column
- **WHEN** only one price is set
- **THEN** the empty column SHALL display an empty input field

#### Scenario: Admin views indirect tab with segmented durations
- **WHEN** an admin selects the indirect (air_indirect) transport tab
- **THEN** the tab SHALL display "Durée Chine → Dubaï" and "Durée Dubaï → Destination" input fields above the items table
- **AND** these fields SHALL be persisted separately from the total `air_indirect_arrival_time`
