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
        Schema::create('fee_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Tuition Fee", "Transport Fee", "Library Fee"
            $table->text('description')->nullable();
            $table->decimal('amount', 10, 2);
            $table->enum('frequency', ['one-time', 'monthly', 'quarterly', 'semester', 'annual'])->default('monthly');
            $table->foreignId('class_id')->nullable()->constrained()->onDelete('cascade'); // Specific to class or null for all
            $table->boolean('is_mandatory')->default(true);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            
            $table->index(['class_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_categories');
    }
};
