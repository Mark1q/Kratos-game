<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('turn_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('battle_id')->constrained('battle_logs')->onDelete('cascade');

            $table->integer('turn_number');
            $table->string('attacker');          // 'Kratos' or 'Monster'
            $table->string('skill_used')->nullable();
            $table->integer('damage_dealt')->default(0);
            $table->boolean('was_lucky_miss')->default(false);
            $table->integer('defender_health_remaining');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('turn_logs');
    }
};
