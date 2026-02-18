<?php

namespace App\Imports;

use App\Models\Country;
use App\Models\ShippingFee;
use App\Models\ShippingFeeItem;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

/**
 * Import Excel "Air Freight DDP Table" vers les frais d'expédition de l'app.
 *
 * - Détecte automatiquement la ligne d'en-têtes (recherche "item style" / "destination").
 * - Gère les cellules fusionnées (fill-down pour item_style et destination).
 * - Accepte plusieurs pays dans une cellule (sépare par virgule, une ligne par pays trouvé).
 * - Colonnes reconnues : item style, destination/country, charge weight (KG) / Price: $, arrive time.
 */
class ShippingFeesFromAirFreightDDPImport implements ToCollection
{
    protected array $errors = [];

    protected int $imported = 0;

    protected int $skipped = 0;

    /** @var array<int, string> Map column index => field name */
    protected array $columnMap = [];

    /** @var int 0-based */
    protected int $headerRowIndex = 0;

    public function collection(Collection $rows): void
    {
        $rows = $rows->toArray();
        if (empty($rows)) {
            $this->errors[] = 'Fichier vide ou aucune ligne.';

            return;
        }

        $this->detectHeaderRow($rows);
        if (empty($this->columnMap)) {
            $this->errors[] = 'Ligne d\'en-têtes non trouvée (recherche: "item style", "destination", "charge weight", "price").';

            return;
        }

        $lastItemStyle = null;
        $lastDestination = null;

        for ($i = $this->headerRowIndex + 1; $i < count($rows); $i++) {
            $row = $rows[$i];
            if (! is_array($row)) {
                $row = is_object($row) ? (array) $row : [];
            }
            $rowNumber = $i + 1;

            $itemStyle = $this->getCellByMap($row, 'item_style');
            $destination = $this->getCellByMap($row, 'destination');
            $priceRaw = $this->getCellByMap($row, 'price');
            $estimationDays = $this->getCellByMap($row, 'estimation_days');

            if ($itemStyle !== null && trim((string) $itemStyle) !== '') {
                $lastItemStyle = trim((string) $itemStyle);
            }
            if ($destination !== null && trim((string) $destination) !== '') {
                $lastDestination = trim((string) $destination);
            }

            $itemStyle = $lastItemStyle;
            $destination = $lastDestination;

            if (empty($itemStyle) || empty($destination)) {
                $this->skipped++;

                continue;
            }

            $price = $this->parsePrice($priceRaw);
            if ($price === null && $priceRaw !== null && trim((string) $priceRaw) !== '') {
                $this->errors[] = "Ligne {$rowNumber}: prix invalide (« {$priceRaw} »).";
                $this->skipped++;

                continue;
            }
            if ($price === null) {
                $this->skipped++;

                continue;
            }

            $destinations = $this->splitDestinations($destination);
            $created = 0;
            foreach ($destinations as $oneCountry) {
                $country = $this->resolveCountry($oneCountry);
                if (! $country) {
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
                        'item_style' => $itemStyle,
                    ],
                    [
                        'price_per_kg' => $price,
                        'estimation_days' => $estimationDays ? trim((string) $estimationDays) : null,
                        'estimation_unit' => 'days',
                    ]
                );
                $created++;
            }
            if ($created > 0) {
                $this->imported += $created;
            } else {
                $this->errors[] = "Ligne {$rowNumber}: aucun pays trouvé dans l'app pour « {$destination} ».";
                $this->skipped++;
            }
        }
    }

    protected function detectHeaderRow(array $rows): void
    {
        $maxScan = min(40, count($rows));

        for ($r = 0; $r < $maxScan; $r++) {
            $row = $rows[$r];
            if (! is_array($row)) {
                $row = is_object($row) ? (array) $row : [];
            }
            $colItemStyle = null;
            $colDestination = null;
            $colPrice = null;
            $colEstimation = null;

            foreach ($row as $colIndex => $cell) {
                $n = $this->normalizeHeader((string) $cell);
                if ($n === '') {
                    continue;
                }
                if ($this->matchesHeader($n, ['item style', 'item_style', 'service', 'goods type', 'product type'])) {
                    $colItemStyle = $colIndex;
                }
                if ($this->matchesHeader($n, ['destination', 'country', 'pays'])) {
                    $colDestination = $colIndex;
                }
                if ($this->matchesPriceHeader($n)) {
                    $colPrice = $colIndex;
                }
                if ($this->matchesHeader($n, ['arrive time', 'arrival time', 'transit time', 'estimation', 'delivery', 'working days', 'days'])) {
                    $colEstimation = $colIndex;
                }
            }

            if ($colItemStyle !== null && $colDestination !== null && $colPrice !== null) {
                $this->headerRowIndex = $r;
                $this->columnMap[$colItemStyle] = 'item_style';
                $this->columnMap[$colDestination] = 'destination';
                $this->columnMap[$colPrice] = 'price';
                if ($colEstimation !== null) {
                    $this->columnMap[$colEstimation] = 'estimation_days';
                }

                return;
            }
        }
    }

    protected function matchesHeader(string $normalized, array $keys): bool
    {
        foreach ($keys as $k) {
            if ($normalized === $k || str_contains($normalized, $k) || str_contains($k, $normalized)) {
                return true;
            }
        }

        return false;
    }

    protected function matchesPriceHeader(string $normalized): bool
    {
        if (str_contains($normalized, 'arrive') || str_contains($normalized, 'arrival') || str_contains($normalized, 'transit')) {
            return false;
        }

        return str_contains($normalized, 'charge weight') || ($normalized === 'price') || str_starts_with($normalized, 'price') || str_contains($normalized, 'unit price');
    }

    protected function normalizeHeader(string $cell): string
    {
        $s = trim(strtolower($cell));
        $s = preg_replace('/\s+/', ' ', $s);

        return $s;
    }

    protected function getCellByMap(array $row, string $field): mixed
    {
        foreach ($this->columnMap as $index => $mappedField) {
            if ($mappedField !== $field) {
                continue;
            }
            $v = $row[$index] ?? null;
            if ($v !== null && $v !== '') {
                return $v;
            }

            return null;
        }

        return null;
    }

    protected function splitDestinations(string $destination): array
    {
        $parts = array_map('trim', preg_split('/[,;\/]+/', $destination));
        $out = [];
        foreach ($parts as $p) {
            if ($p !== '') {
                $out[] = $p;
            }
        }

        return $out;
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

        if ($country) {
            return $country;
        }
        $country = Country::where('name', 'like', '%' . $value . '%')->first();

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
