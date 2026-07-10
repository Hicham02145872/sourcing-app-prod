## ADDED Requirements

### Requirement: Admin can set separate durations for each segment of indirect route
The admin shipping fee edit page SHALL display two separate duration input fields in the indirect (air_indirect) tab: one for the China→Dubai segment and one for the Dubai→destination segment.

#### Scenario: Admin views indirect tab with two duration fields
- **WHEN** an admin opens the shipping fee edit page for a country where `is_direct` is false
- **AND** the admin selects the indirect (air_indirect) transport tab
- **THEN** the page SHALL display a "Durée Chine → Dubaï" input field and a "Durée Dubaï → Destination" input field
- **AND** each field SHALL accept a varchar value (e.g., "5-7", "3-5")

#### Scenario: Admin saves separate durations
- **WHEN** an admin enters "5-7" in the China→Dubai duration field and "3-5" in the Dubai→destination duration field
- **AND** clicks save
- **THEN** `china_to_dubai_duration` SHALL be stored as "5-7"
- **AND** `dubai_to_destination_duration` SHALL be stored as "3-5"
- **WHEN** an admin leaves one field empty
- **THEN** that field SHALL be stored as NULL

### Requirement: Client receives separate durations for indirect route
The `getRatesForPopup()` API SHALL return `china_to_dubai_duration` and `dubai_to_destination_duration` in the indirect routing object.

#### Scenario: Popup displays separate durations for indirect route
- **WHEN** a client requests rates for a country with indirect shipping
- **THEN** the `indirect` object SHALL contain `china_to_dubai_duration` and `dubai_to_destination_duration` fields
- **WHEN** the values are set
- **THEN** the popup SHALL display each duration next to its corresponding route segment
- **WHEN** the values are NULL
- **THEN** the popup SHALL display the legacy `arrival_time` as fallback

### Requirement: Client shipping fees list shows segmented durations
The client shipping fees list SHALL display the two segment durations for indirect shipping items.

#### Scenario: Client views indirect tab with segmented durations
- **WHEN** a client views the indirect shipping tab for a country
- **AND** `china_to_dubai_duration` and `dubai_to_destination_duration` are set
- **THEN** the list SHALL display "Chine → Dubaï: X jours" and "Dubaï → Destination: Y jours" separately
- **WHEN** the segmented durations are NULL
- **THEN** the list SHALL fall back to displaying the total `air_indirect_arrival_time`
