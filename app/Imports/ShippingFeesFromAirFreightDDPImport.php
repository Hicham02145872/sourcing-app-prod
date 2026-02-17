<?php

namespace App\Imports;

use App\Models\Country;
use App\Models\ShippingFee;
use App\Models\ShippingFeeItem;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

/**
 * Import Excel "Air Freight DDP Table" vers les frais d'expédition de l'app.
 *
 * Colonnes attendues (noms flexibles, slugifiés) :
 * - country / pays / destination / country_name / code → pays
 * - item_style / type / category / item_type / style → type de marchandise
 * - price_per_kg / price / prix / rate / prix_au_kg → prix au kg
 * - estimation_days / days / delai / délai / estimation → jours (optionnel)
 */
class ShippingFeesFromAirFreightDDPImport implements ToCollection, WithHeadingRow
{
    /** @var array<int, string> */
    protected array $errors = [];

    /** @var int */
    protected int $imported = 0;

    /** @var int */
    protected int $skipped = 0;

    public function collection(Collection $rows): void
    {
        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2; // 1-based + header
            $row = $row->toArray();

            $countryValue = $this->getCell($row, ['country', 'pays', 'destination', 'country_name', 'code', 'country_code']);
            $itemStyle = $this->getCell($row, ['item_style', 'type', 'category', 'item_type', 'style', 'product_type']);
            $pricePerKg = $this->getCell($row, ['price_per_kg', 'price', 'prix', 'rate', 'prix_au_kg', 'price_per_kg_usd']);
            $estimationDays = $this->getCell($row, ['estimation_days', 'days', 'delai', 'estimation', 'delivery_days']);

            if (empty($countryValue) && empty($itemStyle) && (empty($pricePerKg) || ! is_numeric($pricePerKg))) {
                $this->skipped++;

                continue;
            }

            if (empty($countryValue)) {
                $this->errors[] = "Ligne {$rowNumber}: pays manquant.";
                $this->skipped++;

                continue;
            }

            if (empty($itemStyle)) {
                $this->errors[] = "Ligne {$rowNumber}: type de marchandise (item_style) manquant.";
                $this->skipped++;

                continue;
            }

            $price = $this->parsePrice($pricePerKg);
            if ($price === null) {
                $this->errors[] = "Ligne {$rowNumber}: prix au kg invalide (« {$pricePerKg} »).";
                $this->skipped++;

                continue;
            }

            $country = $this->resolveCountry($countryValue);
            if (! $country) {
                $this->errors[] = "Ligne {$rowNumber}: pays introuvable (« {$countryValue} »). Créer le pays dans l'app ou vérifier le nom/code.";
                $this->skipped++;

                continue;
            }

            $shippingFee = $country->shippingFee ?? ShippingFee::create([
                'country_id' => $country->id,
                'currency' => 'USD',
                'unit' => 'kg',
            ]);

            ShippingFeeItem::updateOrCreate(
                [
                    'shipping_fee_id' => $shippingFee->id,
                    'transport_type' => 'air',
                    'item_style' => trim($itemStyle),
                ],
                [
                    'price_per_kg' => $price,
                    'estimation_days' => $estimationDays ? trim((string) $estimationDays) : null,
                    'estimation_unit' => 'days',
                ]
            );
            $this->imported++;
        }
    }

    /**
     * @param  array<string, mixed>  $row
     * @param  array<int, string>  $keys
     */
    protected function getCell(array $row, array $keys): mixed
    {
        foreach ($keys as $key) {
            if (isset($row[$key])) {
                $v = $row[$key];
                if ($v !== null && $v !== '') {
                    return $v;
                }
            }
        }

        return null;
    }

    protected function parsePrice(mixed $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }
        if (is_numeric($value)) {
            return round((float) $value, 2);
        }
        $cleaned = preg_replace('/[^\d.,\-]/', '', (string) $value);
        $cleaned = str_replace(',', '.', $cleaned);
        if ($cleaned !== '' && is_numeric($cleaned)) {
            return round((float) $cleaned, 2);
        }

        return null;
    }

    protected function resolveCountry(string $value): ?Country
    {
        $value = trim($value);
        if ($value === '') {
            return null;
        }

        $country = Country::whereRaw('UPPER(code) = ?', [strtoupper($value)])
            ->orWhereRaw('LOWER(name) = ?', [strtolower($value)])
            ->first();

        return $country;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getImportedCount(): int
    {
        return $this->imported;
    }

    public function getSkippedCount(): int
    {
        return $this->skipped;
    }
}
