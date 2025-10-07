<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('client_documents', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id');
            
            $table->enum('document_type', [
                'national_id', 'passport', 'business_license', 
                'tax_certificate', 'bank_statement', 'financial_statement',
                'incorporation_certificate', 'memorandum', 'articles_of_association',
                'ownership_proof', 'utility_bill', 'other'
            ]);

            $table->string('document_name');
            $table->string('document_url', 500);
            $table->integer('file_size')->nullable();
            $table->string('mime_type', 100)->nullable();

            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->integer('verified_by_user_id')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->date('expiry_date')->nullable();
            $table->timestamp('uploaded_at')->useCurrent();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_documents');
    }
};

