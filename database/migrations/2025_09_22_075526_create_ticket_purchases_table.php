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
        Schema::create('ticket_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Buyer
            $table->foreignId('ticket_id')->constrained()->onDelete('cascade'); // Which ticket type
            $table->foreignId('event_id')->constrained()->onDelete('cascade'); // Which event
            $table->integer('quantity'); // How many tickets purchased
            $table->decimal('unit_price', 8, 2); // Price per ticket at time of purchase
            $table->decimal('total_price', 8, 2); // Total amount paid
            $table->string('purchase_reference')->unique(); // Unique reference for the purchase
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'refunded'])->default('confirmed');
            $table->string('buyer_name'); // Name of the person buying
            $table->string('buyer_email'); // Email for communication
            $table->string('buyer_phone')->nullable(); // Phone for contact
            $table->json('attendee_details')->nullable(); // Names of ticket holders if different
            $table->timestamp('purchased_at')->useCurrent();
            $table->timestamps();

            $table->index(['user_id', 'event_id']);
            $table->index(['ticket_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_purchases');
    }
};
