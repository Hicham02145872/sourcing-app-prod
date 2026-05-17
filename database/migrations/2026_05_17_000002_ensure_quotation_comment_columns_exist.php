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
        Schema::table('quotations', function (Blueprint $table) {
            if (! Schema::hasColumn('quotations', 'comments')) {
                $table->text('comments')->nullable()->after('sourcing_note');
            }

            if (! Schema::hasColumn('quotations', 'admin_negotiation_reply')) {
                $table->text('admin_negotiation_reply')->nullable()->after('negotiation_notes');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            if (Schema::hasColumn('quotations', 'admin_negotiation_reply')) {
                $table->dropColumn('admin_negotiation_reply');
            }

            if (Schema::hasColumn('quotations', 'comments')) {
                $table->dropColumn('comments');
            }
        });
    }
};