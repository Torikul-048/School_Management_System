<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ResetAllPasswords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:reset-passwords {password=password123}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset all user passwords to a default password';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $defaultPassword = $this->argument('password');
        
        if (!$this->confirm("Are you sure you want to reset ALL user passwords to '{$defaultPassword}'?", false)) {
            $this->info('Password reset cancelled.');
            return 0;
        }

        $hashedPassword = Hash::make($defaultPassword);

        // Reset all user passwords
        $updatedCount = DB::table('users')->update([
            'password' => $hashedPassword,
            'updated_at' => now(),
        ]);

        $this->newLine();
        $this->info("✅ Successfully reset passwords for {$updatedCount} users");
        $this->info("🔑 New default password: {$defaultPassword}");
        $this->newLine();

        // Display all users with their credentials
        $users = User::with('roles')->get();

        if ($users->isEmpty()) {
            $this->warn('⚠️  No users found in the database!');
            $this->info('💡 You may need to run database seeders first: php artisan db:seed');
            return 0;
        }

        $this->info('📋 User Login Credentials:');
        $this->line('═════════════════════════════════════════════════════════════════');

        $tableData = [];
        foreach ($users as $user) {
            $roles = $user->roles->pluck('name')->join(', ') ?: 'No Role';
            $tableData[] = [
                $user->email,
                $defaultPassword,
                $roles,
                $user->name,
                $user->status ?? 'N/A',
            ];
        }

        $this->table(
            ['Email', 'Password', 'Role(s)', 'Name', 'Status'],
            $tableData
        );

        $this->newLine();
        $this->warn('⚠️  SECURITY WARNING:');
        $this->warn('   - Users should change their passwords immediately after first login');
        $this->warn('   - Store these credentials in a secure location');
        $this->warn('   - Do not share passwords via email or unsecured channels');
        $this->newLine();

        return 0;
    }
}
