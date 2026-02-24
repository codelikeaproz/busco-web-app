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
        Schema::create('quedan_prices', function (Blueprint $table) {
            $table->id();
            $table->decimal('price', 10, 2);
            $table->date('trading_date');
            $table->date('weekending_date');
            $table->decimal('difference', 10, 2)->nullable();
            $table->string('trend', 20)->nullable();
            $table->string('price_subtext', 255)->nullable();
            $table->text('notes')->nullable();
            $table->string('status', 20)->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quedan_prices');
    }
};
