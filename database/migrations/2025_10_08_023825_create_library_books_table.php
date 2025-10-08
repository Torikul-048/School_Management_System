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
        Schema::create('library_books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('isbn')->unique()->nullable();
            $table->string('author');
            $table->string('publisher')->nullable();
            $table->year('publication_year')->nullable();
            $table->string('edition')->nullable();
            $table->string('category'); // e.g., "Fiction", "Science", "History"
            $table->string('language')->default('English');
            $table->integer('total_copies')->default(1);
            $table->integer('available_copies')->default(1);
            $table->decimal('price', 10, 2)->nullable();
            $table->string('rack_number')->nullable();
            $table->string('book_cover')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['available', 'unavailable', 'damaged', 'lost'])->default('available');
            $table->timestamps();
            
            $table->index(['category', 'status']);
            $table->index('isbn');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('library_books');
    }
};
