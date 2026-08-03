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
        Schema::table('sourcing_orders', function (Blueprint $table) {
            $table->string('parcel_photo_path')->nullable()->after('cloudinary_public_id');
            $table->string('parcel_photo_public_id')->nullable()->after('parcel_photo_path');
            $table->decimal('parcel_weight_kg', 8, 2)->nullable()->after('parcel_photo_public_id');
            $table->timestamp('parcel_photo_uploaded_at')->nullable()->after('parcel_weight_kg');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sourcing_orders', function (Blueprint $table) {
            $table->dropColumn([
                'parcel_photo_path',
                'parcel_photo_public_id',
                'parcel_weight_kg',
                'parcel_photo_uploaded_at',
            ]);
        });
    }
};
