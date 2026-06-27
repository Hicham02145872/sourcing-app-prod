## Context

The admin shipping fees edit page (`ShippingFeeEdit` Livewire component) already supports currency selection per country using TomSelect with all 164 currencies from `config/currencies.php`. However:

- The **index table** (`ShippingFeesTable` / `shipping-fees-table.blade.php`) does not display which currency each country uses
- The **Excel import** (`ShippingFeesFromAirFreightDDPImport`) hardcodes `'currency' => 'USD'` when creating new records
- The **import form** has no currency selector

This creates inconsistency: an admin can set EUR for a country in the edit page, but the index table gives no visual indication of the currency, and the import always defaults to USD regardless.

## Goals / Non-Goals

**Goals:**
- Show currency code for each country in the shipping fees index table
- Add a currency selector to the Excel import form and pass it to the importer
- New import records use the selected currency; existing records keep their currency

**Non-Goals:**
- Not changing the currency selector in the edit page (164 currencies from config is fine)
- Not adding currency conversion or exchange rates
- Not modifying the client-facing shipping fees list (it already reads `$country->shippingFee->currency`)

## Decisions

**Decision 1: Currency column in index table**
- Add a `<th>` and `<td>` column for "Currency" after the Transportation Status column
- Display `$country->shippingFee->currency ?? 'USD'` — works even if no shipping fee record exists yet
- Use the same text styling as other cells (text-xs font-bold)

**Decision 2: Import form currency selector**
- Add a `<select>` to the import form blade (`admin.shipping-fees.import`) with options from `config('currencies')`
- Default to "USD"
- The import controller (`ShippingFeeController@import`) passes the selected currency to the import class

**Decision 3: Importer accepts currency parameter**
- Add a `$currency` property to `ShippingFeesFromAirFreightDDPImport` with a setter
- When creating new `ShippingFee` records, use `$this->currency` instead of hardcoded `'USD'`
- Only set currency on `create()`, not on update — existing records keep their currency

## Risks / Trade-offs

- [Low] Existing shipping fee records created via import with USD will keep USD — no migration needed, which is intentional
- [Low] The import form currency selector adds one more dropdown — negligible UX overhead
