<?php

namespace App\Http\Controllers\API;


use App\Helpers\BookHelperService;
use App\Models\BookUnique;
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


    public function getHistory($userID): JsonResponse
    {

        $books = BookUnique::whereIn('id', \DB::table('user_book_histories')->where('user_id', $userID)->pluck('book_id'))->get();
        return response()->json($books);
    }


}
