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
        Schema::create('request_books', function (Blueprint $table) {
            $table->id('requestBook_id');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('book_id');
            $table->boolean('status')->default(false);
            $table->timestamps();

            $table->foreign('student_id')->references('student_id')->on('students')->restrictOnDelete();
            $table->foreign('book_id')->references('book_id')->on('books')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_books');
    }
};
