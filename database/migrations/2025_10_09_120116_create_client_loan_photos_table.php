
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_loan_photos', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id')->nullable()->constrained()->onDelete('cascade');
            $table->integer('client_loan_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('photo'); // store file path
            $table->date('date_captured')->nullable();
            $table->text('description')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_loan_photos');
    }
};
