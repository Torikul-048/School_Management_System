<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class ShowTeacherCredentials extends Command
{
    protected $signature = 'show:teachers';
    protected $description = 'Display teacher credentials';

    public function handle()
    {
        $teachers = User::role('Teacher')->get(['id', 'name', 'email']);
        
        if ($teachers->isEmpty()) {
            $this->error('No teachers found!');
            return;
        }
        
        $this->info('=== TEACHER CREDENTIALS ===');
        $this->newLine();
        
        foreach ($teachers as $teacher) {
            $this->line('Name: ' . $teacher->name);
            $this->line('Email: ' . $teacher->email);
            $this->line('Password: password (default)');
            $this->line('---');
        }
        
        $this->newLine();
        $this->warn('Note: If the default password does not work, you may need to reset it.');
    }
}
