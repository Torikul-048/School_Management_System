<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\BookCategory;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $categories = BookCategory::all();
        
        $books = [
            // Fiction
            [
                'title' => 'To Kill a Mockingbird',
                'isbn' => '978-0-06-112008-4',
                'author' => 'Harper Lee',
                'publisher' => 'Harper Perennial Modern Classics',
                'publication_year' => 1960,
                'category_id' => $categories->where('code', 'FIC')->first()->id,
                'description' => 'A classic of modern American literature which has won many awards.',
                'language' => 'English',
                'total_copies' => 5,
                'available_copies' => 5,
                'price' => 450.00,
                'rack_location' => 'A-1',
                'status' => 'available',
            ],
            [
                'title' => '1984',
                'isbn' => '978-0-452-28423-4',
                'author' => 'George Orwell',
                'publisher' => 'Penguin Books',
                'publication_year' => 1949,
                'category_id' => $categories->where('code', 'FIC')->first()->id,
                'description' => 'A dystopian social science fiction novel and cautionary tale.',
                'language' => 'English',
                'total_copies' => 4,
                'available_copies' => 4,
                'price' => 380.00,
                'rack_location' => 'A-2',
                'status' => 'available',
            ],
            
            // Science
            [
                'title' => 'Physics - Class 9 & 10 (NCTB)',
                'isbn' => '978-984-5000-123-4',
                'author' => 'National Curriculum and Textbook Board',
                'publisher' => 'NCTB',
                'publication_year' => 2023,
                'category_id' => $categories->where('code', 'SCI')->first()->id,
                'description' => 'Official NCTB physics textbook for class 9 and 10.',
                'language' => 'Bengali',
                'total_copies' => 20,
                'available_copies' => 20,
                'price' => 200.00,
                'rack_location' => 'B-1',
                'status' => 'available',
            ],
            [
                'title' => 'Chemistry - Class 9 & 10 (NCTB)',
                'isbn' => '978-984-5000-124-1',
                'author' => 'National Curriculum and Textbook Board',
                'publisher' => 'NCTB',
                'publication_year' => 2023,
                'category_id' => $categories->where('code', 'SCI')->first()->id,
                'description' => 'Official NCTB chemistry textbook for class 9 and 10.',
                'language' => 'Bengali',
                'total_copies' => 20,
                'available_copies' => 20,
                'price' => 200.00,
                'rack_location' => 'B-2',
                'status' => 'available',
            ],
            [
                'title' => 'Biology - Class 9 & 10 (NCTB)',
                'isbn' => '978-984-5000-125-8',
                'author' => 'National Curriculum and Textbook Board',
                'publisher' => 'NCTB',
                'publication_year' => 2023,
                'category_id' => $categories->where('code', 'SCI')->first()->id,
                'description' => 'Official NCTB biology textbook for class 9 and 10.',
                'language' => 'Bengali',
                'total_copies' => 20,
                'available_copies' => 20,
                'price' => 200.00,
                'rack_location' => 'B-3',
                'status' => 'available',
            ],
            
            // Mathematics
            [
                'title' => 'Higher Mathematics - Class 9 & 10',
                'isbn' => '978-984-5000-126-5',
                'author' => 'National Curriculum and Textbook Board',
                'publisher' => 'NCTB',
                'publication_year' => 2023,
                'category_id' => $categories->where('code', 'MATH')->first()->id,
                'description' => 'Official NCTB higher mathematics textbook.',
                'language' => 'Bengali',
                'total_copies' => 15,
                'available_copies' => 15,
                'price' => 180.00,
                'rack_location' => 'C-1',
                'status' => 'available',
            ],
            
            // Computer Science
            [
                'title' => 'Introduction to Algorithms',
                'isbn' => '978-0-262-03384-8',
                'author' => 'Thomas H. Cormen',
                'publisher' => 'MIT Press',
                'publication_year' => 2009,
                'category_id' => $categories->where('code', 'CS')->first()->id,
                'description' => 'Comprehensive textbook on computer algorithms.',
                'language' => 'English',
                'total_copies' => 3,
                'available_copies' => 3,
                'price' => 2500.00,
                'rack_location' => 'D-1',
                'status' => 'available',
            ],
            
            // English Language
            [
                'title' => 'English For Today - Class 9',
                'isbn' => '978-984-5000-127-2',
                'author' => 'National Curriculum and Textbook Board',
                'publisher' => 'NCTB',
                'publication_year' => 2023,
                'category_id' => $categories->where('code', 'ENG')->first()->id,
                'description' => 'Official NCTB English textbook for class 9.',
                'language' => 'English',
                'total_copies' => 25,
                'available_copies' => 25,
                'price' => 150.00,
                'rack_location' => 'E-1',
                'status' => 'available',
            ],
            [
                'title' => 'English For Today - Class 10',
                'isbn' => '978-984-5000-128-9',
                'author' => 'National Curriculum and Textbook Board',
                'publisher' => 'NCTB',
                'publication_year' => 2023,
                'category_id' => $categories->where('code', 'ENG')->first()->id,
                'description' => 'Official NCTB English textbook for class 10.',
                'language' => 'English',
                'total_copies' => 25,
                'available_copies' => 25,
                'price' => 150.00,
                'rack_location' => 'E-2',
                'status' => 'available',
            ],
            
            // Bengali Language
            [
                'title' => 'Bangla Sahitya - Class 9',
                'isbn' => '978-984-5000-129-6',
                'author' => 'National Curriculum and Textbook Board',
                'publisher' => 'NCTB',
                'publication_year' => 2023,
                'category_id' => $categories->where('code', 'BEN')->first()->id,
                'description' => 'Official NCTB Bengali literature textbook for class 9.',
                'language' => 'Bengali',
                'total_copies' => 25,
                'available_copies' => 25,
                'price' => 150.00,
                'rack_location' => 'F-1',
                'status' => 'available',
            ],
            
            // History
            [
                'title' => 'Bangladesh and World Civilization - Class 9 & 10',
                'isbn' => '978-984-5000-130-2',
                'author' => 'National Curriculum and Textbook Board',
                'publisher' => 'NCTB',
                'publication_year' => 2023,
                'category_id' => $categories->where('code', 'HIST')->first()->id,
                'description' => 'History of Bangladesh and world civilizations.',
                'language' => 'Bengali',
                'total_copies' => 20,
                'available_copies' => 20,
                'price' => 170.00,
                'rack_location' => 'G-1',
                'status' => 'available',
            ],
            
            // Reference
            [
                'title' => 'Oxford English Dictionary',
                'isbn' => '978-0-19-861186-8',
                'author' => 'Oxford University Press',
                'publisher' => 'Oxford University Press',
                'publication_year' => 2020,
                'category_id' => $categories->where('code', 'REF')->first()->id,
                'description' => 'Comprehensive English language dictionary.',
                'language' => 'English',
                'total_copies' => 5,
                'available_copies' => 5,
                'price' => 1500.00,
                'rack_location' => 'H-1',
                'status' => 'available',
            ],
            
            // Literature
            [
                'title' => 'Gitanjali',
                'isbn' => '978-81-7223-524-7',
                'author' => 'Rabindranath Tagore',
                'publisher' => 'Rupa Publications',
                'publication_year' => 2010,
                'category_id' => $categories->where('code', 'LIT')->first()->id,
                'description' => 'Collection of poems by Nobel laureate Rabindranath Tagore.',
                'language' => 'Bengali',
                'total_copies' => 8,
                'available_copies' => 8,
                'price' => 250.00,
                'rack_location' => 'I-1',
                'status' => 'available',
            ],
        ];

        foreach ($books as $book) {
            Book::create($book);
        }
    }
}
