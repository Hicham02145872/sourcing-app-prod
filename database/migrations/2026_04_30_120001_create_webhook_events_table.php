<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('webhook_events')) {
            Schema::create('webhook_events', function (Blueprint $table) {
                $table->id();
                $table->string('source', 64)->index();
                $table->string('event_type', 128)->nullable()->index();
                $table->json('payload');
                $table->string('status', 32)->default('received')->index();
                $table->timestamp('processed_at')->nullable();
                $table->text('error')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('webhook_events');
    }
};
