<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            // Adds the foreign key relationship to the group_centers table
            $table->foreignId('group_center_id')
                ->after('loan_category_id') // Position it logically
                ->nullable() // Make it nullable if not all loans are group loans
                ->constrained('group_centers')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropConstrainedForeignId('group_center_id');
        });
    }
};