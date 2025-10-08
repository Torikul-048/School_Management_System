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
        Schema::create('book_issues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('library_book_id')->constrained()->onDelete('cascade');
            $table->morphs('borrowable'); // For polymorphic relation (student or teacher)
            $table->date('issue_date');
            $table->date('due_date');
            $table->date('return_date')->nullable();
            $table->decimal('fine_amount', 10, 2)->default(0);
            $table->boolean('fine_paid')->default(false);
            $table->enum('status', ['issued', 'returned', 'overdue', 'lost'])->default('issued');
            $table->text('remarks')->nullable();
            $table->foreignId('issued_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('returned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index(['borrowable_type', 'borrowable_id', 'status']);
            $table->index(['status', 'due_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_issues');
    }
};
