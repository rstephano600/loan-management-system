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
        Schema::create('donation_items', function (Blueprint $table) {
            $table->id();

            // Link to main donation
            $table->foreignId('donation_id')->constrained('donations')->onDelete('cascade');

            // Item details
            $table->string('item_name'); // e.g. "School Books", "Cash", "Clothes"
            $table->integer('quantity')->default(1);
            $table->decimal('unit_value', 15, 2)->nullable(); // value of one item
            $table->decimal('total_value', 15, 2)->nullable(); // calculated total (quantity * unit_value)

            // If cash donation
            $table->string('currency', 10)->default('TZS');

            // Optional attachment (receipt, photo)
            $table->string('attachment')->nullable();

            $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donation_items');
    }
};
