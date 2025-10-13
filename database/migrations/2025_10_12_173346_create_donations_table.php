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
        Schema::create('donations', function (Blueprint $table) {
            $table->id();

    // Core info
            $table->string('donation_title'); // e.g., "Support for Orphanage School"
            $table->text('description')->nullable(); // details or remarks

    // Donation specifics
            $table->decimal('amount', 15, 2); // total donated amount
            $table->string('currency', 10)->default('TZS'); // currency type if needed
            $table->date('donation_date'); // when the donation was made

    // Recipient information
            $table->string('recipient_name'); // who received the donation
            $table->string('recipient_type')->nullable(); // e.g., 'Organization', 'Individual', 'Project'
            $table->string('recipient_contact')->nullable(); // phone or email

    // Purpose / category
            $table->string('support_type')->nullable(); // e.g., 'Education', 'Health', 'Community Service'

    // Documentation
            $table->string('attachment')->nullable(); // file path for receipt, letter, etc.

    // Management / relations
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('status')->default('completed'); // e.g., pending, approved, completed

            $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
