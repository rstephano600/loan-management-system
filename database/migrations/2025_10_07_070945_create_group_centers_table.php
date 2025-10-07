<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('group_centers', function (Blueprint $table) {
            $table->id();

            // Relationship to groups
            $table->foreignId('group_id')->constrained('groups')->onDelete('cascade');

            // Center Details
            $table->string('center_code')->unique();
            $table->string('center_name');
            $table->string('location')->nullable();
            $table->string('area')->nullable(); // e.g. ward / district name
            $table->text('description')->nullable();

            // Operational Details
            $table->foreignId('collection_officer_id')
                ->nullable()
                ->constrained('employees')
                ->onDelete('set null'); // officer assigned to this center

            $table->date('established_date')->nullable();
            $table->boolean('is_active')->default(true);

            // Audit
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('group_centers');
    }
};
