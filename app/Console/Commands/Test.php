<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Test extends Command
{
    protected $signature = 'c:test';

    public function handle()
    {

  $usersForTest = \DB::select('select user_id, count(*) cnt from user_book_histories where user_id < 105
group by user_id having count(*) > 50');



    }


}
