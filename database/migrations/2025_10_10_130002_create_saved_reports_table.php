<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saved_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_template_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('report_name');
            $table->json('parameters')->nullable(); // Saved parameters used
            $table->json('filters')->nullable(); // Applied filters
            $table->string('file_path')->nullable(); // Path to generated PDF
            $table->enum('format', ['pdf', 'excel', 'csv'])->default('pdf');
            $table->timestamp('generated_at')->nullable();
            $table->integer('download_count')->default(0);
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index('report_template_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saved_reports');
    }
};
