<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Announcement;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::role('Admin')->first();
        
        if (!$admin) {
            $this->command->info('No Admin user found. Skipping announcement seeding.');
            return;
        }

        $announcements = [
            [
                'title' => 'Welcome to School Management System',
                'content' => 'Welcome to our new School Management System! This platform will help streamline all academic and administrative processes. Teachers can manage their classes, take attendance, enter marks, and communicate with students and parents. Students and parents can view academic progress, attendance records, and stay updated with school activities.',
                'type' => 'general',
                'priority' => 'high',
                'status' => 'active',
                'is_pinned' => true,
                'is_published' => true,
                'send_email' => false,
                'send_sms' => false,
                'target_audience' => json_encode(['all']),
                'publish_date' => now()->format('Y-m-d'),
                'created_by' => $admin->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Annual Sports Day - Next Month',
                'content' => 'We are excited to announce that our Annual Sports Day will be held next month! All students are encouraged to participate in various sports activities. Registration will begin next week. Parents are warmly invited to attend and support their children.',
                'type' => 'event',
                'priority' => 'normal',
                'status' => 'active',
                'is_pinned' => false,
                'is_published' => true,
                'send_email' => false,
                'send_sms' => false,
                'target_audience' => json_encode(['all']),
                'publish_date' => now()->subDays(5)->format('Y-m-d'),
                'created_by' => $admin->id,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'title' => 'Mid-Term Examination Schedule Released',
                'content' => 'The mid-term examination schedule has been released. Examinations will commence from next week. Students are advised to check the examination timetable and prepare accordingly. Teachers have been instructed to complete the syllabus before the examination dates.',
                'type' => 'exam',
                'priority' => 'urgent',
                'status' => 'active',
                'is_pinned' => true,
                'is_published' => true,
                'send_email' => false,
                'send_sms' => false,
                'target_audience' => json_encode(['students', 'teachers']),
                'publish_date' => now()->subDays(3)->format('Y-m-d'),
                'created_by' => $admin->id,
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
            [
                'title' => 'Parent-Teacher Meeting This Weekend',
                'content' => 'A parent-teacher meeting has been scheduled for this Saturday from 10 AM to 2 PM. Parents are requested to meet their child\'s class teacher to discuss academic progress and behavior. Please collect the meeting schedule from the reception desk.',
                'type' => 'event',
                'priority' => 'high',
                'status' => 'active',
                'is_pinned' => false,
                'is_published' => true,
                'send_email' => false,
                'send_sms' => false,
                'target_audience' => json_encode(['parents', 'teachers']),
                'publish_date' => now()->subDays(2)->format('Y-m-d'),
                'created_by' => $admin->id,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            [
                'title' => 'Library: New Books Available',
                'content' => 'Our school library has received a new collection of books across various subjects including science, mathematics, literature, and general knowledge. Students can visit the library during break time to explore and issue books. Librarians are available to help you find the books you need.',
                'type' => 'general',
                'priority' => 'low',
                'status' => 'active',
                'is_pinned' => false,
                'is_published' => true,
                'send_email' => false,
                'send_sms' => false,
                'target_audience' => json_encode(['all']),
                'publish_date' => now()->subDays(1)->format('Y-m-d'),
                'created_by' => $admin->id,
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
        ];

        foreach ($announcements as $announcement) {
            Announcement::create($announcement);
        }

        $this->command->info('Successfully created ' . count($announcements) . ' announcements!');
    }
}
