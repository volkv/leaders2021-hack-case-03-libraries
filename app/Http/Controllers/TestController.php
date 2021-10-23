<?php

namespace App\Http\Controllers;

use App\Helpers\BookHelperService;
use App\Models\BookUnique;
use Illuminate\Routing\Controller as BaseController;

class TestController extends BaseController
{



    public function print()
    {


        foreach (\DB::select('select distinct user_id from user_book_histories where user_id < 104') as $user) {
            $userID = $user->user_id;

            $userBooks = \DB::select("select bu.title from user_book_histories inner join book_uniques bu on bu.id = user_book_histories.book_id where user_id = $userID");

            echo '<h1>USER#'.$userID.'</h1>';

            foreach ($userBooks as $book) {
                echo $book->title.'<br>';
            }
            echo '<br><hr><br>';

            foreach (BookHelperService::getRecommendationsForUserID($userID) as $book) {
                echo $book->title.'<br>';;
            }
            echo '<br><hr><br>';
        }
        dd(123);


    }

}
