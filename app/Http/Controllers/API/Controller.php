<?php

namespace App\Http\Controllers\API;


use App\Helpers\BookHelperService;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{


    public function getRecommendations($userID): JsonResponse
    {

        return response()->json(BookHelperService::getRecommendationsForUserID($userID));

    }


    public function getNeighbours($userID): JsonResponse
    {

        return response()->json(BookHelperService::getNeighboursForUserID($userID));

    }


}
