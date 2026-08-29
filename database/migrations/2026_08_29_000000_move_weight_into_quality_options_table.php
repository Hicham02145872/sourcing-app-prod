<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $quotations = DB::table('quotations')
            ->whereNotNull('unit_weight')
            ->whereNotNull('quality_options')
            ->select('id', 'unit_weight', 'weight_unit', 'quality_options')
            ->get();

        foreach ($quotations as $quotation) {
            $options = json_decode($quotation->quality_options, true);

            if (!is_array($options) || empty($options)) {
                continue;
            }

            $weight = $quotation->unit_weight;
            $unit = !empty($quotation->weight_unit) ? $quotation->weight_unit : 'g';

            $changed = false;
            foreach ($options as $key => &$option) {
                if (is_array($option)) {
                    $option['weight'] = $weight;
                    $option['weight_unit'] = $unit;
                    $changed = true;
                }
            }
            unset($option);

            if ($changed) {
                DB::table('quotations')
                    ->where('id', $quotation->id)
                    ->update(['quality_options' => json_encode($options)]);
            }
        }

        Schema::table('quotations', function (Blueprint $table) {
            $table->dropColumn(['unit_weight', 'weight_unit']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->decimal('unit_weight', 15, 2)->nullable()->after('commission_service');
            $table->string('weight_unit')->default('g')->after('unit_weight');
        });

        $quotations = DB::table('quotations')
            ->whereNotNull('quality_options')
            ->select('id', 'quality_options')
            ->get();

        foreach ($quotations as $quotation) {
            $options = json_decode($quotation->quality_options, true);

            if (!is_array($options) || !isset($options['medium']) || !is_array($options['medium'])) {
                continue;
            }

            $mediumWeight = $options['medium']['weight'] ?? null;

            if ($mediumWeight === null || $mediumWeight === '') {
                continue;
            }

            DB::table('quotations')
                ->where('id', $quotation->id)
                ->update([
                    'unit_weight' => $mediumWeight,
                    'weight_unit' => $options['medium']['weight_unit'] ?? 'g',
                ]);
        }
    }
};