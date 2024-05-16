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
        Schema::create('model_events', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('actor'); // Could be null for authentication events
            $table->morphs('target');
            $table->string('action');
            $table->dateTime('occurred');
            $table->json('from')->nullable();
            $table->json('to')->nullable();
            $table->ipAddress('ip');
            $table->string('location')->nullable();
            $table->string('agent')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('model_events');
    }
};
