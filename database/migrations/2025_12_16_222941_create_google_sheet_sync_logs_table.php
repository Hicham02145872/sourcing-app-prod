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
        Schema::create('google_sheet_sync_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sourcing_order_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('status', ['success', 'error'])->default('success');
            $table->string('action')->default('append'); // append, update, delete
            $table->text('error_message')->nullable();
            $table->string('error_code')->nullable();
            $table->json('data')->nullable(); // Store synced data for reference
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('google_sheet_sync_logs');
    }
};
