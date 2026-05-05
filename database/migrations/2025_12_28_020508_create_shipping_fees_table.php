<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shipping_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained()->cascadeOnDelete()->unique();
            $table->decimal('sea_fee', 10, 2)->nullable();
            $table->decimal('train_fee', 10, 2)->nullable();
            $table->decimal('air_normal_fee', 10, 2)->nullable();
            $table->decimal('air_brand_fee', 10, 2)->nullable();
            $table->decimal('air_battery_fee', 10, 2)->nullable();
            $table->decimal('air_liquid_fee', 10, 2)->nullable();
            $table->string('currency')->default('USD'); // Assuming USD for now, or just display "Fees"
            $table->string('unit')->default('kg'); // Assuming per kg
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_fees');
    }
};
