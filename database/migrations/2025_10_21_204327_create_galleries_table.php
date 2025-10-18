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
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('category')->default('general'); // general, sports, cultural, academic, others
            $table->date('event_date')->nullable();
            $table->json('images')->nullable(); // Array of image paths
            $table->string('cover_image')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_public')->default(true);
            $table->enum('status', ['draft', 'published', 'archived'])->default('published');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->integer('views_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('galleries');
    }
};
