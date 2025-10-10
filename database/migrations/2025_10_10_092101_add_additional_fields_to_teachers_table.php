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
        Schema::table('teachers', function (Blueprint $table) {
            // Add missing personal information fields
            $table->string('first_name')->after('employee_id');
            $table->string('last_name')->after('first_name');
            $table->string('email')->unique()->after('last_name');
            $table->string('phone', 20)->after('email');
            $table->string('religion', 100)->nullable()->after('nationality');
            
            // Add address fields
            $table->text('address')->nullable()->after('permanent_address');
            $table->string('city', 100)->nullable()->after('address');
            $table->string('state', 100)->nullable()->after('city');
            $table->string('zip_code', 20)->nullable()->after('state');
            $table->string('country', 100)->nullable()->after('zip_code');
            
            // Emergency contact phone
            $table->string('emergency_contact_phone', 20)->nullable()->after('emergency_contact_name');
            
            // Department field
            $table->string('department', 100)->nullable()->after('experience_years');
            
            // Bank IFSC code for Indian banks
            $table->string('bank_ifsc_code', 20)->nullable()->after('bank_account');
            $table->renameColumn('bank_account', 'bank_account_number');
            
            // Photo field
            $table->string('photo')->nullable()->after('tax_id');
            
            // Update status enum to include 'on_leave'
            $table->dropColumn('status');
        });
        
        // Add status column with new enum values
        Schema::table('teachers', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive', 'on_leave', 'resigned', 'terminated'])->default('active')->after('documents');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropColumn([
                'first_name',
                'last_name',
                'email',
                'phone',
                'religion',
                'address',
                'city',
                'state',
                'zip_code',
                'country',
                'emergency_contact_phone',
                'department',
                'bank_ifsc_code',
                'photo',
            ]);
            
            $table->renameColumn('bank_account_number', 'bank_account');
            
            // Restore old status enum
            $table->dropColumn('status');
        });
        
        Schema::table('teachers', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive', 'resigned', 'terminated'])->default('active');
        });
    }
};
