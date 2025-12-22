<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SalesMarginExport implements FromCollection, ShouldAutoSize, WithColumnFormatting, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $orders;

    protected $columns;

    // Mapping of column keys to labels and value closures
    protected $columnDefinitions = [];

    public function __construct($orders, $columns = [])
    {
        $this->orders = $orders;
        $this->columns = ! empty($columns) ? $columns : array_keys($this->getDefaultColumnDefinitions());
        $this->columnDefinitions = $this->getDefaultColumnDefinitions();
    }

    private function getDefaultColumnDefinitions(): array
    {
        return [
            'id' => ['label' => 'ID', 'value' => fn ($o) => $o->id],
            'product' => ['label' => 'Produit', 'value' => fn ($o) => $o->quotation->sourcingRequest->product_name ?? 'N/A'],
            'quantity' => ['label' => 'Quantité', 'value' => fn ($o) => $o->quotation->sourcingRequest->destinations->sum('quantity') ?? 0],
            'destination' => ['label' => 'Destination', 'value' => fn ($o) => $o->quotation->sourcingRequest->destinations->first()->country->name ?? 'N/A'],
            'date' => ['label' => 'Date', 'value' => fn ($o) => $o->created_at->format('d/m/Y')],
            'sales' => ['label' => 'Total Vente', 'value' => fn ($o) => (float) $o->total_amount],
            'cost_product' => ['label' => 'Coût Produit', 'value' => fn ($o) => (float) ($o->product_cost_price ?? 0)],
            'cost_shipping' => ['label' => 'Coût Expédition', 'value' => fn ($o) => (float) ($o->shipping_cost_real ?? 0)],
            'cost_loss' => ['label' => 'Pertes', 'value' => fn ($o) => (float) ($o->rejection_loss_cost ?? 0)],
            'cost_total' => ['label' => 'Coût Total', 'value' => fn ($o) => (float) (($o->product_cost_price ?? 0) + ($o->shipping_cost_real ?? 0) + ($o->rejection_loss_cost ?? 0))],
            'profit' => ['label' => 'Profit Net', 'value' => fn ($o) => (float) ($o->net_profit_or_loss ?? 0)],
            'margin_percent' => ['label' => 'Marge (%)', 'value' => function ($o) {
                $netProfit = $o->net_profit_or_loss ?? 0;
                $marginPercent = ($o->total_amount > 0) ? ($netProfit / $o->total_amount) / 100 : 0; // Return as decimal for Excel % format

                return (float) $marginPercent;
            }],
            'status' => ['label' => 'Status', 'value' => fn ($o) => ucfirst($o->status)],
        ];
    }

    public function collection()
    {
        return $this->orders;
    }

    public function headings(): array
    {
        $headers = [];
        foreach ($this->columns as $colKey) {
            if (isset($this->columnDefinitions[$colKey])) {
                $headers[] = $this->columnDefinitions[$colKey]['label'];
            }
        }

        return $headers;
    }

    public function map($order): array
    {
        $row = [];
        foreach ($this->columns as $colKey) {
            if (isset($this->columnDefinitions[$colKey])) {
                $row[] = $this->columnDefinitions[$colKey]['value']($order);
            }
        }

        return $row;
    }

    public function styles(Worksheet $sheet): array
    {
        $lastRow = $this->orders->count() + 1;
        $lastColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($this->columns));

        return [
            // Style the header row
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF'], 'size' => 12],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF1A237E'], // Dark Blue (Slate)
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],

            // Add borders to the entire data range
            'A1:'.$lastColumn.$lastRow => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ],
                ],
            ],

            // Center certain columns (ID, Date, Status, Qty)
            'A2:A'.$lastRow => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]], // ID
        ];
    }

    public function title(): string
    {
        return 'Rapport des Marges';
    }

    public function columnFormats(): array
    {
        $formats = [];
        $colIndex = 1;

        foreach ($this->columns as $colKey) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);

            switch ($colKey) {
                case 'sales':
                case 'cost_product':
                case 'cost_shipping':
                case 'cost_loss':
                case 'cost_total':
                case 'profit':
                    $formats[$colLetter] = '#,##0.00 "€"'; // Or use currency from data if dynamic
                    break;
                case 'margin_percent':
                    $formats[$colLetter] = '0.00%';
                    break;
            }
            $colIndex++;
        }

        return $formats;
    }
}
