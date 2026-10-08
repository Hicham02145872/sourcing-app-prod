<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * - shipping_fees.indirect_guarantee: indirect-route delivery guarantee shown
     *   in the routing popup (50 or 100), mirroring direct_guarantee.
     * - sourcing_order_destination_shipments.parcel_photo_path: per-destination
     *   colis photo for multi-destination orders (in-transit evidence).
     */
    public function up(): void
    {
        if (! Schema::hasColumn('shipping_fees', 'indirect_guarantee')) {
            Schema::table('shipping_fees', function (Blueprint $table) {
                $table->unsignedTinyInteger('indirect_guarantee')->default(100)->after('direct_guarantee');
            });
        }

        if (! Schema::hasColumn('sourcing_order_destination_shipments', 'parcel_photo_path')) {
            Schema::table('sourcing_order_destination_shipments', function (Blueprint $table) {
                $table->string('parcel_photo_path')->nullable()->after('tracking_carrier');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('shipping_fees', 'indirect_guarantee')) {
            Schema::table('shipping_fees', function (Blueprint $table) {
                $table->dropColumn('indirect_guarantee');
            });
        }

        if (Schema::hasColumn('sourcing_order_destination_shipments', 'parcel_photo_path')) {
            Schema::table('sourcing_order_destination_shipments', function (Blueprint $table) {
                $table->dropColumn('parcel_photo_path');
            });
        }
    }
};