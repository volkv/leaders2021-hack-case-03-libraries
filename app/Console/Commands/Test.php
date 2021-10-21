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

        foreach (BookUnique::where('is_book_jsn', true)->where('cover_url','')->get() as $book) {

            $this->updateCover($book);
            sleep(15);
        }

    }

    public function updateCover(BookUnique $book)
    {

        $query = urlencode($book->title);
        echo $book->title . PHP_EOL;

     //   $data = file_get_contents("https://www.googleapis.com/books/v1/volumes?q=$query&maxResults=1&key=AIzaSyCWb3ereD5wxedOImo9CFmkQ5T0GU3ezRg");
        $data = file_get_contents("https://www.googleapis.com/books/v1/volumes?q=$query&maxResults=1&key=AIzaSyBE67wuHd7ZRp7lqhIxZ9akjF0I7SDWfS8");
        $data = json_decode($data, true);

if (!isset($data['items'] )){
    echo '/';
    $book->cover_url = 'no-cover';
    $book->saveQuietly();
    return;
}
        foreach ($data['items'] as $bookData) {

            if (isset($bookData['volumeInfo']) && isset($bookData['volumeInfo']['imageLinks']) && isset($bookData['volumeInfo']['imageLinks']['thumbnail'])) {
                $book->cover_url = $bookData['volumeInfo']['imageLinks']['thumbnail'];
                $book->saveQuietly();
                echo '|';
            } else {
                echo '/';
                $book->cover_url = 'no-cover';
                $book->saveQuietly();
            }

        }
    }


}
