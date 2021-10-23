<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{


    public function index()
    {
        return view('index');
    }

    public function apiDocs()
    {
        return view('api-docs');
    }


}
