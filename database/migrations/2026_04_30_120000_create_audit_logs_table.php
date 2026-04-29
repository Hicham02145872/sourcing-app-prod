<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action', 128)->index();
            $table->string('target_type', 128)->nullable()->index();
            $table->unsignedBigInteger('target_id')->nullable()->index();
            $table->json('payload')->nullable();
            $table->string('ip', 45)->nullable();
            $table->string('request_id', 64)->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
