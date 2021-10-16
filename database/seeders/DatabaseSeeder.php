<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Rubric;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run()
    {
        $this->createAuthors();
        $this->createRubrics();
        $this->insertBooks();
        $this->insertCirculations();
        $this->syncWithAPI();
        $this->insertControlCirculations();
    }

    public function insertControlCirculations()
    {
        $circulations = Helper::CONTROL_CIRCULATIONS;

        $circulations = array_map(fn($array) => ['book_id' => array_key_last($array), 'user_id' => $array[array_key_last($array)]], $circulations);

        \DB::table('user_book_histories')->upsert($circulations, ['id']);

    }

    public function syncWithAPI()
    {

        $circulationBooksIDS = array_map(fn($item) => array_keys($item)[0], Helper::CONTROL_CIRCULATIONS);

        $existingBooks = Book::pluck('id')->toArray();

        $notExistingBooks = [];

        foreach ($circulationBooksIDS as $circulationBooksID) {

            if (!in_array($circulationBooksID, $existingBooks)) {
                $notExistingBooks[] = $circulationBooksID;
            }
        }


        foreach ($notExistingBooks as $bookID) {

            $mosRUData = @file_get_contents('https://www.mos.ru/aisearch/abis_frontapi/v2/book/?id='.$bookID);
            $mosRUData = json_decode($mosRUData, true);

            if ($mosRUData == null) {
                echo "ERROR $bookID".PHP_EOL;
                continue;
            }
            $authorName = $rubricName = $year = $isbn = null;

            foreach ($mosRUData['bookInfo']['items'] as $item) {
                if ($item['title'] == 'Автор') {
                    $authorName = $item['value'];
                } elseif ($item['title'] == 'Тематика') {
                    $rubricName = $item['value'];
                } elseif ($item['title'] == 'Год издания') {
                    $year = $item['value'];
                } elseif ($item['title'] == 'ISBN') {
                    $isbn = preg_replace('/[^0-9]+/', '', $item['value']);

                    if ($isbn > 10785979101101) {
                        $isbn = null;
                    }
                }
            }

            $rubric = $author = null;
            if ($authorName && !$author = Author::where('full_name', $authorName)->first()) {
                $author = Author::create(['full_name' => $authorName]);
            }

            if ($rubricName && !$rubric = Rubric::where('name', $rubricName)->first()) {
                $rubric = Rubric::create(['name' => $rubricName]);
            }

            $data = [
                'id' => $bookID,
                'year' => $year,
                'title' => $mosRUData['bookInfo']['title'],
                'rubric_id' => $rubric?->id,
                'author_id' => $author?->id,
                'isbn' => $isbn ?: null,
            ];

            Book::upsert($data, ['id']);


            echo $mosRUData['bookInfo']['title'].PHP_EOL;

        }
    }

    public function insertCirculations()
    {
        foreach (range(1, 16) as $id) {
            $this->proceedCirculationFile(storage_path("datasets_biblioteki/datasets_2/circulaton_$id.csv"));
        }
    }

    public function insertBooks()
    {

        $rubrics = Rubric::get();
        $authors = Author::get();
        $data = [];


        foreach ($this->getDataset() as $book) {

            if (!$book['author_fullName']) {
                continue;
            }

            $year = preg_replace('/[^0-9]+/', '', $book['year']);
            if ($year < 1000 || $year > 2100) {
                $year = null;
            }

            $data[] = [
                'id' => $book['id'],
                'year' => $year,
                'isbn' => $book['isbn'] ? preg_replace('/[^0-9]+/', '', $book['isbn']) : null,
                'annotation' => $book['annotation'],
                'title' => $book['title'],
                'rubric_id' => $rubrics->where('name', $book['rubric_name'])->first()->id ?? null,
                'author_id' => $authors->where('full_name', $book['author_fullName'])->first()->id,
            ];

        }


        Book::upsert($data, ['id']);

    }

    public function proceedCirculationFile(string $filePath)
    {
        $existingBookIDS = Book::pluck('id', 'id')->toArray();

        $row = 1;

        $userBooks = [];

        if (($handle = fopen($filePath, "r")) !== false) {
            while (($data = fgetcsv($handle, separator: ';')) !== false) {
                $row++;

                if ($row == 2) {
                    continue;
                }

                $data = array_map(fn($item) => iconv("Windows-1251", "UTF-8", $item), $data);

                if (!isset($existingBookIDS[$data[1]])) {
                    continue;
                }

                $userBooks[] = ['book_id' => $data[1], 'user_id' => $data[5]];

            }

            fclose($handle);


            foreach (array_chunk($userBooks, 10000) as $chunk) {

                \DB::table('user_book_histories')->upsert($chunk, ['id']);
                echo '|';
            }
        }
    }

    public function createAuthors()
    {
        $data = [];

        foreach ($this->getDataset() as $book) {

            if (!$book['author_fullName']) {
                continue;
            }


            $data[$book['author_fullName']] = [

                'contribution' => $book['contribution'],
                'surname' => $book['author_surname'],
                'names' => $book['author_names'],
                'initials' => $book['author_initials'],
                'full_name' => $book['author_fullName'],
                'full_name_alt' => $book['author_fullNameAlt'],

            ];

        }

        Author::upsert($data, ['full_name']);
    }

    public function createRubrics()
    {
        $data = [];

        foreach ($this->getDataset() as $book) {

            if (!$book['rubric_name']) {
                continue;
            }

            $data[$book['rubric_name']] = [
                'name' => $book['rubric_name'],
            ];

        }

        Rubric::upsert($data, ['name']);
    }

    public function getDataset(): array
    {

        $data = file_get_contents(storage_path('datasets_biblioteki/books.jsn'));

        return json_decode($data, true);

    }


}
