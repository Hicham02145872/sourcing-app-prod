<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'sourcing_requests',
            'quotations',
            'quotation_media',
            'sourcing_orders',
            'sourcing_order_media',
            'payment_methods',
            'users',
            'refund_requests',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && !Schema::hasColumn($table, 'cloudinary_public_id')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->string('cloudinary_public_id', 512)->nullable()->after('id');
                });
            }
        }
    }

    public function down(): void
    {
        $tables = [
            'sourcing_requests',
            'quotations',
            'quotation_media',
            'sourcing_orders',
            'sourcing_order_media',
            'payment_methods',
            'users',
            'refund_requests',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'cloudinary_public_id')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->dropColumn('cloudinary_public_id');
                });
            }
        }
    }
};
