<?php

namespace App\Console\Commands;


use App\Helpers\BookHelper;
use App\Models\Book;
use App\Models\BookUnique;
use Illuminate\Console\Command;
use JsonMachine\JsonMachine;

class Test extends Command
{
    protected $signature = 'c:test';

    public function handle()
    {



        foreach (BookUnique::where('title','like','%гарри поттер%')->get() as $item){
         dump(BookHelper::cleanTitle($item->title));
        }


    }


}
