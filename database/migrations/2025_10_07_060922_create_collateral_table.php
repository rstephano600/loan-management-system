<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('collateral', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id');
            
            $table->enum('collateral_type', [
                'real_estate', 'vehicle', 'equipment', 'inventory',
                'accounts_receivable', 'cash_deposit', 'securities', 'other'
            ]);

            $table->text('description');
            $table->decimal('estimated_value', 15, 2);
            $table->decimal('appraised_value', 15, 2)->nullable();
            $table->string('currency', 3)->default('TZS');

            // Ownership & Documentation
            $table->integer('ownership_proof_document_id')->nullable();
            $table->boolean('ownership_verified')->default(false);

            // Valuation
            $table->date('valuation_date')->nullable();
            $table->string('valuation_company', 255)->nullable();
            $table->integer('valuation_document_id')->nullable();

            // Insurance
            $table->boolean('is_insured')->default(false);
            $table->string('insurance_company', 255)->nullable();
            $table->string('insurance_policy_number', 100)->nullable();
            $table->date('insurance_expiry_date')->nullable();

            // Location
            $table->text('location_address')->nullable();

            $table->enum('status', ['available', 'pledged', 'liquidated', 'released'])
                ->default('available');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('collateral');
    }
};
