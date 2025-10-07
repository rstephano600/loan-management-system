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
        Schema::create('client_guarantors', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id');

            // Personal Information
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('national_id', 50)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('phone_number', 20);

            // Address
            $table->string('address_line1', 255)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('country', 100)->nullable();

            // Financial Info
            $table->string('occupation', 255)->nullable();
            $table->string('employer', 255)->nullable();
            $table->decimal('monthly_income', 15, 2)->nullable();

            // Relationship
            $table->string('relationship_to_client', 100)->nullable();

            // Credit Check
            $table->integer('credit_score')->nullable();
            $table->boolean('credit_checked')->default(false);

            // Status
            $table->enum('status', ['active', 'inactive', 'declined'])->default('active');

            // Verification
            $table->boolean('verified')->default(false);
            $table->integer('verified_by_user_id')->nullable();
            $table->timestamp('verified_at')->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_guarantors');
    }
};
