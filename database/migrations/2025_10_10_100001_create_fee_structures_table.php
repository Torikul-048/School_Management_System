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
        Schema::create('fee_structures', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Tuition, Transport, Hostel, Library, Lab, Sports
            $table->text('description')->nullable();
            $table->decimal('amount', 10, 2);
            $table->enum('fee_type', ['tuition', 'transport', 'hostel', 'library', 'lab', 'sports', 'examination', 'admission', 'other']);
            $table->enum('frequency', ['monthly', 'quarterly', 'half-yearly', 'yearly', 'one-time']);
            $table->foreignId('class_id')->nullable()->constrained('classes')->onDelete('cascade');
            $table->date('applicable_from');
            $table->date('applicable_to')->nullable();
            $table->boolean('is_mandatory')->default(true);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_structures');
    }
};
