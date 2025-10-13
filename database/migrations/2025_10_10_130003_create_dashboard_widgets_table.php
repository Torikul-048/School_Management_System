<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dashboard_widgets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('widget_key')->unique();
            $table->enum('widget_type', ['stat', 'chart', 'table', 'list'])->default('stat');
            $table->enum('chart_type', ['line', 'bar', 'pie', 'doughnut', 'area'])->nullable();
            $table->string('icon')->nullable();
            $table->string('color')->nullable();
            $table->json('roles')->nullable(); // Roles that can see this widget
            $table->string('data_source'); // Controller method or query
            $table->json('configuration')->nullable(); // Widget-specific config
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->integer('refresh_interval')->default(300); // seconds
            $table->timestamps();

            $table->index('widget_key');
            $table->index(['is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dashboard_widgets');
    }
};
