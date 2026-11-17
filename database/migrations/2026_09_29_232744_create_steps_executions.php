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
        Schema::create('steps_executions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('production_id')
                ->constrained('productions')
                ->cascadeOnDelete();

            $table->foreignId('step_id')
                ->constrained('steps');

            $table->foreignId('operator_id')
                ->nullable()
                ->constrained('operators')
                ->nullOnDelete();

            $table->dateTime('start');
            $table->dateTime('end')->nullable();

            $table->string('status', 30);

            // Time in seconds
            $table->unsignedInteger('real_time')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('steps_executions');
    }
};
