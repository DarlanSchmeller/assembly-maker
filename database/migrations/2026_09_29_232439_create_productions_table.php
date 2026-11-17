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
        Schema::create('productions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')
                ->constrained('products');

            $table->foreignId('production_lines_id')
                ->constrained('production_lines');

            $table->string('tracking_code', 100)->unique();

            $table->dateTime('start');
            $table->dateTime('end')->nullable();
            $table->string('status', 30);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productions');
    }
};
