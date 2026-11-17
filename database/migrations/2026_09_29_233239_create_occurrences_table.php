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
        Schema::create('occurrences', function (Blueprint $table) {
            $table->id();

            $table->foreignId('production_id')
                ->constrained('productions')
                ->cascadeOnDelete();

            $table->foreignId('step_id')
                ->nullable()
                ->constrained('steps')
                ->nullOnDelete();

            $table->string('type', 80);
            $table->text('description')->nullable();
            $table->string('severity', 30);

            $table->dateTime('start');
            $table->dateTime('end')->nullable;

            $table->boolean('solved')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('occurrences');
    }
};
