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
        Schema::create('shipping_fee_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipping_fee_id')->constrained()->cascadeOnDelete();
            $table->string('transport_type'); // 'air', 'sea', 'train'
            $table->string('item_style');
            $table->decimal('price_16_49', 10, 2)->nullable();
            $table->decimal('price_50_99', 10, 2)->nullable();
            $table->decimal('price_100_499', 10, 2)->nullable();
            $table->decimal('price_plus_500', 10, 2)->nullable();
            $table->string('estimation_days')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_fee_items');
    }
};
