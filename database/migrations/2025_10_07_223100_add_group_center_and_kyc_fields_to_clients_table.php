<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {

            $table->integer('group_center_id')->nullable()->after('country');
            $table->string('national_id')->nullable()->after('group_center_id'); // e.g., NIDA / National ID
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed'])->nullable();
            $table->string('spouse_name')->nullable();
            $table->string('other_name')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('street_leader')->nullable();

            // File paths for media
            $table->string('profile_picture')->nullable();
            $table->string('sign_image')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropForeign(['group_center_id']);
            $table->dropColumn([
                'group_center_id',
                'national_id',
                'gender',
                'marital_status',
                'spouse_name',
                'other_name',
                'date_of_birth',
                'is_street_leader',
                'profile_picture',
                'sign_image',
            ]);
        });
    }
};