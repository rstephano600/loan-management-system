<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('loan_categories', function (Blueprint $table) {
        $table->decimal('interest_amount', 15, 2)->change();
        $table->decimal('principal_due', 15, 2)->change();
        $table->decimal('interest_due', 15, 2)->change();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_categories', function (Blueprint $table) {
            //
        });
    }
};
