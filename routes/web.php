<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
require __DIR__.'/auth.php';

Route::get('/', [Controller::class, 'index']);
Route::get('/test', [Controller::class, 'test']);


