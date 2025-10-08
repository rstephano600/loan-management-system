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
    Schema::create('system_logs', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id')->nullable(); // who did the action
        $table->string('action'); // create, update, delete, login, logout, etc
        $table->string('model')->nullable(); // which model/table
        $table->unsignedBigInteger('model_id')->nullable(); // which record
        $table->text('description')->nullable(); // details of action
        $table->ipAddress('ip_address')->nullable(); // where request came from
        $table->string('user_agent')->nullable(); // browser/device info
        $table->timestamps();

        $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_logs');
    }
};
