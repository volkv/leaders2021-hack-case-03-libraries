<?php

namespace App\Console\Commands;

use App\Models\Author;
use App\Models\Book;
use App\Models\Library;
use App\Models\Rubric;
use Illuminate\Console\Command;


class BooksFromMosRU extends Command
{

    protected $signature = 'c:mosru {part}';


    public function handle()
    {
        $part = $this->argument('part');

        $row = 1;
        $bookIDS = [];
        if (($handle = fopen(storage_path('datasets_biblioteki/datasets_2/circulaton_1.csv'), "r")) !== false) {
            while (($data = fgetcsv($handle, separator: ';')) !== false) {
                $row++;
                if ($row == 2) {
                    continue;
                }

                $data = array_map(fn($item) => iconv("Windows-1251", "UTF-8", $item), $data);

                $array = [
                    'circulationID' => $data[0],
                    'catalogueRecordID' => $data[1],
                    'barcode' => $data[2],
                    'startDate' => $data[3],
                    'finishDate' => $data[4],
                    'readerID' => $data[5],
                    'bookpointID' => $data[6],
                    'state' => $data[7],
                ];
                $states[$array['state']] = isset($states[$array['state']]) ? ++$states[$array['state']] : 1;


                $bookIDS[$array['catalogueRecordID']] = true;


            }

            fclose($handle);

            $bookIDS = array_chunk($bookIDS, 20000, preserve_keys: true);

        }
        $bookIDS = $bookIDS[$part];


        $cnt = count($bookIDS);
        $i = 1;
        foreach ($bookIDS as $bookID => $_) {

            $mosRUData = @file_get_contents('https://www.mos.ru/aisearch/abis_frontapi/v2/book/?id='.$bookID);
            $mosRUData = json_decode($mosRUData, true);

            if (!$mosRUData) {
                echo "ERROR BOOK# $bookID".PHP_EOL;
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
                'isbn' => $isbn,
            ];

            Book::upsert($data, ['id']);

            $this->syncLibraries($mosRUData, $bookID);
            echo $i.' / '.$cnt.' '.$mosRUData['bookInfo']['title'].PHP_EOL;
            $i++;
        }


    }

    public function syncLibraries(array $data, $bookID)
    {

        $book = Book::find($bookID);

        foreach ($data['libraries'] as $library) {

            $data = [
                'id' => $library['id'],
                'title' => $library['title'],
                'address' => $library['address'],
                'phone' => $library['phone'],
                'lat' => $library['coords']['lat'] ?? null,
                'lng' => $library['coords']['lon'] ?? null,
            ];

            Library::upsert($data, ['id']);

            $book->library()->syncWithoutDetaching([$library['id'] => ['count' => $library['copies_count'], 'available' => $library['available']]]);

        }


    }


}
