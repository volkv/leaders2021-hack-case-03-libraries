<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Routing\Controller as BaseController;

class TestController extends BaseController
{

    public function test()
    {


        $row = 1;
        $states = [];
        if (($handle = fopen(storage_path('datasets_biblioteki/datasets_2/siglas.csv'), "r")) !== false) {
            while (($data = fgetcsv($handle, separator: ';')) !== false) {

                $row++;
                if ($row == 2) {
                    continue;
                }


                $data = array_map(fn($item) => iconv("Windows-1251", "UTF-8", $item), $data);
dd($data);

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
              $mosRUdata =  file_get_contents('https://www.mos.ru/aisearch/abis_frontapi/v2/book/?id='.$array['catalogueRecordID']);
                $mosRUdata = json_decode($mosRUdata, true);

          dump($mosRUdata['bookInfo']);
                if ($row > 1000) {
                    break;
                }


            }

            fclose($handle);
            dd($states);
        }

    }

}
