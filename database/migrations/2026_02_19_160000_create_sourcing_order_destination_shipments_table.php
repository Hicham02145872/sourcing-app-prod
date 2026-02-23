<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * When an order has more than one destination, each destination can have its own tracking number and shipping company.
     */
    public function up(): void
    {
        Schema::create('sourcing_order_destination_shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sourcing_order_id')->constrained('sourcing_orders')->onDelete('cascade');
            $table->foreignId('sourcing_request_destination_id')->constrained('sourcing_request_destinations')->onDelete('cascade');
            $table->string('tracking_number')->nullable();
            $table->string('tracking_carrier')->nullable();
            $table->foreignId('shipping_company_id')->nullable()->constrained('shipping_companies')->nullOnDelete();
            $table->timestamps();

            $table->unique(['sourcing_order_id', 'sourcing_request_destination_id'], 'order_destination_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sourcing_order_destination_shipments');
    }
};
