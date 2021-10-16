<?php

namespace App\Console\Commands;

use App\Models\Author;
use App\Models\BookUnique;
use App\Models\Library;
use App\Models\Rubric;
use Illuminate\Console\Command;


class BookFromGoogleAPI extends Command
{

    protected $signature = 'c:google';


    public function handle()
    {


    }


    public function getBookData($q) : array
    {

        $data =file_get_contents("https://www.googleapis.com/books/v1/volumes?q=$q&key=AIzaSyCWb3ereD5wxedOImo9CFmkQ5T0GU3ezRg");
        return json_decode($data, true);

    }


}
