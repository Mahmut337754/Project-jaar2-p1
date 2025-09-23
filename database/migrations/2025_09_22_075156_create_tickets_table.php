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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('name'); // e.g., "Zaterdag Early Access"
            $table->text('description'); // e.g., "Entry from 11:00"
            $table->enum('day', ['saturday', 'sunday']); // Which day
            $table->time('admission_time'); // 11:00, 12:00, 14:00, 16:00
            $table->decimal('price', 8, 2); // €50, €15, €12, etc.
            $table->integer('total_quantity'); // Total available tickets
            $table->integer('sold_quantity')->default(0); // How many sold
            $table->integer('available_quantity')->virtualAs('total_quantity - sold_quantity'); // Auto-calculated
            $table->boolean('is_active')->default(true);
            $table->json('features')->nullable(); // Early access, special perks, etc.
            $table->timestamps();

            $table->index(['event_id', 'day', 'admission_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
