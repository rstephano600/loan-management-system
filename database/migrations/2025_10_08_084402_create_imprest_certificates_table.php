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
        Schema::create('imprest_certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->nullable()->constrained('client_loans')->onDelete('cascade');
            $table->foreignId('group_center_id')->nullable()->constrained('group_centers')->onDelete('cascade');

            $table->decimal('amount_requested', 15, 2)->default(0);
            $table->decimal('ofppt', 15, 2)->default(0);
            $table->decimal('amount_declined', 15, 2)->default(0);
            $table->decimal('loan_declined', 15, 2)->default(0);
            $table->decimal('amount_advanced', 15, 2)->default(0);
            $table->decimal('total_preclosure', 15, 2)->default(0);
            $table->decimal('loans_appr', 15, 2)->default(0);
            $table->decimal('amount_disbursed', 15, 2)->default(0);
            $table->decimal('ppt_disbursed', 15, 2)->default(0);
            $table->decimal('amount_refunded', 15, 2)->default(0);
            $table->decimal('amount_paid', 15, 2)->default(0);
            $table->decimal('fine_or_ppfees', 15, 2)->default(0);
            $table->decimal('coll_with_preclose', 15, 2)->virtualAs('amount_paid + total_preclosure');
            $table->decimal('collected_preclosure', 15, 2)->default(0);
            $table->decimal('preclosure_fees', 15, 2)->default(0);

            $table->decimal('total_amount_paid', 15, 2)->virtualAs('coll_with_preclose + total_preclosure + fine_or_ppfees + preclosure_fees');

            $table->date('date_filled')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imprest_certificates');
    }
};
