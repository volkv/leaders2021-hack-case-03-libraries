<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function index()
    {
        return view('index');
    }



}
