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
        Schema::create('administrations', function (Blueprint $table) {
            $table->id('administration_id');
            $table->string('fname');
            $table->string('lname');
            $table->enum('gender', ['Male', 'Female', 'Other']);
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->string('address');
            $table->string('password');
            $table->unsignedBigInteger('role_id');
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->foreign('role_id')->references('role_id')->on('roles')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('administrations');
    }
};
