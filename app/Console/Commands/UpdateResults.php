<?php

namespace App\Console\Commands;

use App\Helpers\BookHelperService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateResults extends Command
{
    protected $signature = 'c:results';

    public function handle()
    {


        foreach (DB::select('select distinct user_id from user_book_histories where user_id < 104') as $user) {
            $userID = $user->user_id;


            $books = BookHelperService::getRecommendationsForUserID($userID, limit: 5);

            $insert = ['user_id' => $userID];
            foreach ($books as $i => $book) {

                $insert["book_id_".$i + 1] = $book['id'];
            }


            DB::table('results')->upsert($insert,['user_id'],['book_id_1','book_id_2','book_id_3','book_id_4','book_id_5']);

            echo '|';
        }

    }


}
