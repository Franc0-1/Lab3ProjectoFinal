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
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->enum('type', ['percentage', 'fixed_amount', 'buy_x_get_y']);
            $table->decimal('value', 8, 2); // percentage or fixed amount
            $table->decimal('minimum_order', 8, 2)->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('active')->default(true);
            $table->integer('usage_limit')->nullable();
            $table->integer('used_count')->default(0);
            $table->json('applicable_items')->nullable(); // pizza IDs or category IDs
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
