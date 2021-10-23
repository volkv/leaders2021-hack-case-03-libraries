<?php

namespace App\Http\Controllers\API;


use App\Helpers\BookHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function getRecommendations($userID): JsonResponse
    {


        $recommendations = BookHelper::getRecommendationsForUserID($userID);
        $json = [];

        foreach ($recommendations as $book) {

            $json[] = (array)$book;
        }

        return response()->json($json);

    }


}
