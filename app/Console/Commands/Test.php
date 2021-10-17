<?php

namespace App\Console\Commands;


use App\Helpers\BookHelper;
use App\Models\Book;
use App\Models\BookUnique;
use Illuminate\Console\Command;
use JsonMachine\JsonMachine;

class Test extends Command
{
    protected $signature = 'c:test';

    public function handle()
    {



        foreach (BookUnique::where('is_book_jsn', true)->whereNull('cover_url')->get() as $book) {
            $this->updateCover($book);
            sleep(20);
        }


    }

    public function updateCover(BookUnique $book)
    {

        $query = urlencode($book->unique_title);
        $data = file_get_contents("https://www.googleapis.com/books/v1/volumes?q=$query&maxResults=1&key=AIzaSyCWb3ereD5wxedOImo9CFmkQ5T0GU3ezRg");
        $data = json_decode($data, true);

        foreach ($data['items'] as $bookData) {

            if (isset($bookData['volumeInfo']) && isset($bookData['volumeInfo']['imageLinks']) && isset($bookData['volumeInfo']['imageLinks']['thumbnail'])) {
                $book->cover_url = $bookData['volumeInfo']['imageLinks']['thumbnail'];
                $book->saveQuietly();
                echo '|';
            } else {
                echo '/';
                $book->cover_url = '';
                $book->saveQuietly();
            }

        }
    }


}
