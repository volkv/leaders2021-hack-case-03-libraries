<?php

namespace App\Http\Controllers\API;


use App\Helpers\BookHelperService;
use App\Models\BookUnique;
use Database\Seeders\Helper;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{


    public function getRecommendations($userID): JsonResponse
    {

        return response()->json(BookHelperService::getRecommendationsForUserID($userID));

    }

    public function getRecsAndHistory($userID): JsonResponse
    {

        return response()->json(
            [
                'recommendations'=> BookHelperService::getRecommendationsForUserID($userID),
                'history'=> BookHelperService::getUserHistory($userID)
                ]
        );

    }


    public function getNeighbours($userID): JsonResponse
    {

        return response()->json(BookHelperService::getNeighboursForUserID($userID));

    }


    public function getHistory($userID): JsonResponse
    {


        return response()->json(BookHelperService::getUserHistory($userID));
    }


}
