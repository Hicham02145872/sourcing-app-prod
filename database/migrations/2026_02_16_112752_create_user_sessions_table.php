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
        if (!Schema::hasTable('user_sessions')) {
            Schema::create('user_sessions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('session_id')->unique();
                $table->string('ip_address', 45);
                $table->text('user_agent')->nullable();
                $table->timestamp('last_activity');
                $table->boolean('is_current')->default(false);
                $table->timestamps();

                $table->index(['user_id', 'last_activity']);
                $table->index('session_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_sessions');
    }
};
