<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sourcing_orders', function (Blueprint $table) {
            $table->string('china_tracking_number')->nullable()->after('tracking_carrier');
            $table->string('package_label_photo_path')->nullable()->after('china_tracking_number');
        });
    }

    public function down(): void
    {
        Schema::table('sourcing_orders', function (Blueprint $table) {
            $table->dropColumn(['china_tracking_number', 'package_label_photo_path']);
        });
    }
};