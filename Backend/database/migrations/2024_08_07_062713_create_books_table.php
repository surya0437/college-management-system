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
        Schema::create('books', function (Blueprint $table) {
            $table->id('book_id');
            $table->string('name');
            $table->integer('quantity');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('author_id')->nullable();
            $table->unsignedBigInteger('periodic_id')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->foreign('category_id')->references('category_id')->on('book_categories')->restrictOnDelete();
            $table->foreign('author_id')->references('author_id')->on('book_authors')->restrictOnDelete();
            $table->foreign('periodic_id')->references('periodic_id')->on('periodics')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
