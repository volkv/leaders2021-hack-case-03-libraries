<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function index()
    {


        return view('index');

    }


    public function test()
    {

        $data = file_get_contents(storage_path('datasets_biblioteki/books.jsn'));

        $data = json_decode($data, true);
        dump(count($data));
        foreach ($data as $book) {
            if ($book['parentId'] != 0) {
                dd($book);
            }
        }
        dd(123);
    }

}
