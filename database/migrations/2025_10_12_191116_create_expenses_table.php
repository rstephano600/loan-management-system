<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();

            // Core expense information
            $table->string('expense_title'); // e.g. "Office Maintenance"
            $table->text('description')->nullable();
            $table->date('expense_date');

            // Financial info
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->string('currency', 10)->default('TZS');

            // Category relation
            $table->foreignId('expense_category_id')->nullable()->constrained('expense_categories')->onDelete('set null');

            // Supporting document
            $table->string('attachment')->nullable(); // receipt or invoice

            // Management info
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
