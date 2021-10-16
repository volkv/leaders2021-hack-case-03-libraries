<?php

namespace App\Http\Controllers\API;


use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function getRecomendations($userID)
    {

        $books = \DB::select("select b.id, b.title, count(*) cnt, prcnt from user_book_histories final inner join books b on b.id = final.book_id inner join (

select ubh.user_id,

        (count(*)   /   (select count(*) from user_book_histories where user_id = ubh.user_id)::float)  prcnt
from user_book_histories h
         inner join user_book_histories ubh on ubh.book_id = h.book_id

where h.user_id = $userID

group by ubh.user_id
having ubh.user_id != $userID and (count(*) / (select count(*) from user_book_histories where user_id = ubh.user_id)::float) != 1
order by prcnt desc
limit 20) best on best.user_id = final.user_id

where book_id not in (select book_id from user_book_histories where user_id = $userID)
group by b.id, b.title, prcnt
order by cnt desc, prcnt desc");


        $json = [];

        foreach ($books as $book) {
            $json[] = [
                'id' => $book->id,
                'title' => $book->title,
                'cnt' => $book->cnt,
                'prcnt' => $book->prcnt,
            ];
        }

        return response()->json($json);

    }

}
