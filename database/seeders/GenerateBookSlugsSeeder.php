<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;

class GenerateBookSlugsSeeder extends Seeder
{
    public function run(): void
    {
        $books = Book::all();
        
        foreach ($books as $book) {
            $book->slug = Book::generateUniqueSlug($book->title, $book->id);
            $book->save();
        }
        
        $this->command->info('Slugs generated for ' . $books->count() . ' books!');
    }
}
