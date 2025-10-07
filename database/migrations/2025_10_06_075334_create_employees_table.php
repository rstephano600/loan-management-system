<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id')->unique()->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('gender')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('nida')->nullable();
            $table->string('tribe')->nullable();
            $table->string('religion')->nullable();
            $table->string('address')->nullable();
            $table->string('education_level')->nullable();
            $table->string('position')->nullable();
            $table->string('department')->nullable();
            $table->date('date_of_hire')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('profile_picture')->nullable();
            $table->string('cv')->nullable();
            $table->text('other_information')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
