<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('battle_logs', function (Blueprint $table) {
            $table->id();

            // Kratos starting stats
            $table->integer('kratos_health');
            $table->integer('kratos_strength');
            $table->integer('kratos_defence');
            $table->integer('kratos_speed');
            $table->decimal('kratos_luck', 4, 2);

            // Monster starting stats
            $table->integer('monster_health');
            $table->integer('monster_strength');
            $table->integer('monster_defence');
            $table->integer('monster_speed');
            $table->decimal('monster_luck', 4, 2);

            // Result
            $table->string('winner')->nullable(); // 'Kratos', 'Monster', 'Draw'
            $table->integer('total_turns')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('battle_logs');
    }
};
