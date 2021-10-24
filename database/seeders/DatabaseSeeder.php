<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\BookUnique;
use App\Models\Rubric;
use Illuminate\Database\Seeder;
use JsonMachine\JsonMachine;

class DatabaseSeeder extends Seeder
{

    static $uniqueBooks = [];
    static $rubricsCache = [];
    static $authorsCache = [];
    static $uniqueBooksCache = [];
    static array $existingBookIDSCache = [];


    public function run()
    {
        $this->insertBooksIDSFromCirculations();

        self::$existingBookIDSCache = Book::pluck('id', 'id')->toArray();
        self::$uniqueBooksCache = BookUnique::pluck('id', 'unique_title')->toArray();

        self::$rubricsCache = Rubric::pluck('id', 'name')->toArray();
        self::$authorsCache = Author::pluck('id', 'simple_name')->toArray();

        $this->insertBooks();

        $this->insertAllBooks();

        $this->insertCirculations();

        $this->syncNonExistingBooksWithAPI();

        $this->insertControlCirculations();

    }


    public function syncNonExistingBooksWithAPI()
    {

        $booksWithNoUnique = Book::pluck('book_unique_id', 'id')->toArray();

        $uniqueBooks = [];
        foreach (Helper::CONTROL_CIRCULATIONS as $book) {
            $bookID = array_key_first($book);
            if (!isset($booksWithNoUnique[$bookID])) {
                $uniqueBooks[$bookID] = true;

            }
        }
        foreach ($uniqueBooks as $bookID => $uniqueBook) {

            $this->syncWithAPI($bookID);
        }

    }

    public function insertBooksIDSFromCirculations()
    {

        foreach (Helper::CONTROL_CIRCULATIONS as $book) {
            $bookID = array_key_first($book);

            Book::insertOrIgnore(['id' => $bookID]);
        }

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

        $books = Book::pluck('book_unique_id', 'id')->toArray();

        $circulations = Helper::CONTROL_CIRCULATIONS;
        $array = [];
        foreach ($circulations as $book) {

            $bookID = $books[array_key_first($book)];
            $userID = $book[array_key_first($book)];
            $array["$bookID-$userID"] = ['user_id' => $userID, 'book_id' => $bookID];
        }


        \DB::table('user_book_histories')->upsert($array, ['book_id', 'user_id']);


    }

    public function syncWithAPI($bookID)
    {

        $mosRUData = @file_get_contents('https://www.mos.ru/aisearch/abis_frontapi/v2/book/?id='.$bookID);
        $mosRUData = json_decode($mosRUData, true);

        if ($mosRUData == null) {
            echo "ERROR $bookID".PHP_EOL;
            return;
        }

        $rubricName = $authorName = $year = $isbn = null;

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
            'author_fullName' => $authorName ?: null,
            'rubric_name' => $rubricName ?: null,
        ];


        $this->proceedBook($data, true);


        echo $bookID.' '.$mosRUData['bookInfo']['title'].PHP_EOL;


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

            $authorSimpleName = explode(' ', DatabaseSeeder::cleanTitle($book['author_fullName']))[0];

            if (!$authorID = (self::$authorsCache[$authorSimpleName] ?? null)) {

                $authorSimpleName = explode(' ', DatabaseSeeder::cleanTitle($book['author_fullName']))[0];

                $data = [

                    'surname' => $book['author_surname'] ?? null,
                    'names' => $book['author_names'] ?? null,
                    'initials' => $book['author_initials'] ?? null,
                    'full_name' => $book['author_fullName'],
                    'full_name_alt' => $book['author_fullNameAlt'] ?? null,
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


        if ($book['title_orig'] ?? null) {
            $titleAttribute = $book['title_orig'];
        } elseif ($book['parentTitle'] ?? null) {
            $titleAttribute = $book['parentTitle'];
        } else {
            $titleAttribute = $book['title'];
        }

        $uniqueTitle = DatabaseSeeder::cleanTitle($titleAttribute);

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
            'unique_title' => $uniqueTitle,
        ];

        $this->upsertBook($data, $uniqueTitle);


    }

    public function upsertBook($data, $uniqueTitle)
    {

        $dataWithoutID = $data;
        unset($dataWithoutID['id']);


        if (!$uniqueBookID = self::$uniqueBooksCache[$uniqueTitle] ?? null) {

            $uniqueBook = BookUnique::create($dataWithoutID);
            self::$uniqueBooksCache[$uniqueBook->unique_title] = $uniqueBook->id;
            $uniqueBookID = $uniqueBook->id;

        }


        Book::upsert(['id' => $data['id'], 'book_unique_id' => $uniqueBookID], ['id'], ['book_unique_id']);

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
        $start = false;

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

                if ($currentProgress >= 5) {
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

        if (($handle = fopen($filePath, "r")) !== false) {
            while (($data = fgetcsv($handle, separator: ';')) !== false) {
                $row++;

                if ($row == 2) {
                    continue;
                }

                $data = array_map(fn($item) => iconv("Windows-1251", "UTF-8", $item), $data);

                if (!$uniqueID = (self::$existingBookIDSCache[$data[1]] ?? null)) {
                    continue;
                }

                $userBooks["$uniqueID-$data[5]"] = ['book_id' => $uniqueID, 'user_id' => $data[5]];

            }

        }

        fclose($handle);


        foreach (array_chunk($userBooks, 10000) as $chunk) {

            \DB::table('user_book_histories')->upsert($chunk, ['user_id', 'book_id']);
            echo '|';
        }

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

    static function cleanTitle(string $title)
    {


        $title = mb_strtolower($title);

        $title = str_replace("рассказы", "", $title);
        $title = str_replace("рассказ", "", $title);
        $title = str_replace("фантастич повесть", "", $title);
        $title = str_replace("повесть", "", $title);
        $title = str_replace("повести", "", $title);
        $title = str_replace("книга для чтения на англ яз", "", $title);
        $title = str_replace("романы", "", $title);
        $title = str_replace("роман", "", $title);
        $title = str_replace("перевод с английского", "", $title);
        $title = str_replace("пер. с англ.", "", $title);
        $title = str_replace("графический роман", "", $title);
        $title = str_replace("комикс", "", $title);
        $title = str_replace("сборник", "", $title);
        $title = str_replace("сказкапритча", "", $title);
        $title = str_replace("сказка", "", $title);
        $title = str_replace("сказки", "", $title);
        $title = str_replace("пер с фр", "", $title);
        $title = str_replace("-", "", $title);
        $title = str_replace("аудиокнига", "", $title);
        $title = str_replace("фантастические", "", $title);
        $title = str_replace("для среднего школьного возраста", "", $title);
        $title = str_replace("для старшего школьного возраста", "", $title);
        $title = str_replace("перевод с французского", "", $title);

        $title = preg_replace("/\([^)]+\)/", "", $title);

        $title = preg_replace('~[^\p{Cyrillic}a-z0-9_\s-]+~ui', '', $title);
        $title = preg_replace('/\s\s+/', ' ', $title);

        $title = trim($title, " ");


        return $title;
    }

}
