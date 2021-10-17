<?php

namespace App\Http\Controllers\API;


use App\Helpers\BookHelper;
use Database\Seeders\Helper;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function getRecomendations($userID)
    {


          $recommendations = BookHelper::getRecommendationsForUserID($userID);
        $json = [];

        foreach ($recommendations as $book) {
            $json[] = [
                'id' => $book->id,
                'title' => $book->title,
                'cnt' => $book->cnt,
                'factor' => $book->avg_factor,
            ];
        }

        return response()->json($json);

    }


}
