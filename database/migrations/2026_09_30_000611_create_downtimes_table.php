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
        Schema::create('downtimes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('production_line_id')
                ->constrained('production_lines');

            $table->foreignId('step_id')
                ->nullable()
                ->constrained('steps')
                ->nullOnDelete();

            $table->string('reason', 150);

            $table->dateTime('start');
            $table->dateTime('end')->nullable();

            // Tempo em segundos
            $table->unsignedInteger('duracao')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('downtimes');
    }
};
