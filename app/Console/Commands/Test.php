<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;


class Test extends Command
{

    protected $signature = 'c:test';


    public function handle()
    {

        $data = file_get_contents(storage_path('datasets_biblioteki/books.jsn'));

$data = json_decode($data,true);
    }


}
