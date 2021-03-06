<?php

namespace App\Helpers;

use App\Models\BookUnique;
use DB;
use Illuminate\Support\Collection;

class BookHelperService
{

    const NEIGHBOUR_MIN_BOOK_COUNT = 5;
    const MAX_NEIGHBOURS_COUNT = 30;

    static function getNeighboursForUserID(int $userID): array
    {

        $neighbours = DB::select(self::buildNeighboursSQL($userID));

        $userBooks = self::getUserBookIDS($userID);

        foreach ($neighbours as &$neighbour) {
            $neighbour = (array) $neighbour;
            $neighbourBooks = self::getUserBookIDS($neighbour['user_id']);

            $common = array_intersect($neighbourBooks, $userBooks);
            $diff = array_diff($neighbourBooks, $userBooks);


            $neighbour['common'] = BookUnique::whereIn('id', $common)->limit(5)->get()->toArray();
            $neighbour['diff'] = BookUnique::whereIn('id', $diff)->limit(5)->get()->toArray();
        }

        return $neighbours;

    }

    static function buildNeighboursSQL(int $userID): string
    {
        return "select sosedi_history.user_id,
              (select count(*) from user_book_histories where user_id = sosedi_history.user_id) all_count,
             count(*)                                                                          common_cnt,

             (count(*)::float  /  (select count(*) from user_book_histories where user_id = sosedi_history.user_id)::float) * 100 + (count(*) / (select count(*)::float from user_book_histories where user_id = $userID)::float * 100)   factor

      from user_book_histories user_history
               inner join user_book_histories sosedi_history on sosedi_history.book_id = user_history.book_id
      where user_history.user_id = $userID
        and sosedi_history.user_id != $userID and (select count(*) from user_book_histories where user_id = sosedi_history.user_id) > ".self::NEIGHBOUR_MIN_BOOK_COUNT."
           and (select count(*) from user_book_histories where user_id = sosedi_history.user_id)
          < ((select count(*) from user_book_histories where user_id = $userID)*3)

      group by sosedi_history.user_id
      having (select count(*) from user_book_histories where user_id = sosedi_history.user_id) != count(*)
      order by factor desc
      limit ".self::MAX_NEIGHBOURS_COUNT;
    }

    static function getUserBookIDS(int $userID): array
    {
        return DB::table('user_book_histories')->where('user_id', $userID)->pluck('book_id', 'book_id')->toArray();
    }

    static function getRecommendationsForUserID(int $userID, int $limit = 10): array
    {

        $bookData = DB::select("select books.id, (select count(*) from user_book_histories where user_book_histories.book_id = books.id) book_popularity,
       ROUND(CAST(avg(factor) * count(*) AS numeric),2) score,  count(*) neighbours_cnt
from user_book_histories common_books
         inner join
     (".self::buildNeighboursSQL($userID).") sosedi
     on common_books.user_id = sosedi.user_id
         inner join book_uniques books on books.id = common_books.book_id
where common_books.book_id not in (select ubh.book_id from user_book_histories ubh where user_id = $userID) and books.is_book_jsn = true
group by books.id, books.title, books.cover_url
order by score desc, book_popularity, books.id
limit $limit");

        $bookModels = BookUnique::whereIn('id', array_column($bookData, 'id'))->get();
        $bookModels = $bookModels->pluck([], 'id')->toArray();

        foreach ($bookData as &$bookDatum) {

            $bookDatum = (array) $bookDatum;

            $modelData = (array) $bookModels[$bookDatum['id']];
            unset($bookDatum['id']);
            $bookDatum = array_merge($modelData, $bookDatum);

        }

        return $bookData;
    }

    static function getUserHistory(int $userID): Collection
    {
        return BookUnique::whereIn('id', \DB::table('user_book_histories')->where('user_id', $userID)->pluck('book_id'))->get();
    }
}
