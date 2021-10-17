<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BookUnique;
use Illuminate\Http\Request;


class SearchController extends Controller
{


    public function search(Request $request)
    {
        $request->validate([
            'search' => 'string',
            'limit' => 'nullable|int'
        ]);

        $search = strtolower($request->post('search'));

        $results = [];
        foreach (BookUnique::search($search)->get() as $book) {
            $results[] = $book->attributesToArray();
        }


        return response()->json(['results' => $results]);
    }

}
