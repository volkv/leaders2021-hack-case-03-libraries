<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function index()
    {



        $data = file_get_contents(storage_path('datasets_biblioteki/books.jsn'));

        $data = json_decode($data,true);
        dump(count($data));
      foreach ($data as $book){
          dd($book);
      }
         return view('index');

    }



}
