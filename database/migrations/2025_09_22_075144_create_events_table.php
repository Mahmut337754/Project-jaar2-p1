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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Sneakerness Rotterdam 2023
            $table->text('description');
            $table->string('location'); // Van Nellefabriek, Rotterdam
            $table->date('start_date'); // 2023-11-11
            $table->date('end_date'); // 2023-11-12
            $table->time('start_time'); // Opening time
            $table->time('end_time'); // Closing time
            $table->boolean('is_active')->default(true);
            $table->string('status')->default('upcoming'); // upcoming, ongoing, completed, cancelled
            $table->decimal('base_price', 8, 2)->nullable(); // Base ticket price
            $table->text('image_url')->nullable();
            $table->json('additional_info')->nullable(); // Store extra event details
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
