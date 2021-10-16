<?php

namespace Database\Seeders;

use App\Helpers\BookHelper;
use App\Models\Author;
use App\Models\Book;
use App\Models\BookUnique;
use App\Models\Rubric;
use Illuminate\Database\Seeder;
use JsonMachine\JsonMachine;

class DatabaseSeeder extends Seeder
{


    // add all books
    // add circ field to books_unique
    // add circ
    // remove redundant books (no circ field)
    // remove annotations
    // del circ field

    static $uniqueBooks = [];
    static $rubricsCache = [];
    static $authorsCache = [];
    static array $existingBookIDSCache = [];


    public function run()
    {
      //  $this->insertBooksIDSFromCirculations();

        self::$existingBookIDSCache = Book::pluck('id', 'id')->toArray();

        self::$rubricsCache = Rubric::pluck('id', 'name')->toArray();
        self::$authorsCache = Author::pluck('id', 'simple_name')->toArray();

        $this->insertBooks();

        $this->insertAllBooks();


        //    $this->insertCirculations();
//        $this->insertControlCirculations();

        //  $this->syncWithAPI();
    }

    public function insertBooksIDSFromCirculations()
    {

        foreach (range(1, 16) as $id) {

            $bookIDS = [];


            echo "Circulation: $id".PHP_EOL;
            $row = 1;
            if (($handle = fopen(storage_path("datasets_biblioteki/datasets_2/circulaton_$id.csv"), "r")) !== false) {
                while (($data = fgetcsv($handle, separator: ';')) !== false) {
                    $row++;

                    if ($row == 2) {
                        continue;
                    }

                    if ((int) $data[1]) {
                        $bookIDS[$data[1]] = $data[1];
                    }

                }


            }

            fclose($handle);
            $bookIDS = array_map(fn($id) => ['id' => $id], $bookIDS);

            foreach (array_chunk($bookIDS, 10000) as $chunk) {

                Book::upsert($chunk, ['id']);

                echo '|';
            }

        }


    }

    public function insertControlCirculations()
    {
        $circulations = Helper::CONTROL_CIRCULATIONS;

        $circulations = array_map(fn($array) => ['book_id' => array_key_last($array), 'user_id' => $array[array_key_last($array)]], $circulations);

        \DB::table('user_book_histories')->upsert($circulations, ['id']);

    }

    public function syncWithAPI($bookID)
    {

        $mosRUData = @file_get_contents('https://www.mos.ru/aisearch/abis_frontapi/v2/book/?id='.$bookID);
        $mosRUData = json_decode($mosRUData, true);

        if ($mosRUData == null) {
            echo "ERROR $bookID".PHP_EOL;
            return;
        }

        $year = $isbn = null;

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


        $data = [
            'id' => $bookID,
            'year' => $year,
            'title' => $mosRUData['bookInfo']['title'],
            'isbn' => $isbn ?: null,
            'is_book_jsn' => true,
        ];


        $this->proceedBook($data);


        echo $mosRUData['bookInfo']['title'].PHP_EOL;


    }

    public function proceedBook(array $book, $isBooksJSN = false)
    {

        if (!isset(self::$existingBookIDSCache[$book['id']])) {
            return;
        }

        unset(self::$existingBookIDSCache[$book['id']]);

        $year = preg_replace('/[^0-9]+/', '', $book['year']);
        if ($year < 1000 || $year > 2100) {
            $year = null;
        }

        if (!$book['author_fullName']) {
            $authorID = null;
        } else {

            $authorSimpleName = explode(' ', BookHelper::cleanTitle($book['author_fullName']))[0];

            if (!$authorID = (self::$authorsCache[$authorSimpleName] ?? null)) {

                $authorSimpleName = explode(' ',BookHelper::cleanTitle($book['author_fullName']))[0] ;

                $data = [

                    'surname' => $book['author_surname'],
                    'names' => $book['author_names'],
                    'initials' => $book['author_initials'],
                    'full_name' => $book['author_fullName'],
                    'full_name_alt' => $book['author_fullNameAlt'],
                    'simple_name' => $authorSimpleName,

                ];

                $authorID = self::$authorsCache[$authorSimpleName] = Author::create($data)->id;
            }
        }
        $rubricName = mb_strtolower($book['rubric_name']);

        if (!$rubricID = (self::$rubricsCache[$rubricName] ?? null)) {

            $rubricID = self::$rubricsCache[$rubricName] = Rubric::create(['name' => $rubricName])->id;
        }

        $isbn = $book['isbn'] ? preg_replace('/[^0-9]+/', '', $book['isbn']) : null;
        $isbn = (int) $isbn ?: null;


        if ($book['title_orig']) {
            $titleAttribute = $book['title_orig'];
        } elseif ($book['parentTitle']) {
            $titleAttribute = $book['parentTitle'];
        } else {
            $titleAttribute = $book['title'];
        }

        $uniqueTitle = BookHelper::cleanTitle($titleAttribute);

        if (!$uniqueTitle) {
            $uniqueTitle = mb_strtolower($book['title_orig']);
        }
        if (!$uniqueTitle) {
            $uniqueTitle = mb_strtolower($book['parentTitle']);
        }
        if (!$uniqueTitle) {
            $uniqueTitle = mb_strtolower($book['title']);
        }

        if (!$uniqueTitle) {
            return;
        }

        $data = [
            'id' => $book['id'],
            'year' => $year,
            'isbn' => $isbn,
            'title' => $book['title'],
            'rubric_id' => $rubricID,
            'author_id' => $authorID,
            'is_book_jsn' => $isBooksJSN,
            'unique-title' => $uniqueTitle,
        ];

        $this->upsertBook($data, $uniqueTitle);


    }

    public function upsertBook($data, $uniqueTitle)
    {

        $dataWithoutID = $data;
        unset($dataWithoutID['id']);

        $uniqueBook = BookUnique::where('unique-title', $uniqueTitle)->first() ?? BookUnique::create($dataWithoutID);


        Book::upsert(['id' => $data['id'], 'book_unique_id' => $uniqueBook->id], ['id'], ['book_unique_id']);

    }

    public function insertCirculations()
    {


        foreach (range(1, 16) as $id) {
            echo "Circulation: $id".PHP_EOL;
            $this->proceedCirculationFile(storage_path("datasets_biblioteki/datasets_2/circulaton_$id.csv"));
        }
    }

    public function insertAllBooks()
    {
        echo 'BooksFULL.jsn'.PHP_EOL;

        $fileSize = filesize(storage_path('datasets_biblioteki/books_full.jsn'));
        $progress = $proceeded = $cnt = 0;

        $books = $this->getBooksJSNFULL();
        $start = true;

        foreach ($books as $book) {

            if ($start) {
                $this->proceedBook($book);

                $proceeded++;
            }

            $cnt++;

            if ($cnt % 100 == 0) {
                echo '|';
            }

            if ($cnt % 1000 == 0) {
                $currentProgress = intval($books->getPosition() / $fileSize * 100);

                if ($currentProgress >= 12) {
                    $start = true;
                }

                if ($currentProgress != $progress) {
                    echo 'Progress: '.$currentProgress.' % CNT: '.$cnt.' BOOKS: '.$proceeded.PHP_EOL;
                    $progress = $currentProgress;
                }
            }
        }

    }

    public function insertBooks()
    {

        echo 'Books.jsn'.PHP_EOL;

        foreach ($this->getBooksJSN() as $book) {

            $this->proceedBook($book, isBooksJSN: true);
            echo '|';
        }


    }


    public function proceedCirculationFile(string $filePath)
    {

        $row = 1;

        $userBooks = [];

        $shitBooks = [];
        if (($handle = fopen($filePath, "r")) !== false) {
            while (($data = fgetcsv($handle, separator: ';')) !== false) {
                $row++;

                if ($row == 2) {
                    continue;
                }

                $data = array_map(fn($item) => iconv("Windows-1251", "UTF-8", $item), $data);

                if (!isset(self::$existingBookIDSCache[$data[1]])) {

                    $shitBooks[$data[1]] = true;
                }
                continue;

                $userBooks[] = ['book_id' => $uniqueID, 'user_id' => $data[5]];

            }


        }

        fclose($handle);

        dump(count($shitBooks));
//        foreach (array_chunk($userBooks, 10000) as $chunk) {
//
//            \DB::table('user_book_histories')->upsert($chunk, ['id']);
//            echo '|';
//        }

    }

    public function createAuthors()
    {
        $data = [];
        echo 'Authors'.PHP_EOL;
        foreach ($this->getBooksJSN() as $book) {

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
                'simple_name' => explode(' ', $book['author_fullName'])[0],

            ];

        }

        Author::upsert($data, ['full_name']);
    }

    public function createRubrics()
    {
        echo 'Rubrics'.PHP_EOL;

        $data = [];

        foreach ($this->getBooksJSN() as $book) {

            if (!$book['rubric_name']) {
                continue;
            }

            $data[$book['rubric_name']] = [
                'name' => $book['rubric_name'],
            ];

        }

        Rubric::upsert($data, ['name']);
    }

    public function getBooksJSN(): array
    {

        $data = file_get_contents(storage_path('datasets_biblioteki/books.jsn'));

        return json_decode($data, true);

    }

    public function getBooksJSNFULL(): JsonMachine
    {

        return JsonMachine::fromFile(storage_path('datasets_biblioteki/books_full.jsn'));

    }

}
