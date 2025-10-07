
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            
            // Basic Info
            $table->enum('client_type', [
                'individual', 'sole_proprietor', 'partnership', 'llc', 'corporation'
            ]);

            // Business Info
            $table->string('business_name')->nullable();
            $table->string('business_registration_number', 100)->nullable();
            $table->string('tax_identification_number', 100)->nullable();
            $table->string('industry_sector', 100)->nullable();
            $table->decimal('years_in_business', 4, 2)->nullable();
            $table->integer('number_of_employees')->nullable();

            // Contact Info
            $table->string('first_name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->string('middle_name', 100)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('alternative_phone', 20)->nullable();

            // Address
            $table->string('address_line1', 255)->nullable();
            $table->string('address_line2', 255)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state_province', 100)->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->string('country', 100)->default('Tanzania');

            // Credit Assessment
            $table->integer('credit_score')->nullable();
            $table->enum('credit_rating', ['excellent', 'good', 'fair', 'poor', 'unrated'])->default('unrated');
            $table->enum('risk_category', ['low', 'medium', 'high', 'very_high'])->default('medium');

            // Status
            $table->enum('status', ['active', 'inactive', 'blacklisted', 'suspended'])->default('active');
            $table->text('blacklist_reason')->nullable();

            // Relationship
            $table->integer('assigned_loan_officer_id')->nullable();
            $table->boolean('kyc_completed')->default(false);
            $table->timestamp('kyc_completed_at')->nullable();

            $table->timestamps();
    });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
