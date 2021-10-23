<?php

namespace App\Console\Commands;

use App\Models\Book;
use Illuminate\Console\Command;

class Test extends Command
{
    protected $signature = 'c:test';

    public function handle()
    {

        $booksCounts = [];
        foreach (range(1, 16) as $id) {
            echo "Circulation: $id".PHP_EOL;


            $row = 1;
            if (($handle = fopen(storage_path("datasets_biblioteki/datasets_2/circulaton_$id.csv"), "r")) !== false) {
                while (($data = fgetcsv($handle, separator: ';')) !== false) {
                    $row++;

                    if ($row == 2) {
                        continue;
                    }

                    $data = array_map(fn($item) => iconv("Windows-1251", "UTF-8", $item), $data);

                    if (isset($booksCounts[$data[1]])) {
                        $booksCounts[$data[1]]++;
                    } else {
                        $booksCounts[$data[1]] = 1;
                    }

                }

            }

            fclose($handle);

        }

        $booksUpserts = array_map(fn($key, $value) => ['id' => $value, 'count' => $key], $booksCounts, array_keys($booksCounts));
        foreach (array_chunk($booksUpserts,5000) as $chunk){
            Book::upsert($chunk, ['id'], ['count']);
            echo '|';
        }

    }


}
