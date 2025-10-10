<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ResetPasswordsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * This seeder resets all user passwords to a default password.
     * Default password: password123
     */
    public function run(): void
    {
        $defaultPassword = 'password123';
        $hashedPassword = Hash::make($defaultPassword);

        // Reset all user passwords
        DB::table('users')->update([
            'password' => $hashedPassword,
            'updated_at' => now(),
        ]);

        $userCount = User::count();

        $this->command->info("✅ Successfully reset passwords for {$userCount} users");
        $this->command->info("📧 Default password for all accounts: {$defaultPassword}");
        $this->command->line('');
        $this->command->info('📋 User Login Credentials:');
        $this->command->line('─────────────────────────────────────────────────────────');

        // Display all users with their roles
        $users = User::with('roles')->get();

        foreach ($users as $user) {
            $roles = $user->roles->pluck('name')->join(', ');
            $this->command->line("📧 Email: {$user->email}");
            $this->command->line("🔑 Password: {$defaultPassword}");
            $this->command->line("👤 Role: {$roles}");
            $this->command->line("📛 Name: {$user->name}");
            $this->command->line('─────────────────────────────────────────────────────────');
        }

        $this->command->warn('⚠️  IMPORTANT: Users should change their passwords after first login!');
    }
}
