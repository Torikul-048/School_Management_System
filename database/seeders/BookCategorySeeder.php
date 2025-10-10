<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BookCategory;

class BookCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Fiction',
                'code' => 'FIC',
                'description' => 'Fictional literature including novels and short stories',
                'status' => 'active',
            ],
            [
                'name' => 'Non-Fiction',
                'code' => 'NFIC',
                'description' => 'Non-fictional books including biographies and documentaries',
                'status' => 'active',
            ],
            [
                'name' => 'Science',
                'code' => 'SCI',
                'description' => 'Science books covering physics, chemistry, biology, and general science',
                'status' => 'active',
            ],
            [
                'name' => 'Mathematics',
                'code' => 'MATH',
                'description' => 'Mathematics textbooks and reference books',
                'status' => 'active',
            ],
            [
                'name' => 'Literature',
                'code' => 'LIT',
                'description' => 'Classic and contemporary literature',
                'status' => 'active',
            ],
            [
                'name' => 'History',
                'code' => 'HIST',
                'description' => 'Historical books and references',
                'status' => 'active',
            ],
            [
                'name' => 'Geography',
                'code' => 'GEO',
                'description' => 'Geography and atlas books',
                'status' => 'active',
            ],
            [
                'name' => 'Computer Science',
                'code' => 'CS',
                'description' => 'Programming, algorithms, and computer science books',
                'status' => 'active',
            ],
            [
                'name' => 'English Language',
                'code' => 'ENG',
                'description' => 'English language learning and grammar books',
                'status' => 'active',
            ],
            [
                'name' => 'Bengali Language',
                'code' => 'BEN',
                'description' => 'Bengali language and literature books',
                'status' => 'active',
            ],
            [
                'name' => 'Islamic Studies',
                'code' => 'ISL',
                'description' => 'Islamic studies and religious books',
                'status' => 'active',
            ],
            [
                'name' => 'Social Science',
                'code' => 'SOC',
                'description' => 'Social science including economics, politics, and sociology',
                'status' => 'active',
            ],
            [
                'name' => 'Arts & Crafts',
                'code' => 'ART',
                'description' => 'Arts, crafts, and creative books',
                'status' => 'active',
            ],
            [
                'name' => 'Reference',
                'code' => 'REF',
                'description' => 'Dictionaries, encyclopedias, and reference materials',
                'status' => 'active',
            ],
            [
                'name' => "Children's Books",
                'code' => 'CHILD',
                'description' => 'Books for children and early learners',
                'status' => 'active',
            ],
            [
                'name' => 'Magazines & Journals',
                'code' => 'MAG',
                'description' => 'Magazines, journals, and periodicals',
                'status' => 'active',
            ],
        ];

        foreach ($categories as $category) {
            BookCategory::create($category);
        }
    }
}
