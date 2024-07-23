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
        Schema::create('students', function (Blueprint $table) {
            $table->id('student_id');
            $table->string('roll_no')->unique();
            $table->string('fname');
            $table->string('lname');
            $table->enum('gender', ['Male', 'Female', 'Other']);
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->string('address');
            $table->string('password');
            $table->date('date_of_birth');
            $table->unsignedBigInteger('program_id');
            $table->string('image')->nullable();
            $table->boolean('status')->default(true);
            $table->boolean('face')->nullable();
            $table->timestamps();

            $table->foreign('program_id')->references('program_id')->on('programs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
