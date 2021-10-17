<?php

namespace App\Helpers;

class BookHelper
{


    static function getRecommendationsForUserID(int $userID) : array {

        return \DB::select("select  books.title, books.id, avg(factor) * count(*) avg_factor,  count(*) cnt
from user_book_histories common_books
         inner join

     (select sosedi_history.user_id,
             (select count(*) from user_book_histories where user_id = $userID) user_cnt,  (select count(*) from user_book_histories where user_id = sosedi_history.user_id) all_count,
             count(*)                                                                          common_cnt,

             (count(*)::float  /  (select count(*) from user_book_histories where user_id = sosedi_history.user_id)::float) * 100 + (count(*) / (select count(*)::float from user_book_histories where user_id = $userID)::float * 100)   factor

      from user_book_histories user_history
               inner join user_book_histories sosedi_history on sosedi_history.book_id = user_history.book_id
      where user_history.user_id = $userID
        and sosedi_history.user_id != $userID and (select count(*) from user_book_histories where user_id = sosedi_history.user_id) > 10
        and (select count(*) from user_book_histories where user_id = sosedi_history.user_id)
          < ((select count(*) from user_book_histories where user_id = $userID)*3)

      group by sosedi_history.user_id
      having (select count(*) from user_book_histories where user_id = sosedi_history.user_id) != count(*)
      order by factor desc
      limit 10) sosedi on common_books.user_id = sosedi.user_id

         inner join book_uniques books on books.id = common_books.book_id
where book_id not in (select book_id from user_book_histories where user_id = $userID)
group by books.id, books.title
order by avg_factor desc, cnt, books.id
limit 5
");


    }

    static function cleanTitle(string $title){


        $title = mb_strtolower($title);

        $title = str_replace("рассказы", "", $title);
        $title = str_replace("рассказ", "", $title);
        $title = str_replace("фантастич повесть", "", $title);
        $title = str_replace("повесть", "", $title);
        $title = str_replace("повести", "", $title);
        $title = str_replace("книга для чтения на англ яз", "", $title);
        $title = str_replace("романы", "", $title);
        $title = str_replace("роман", "", $title);
        $title = str_replace("перевод с английского", "", $title);
        $title = str_replace("пер. с англ.", "", $title);
        $title = str_replace("графический роман", "", $title);
        $title = str_replace("комикс", "", $title);
        $title = str_replace("сборник", "", $title);
        $title = str_replace("сказкапритча", "", $title);
        $title = str_replace("сказка", "", $title);
        $title = str_replace("сказки", "", $title);
        $title = str_replace("пер с фр", "", $title);
        $title = str_replace("-", "", $title);
        $title = str_replace("аудиокнига", "", $title);
        $title = str_replace("фантастические", "", $title);
        $title = str_replace("для среднего школьного возраста", "", $title);
        $title = str_replace("для старшего школьного возраста", "", $title);
        $title = str_replace("перевод с французского", "", $title);

        $title = preg_replace("/\([^)]+\)/","",$title);

        $title= preg_replace('~[^\p{Cyrillic}a-z0-9_\s-]+~ui', '', $title);
        $title = preg_replace('/\s\s+/', ' ', $title);

        $title = trim($title, " ");


        return $title;
    }
}
