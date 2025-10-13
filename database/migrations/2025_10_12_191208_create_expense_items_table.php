<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('expense_items', function (Blueprint $table) {
            $table->id();

            // Relationship to main expense
            $table->foreignId('expense_id')->constrained('expenses')->onDelete('cascade');

            // Item details
            $table->string('item_name'); // e.g., "Printer Ink", "Taxi Fare"
            $table->integer('quantity')->default(1);
            $table->decimal('unit_cost', 15, 2)->nullable();
            $table->decimal('total_cost', 15, 2)->nullable();

            // Optional supplier or payee name
            $table->string('supplier_name')->nullable();

            // Documentation
            $table->string('attachment')->nullable(); // e.g., receipt for that item

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expense_items');
    }
};

