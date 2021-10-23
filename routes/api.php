<?php

use App\Http\Controllers\API\Controller;
use App\Http\Controllers\API\SearchController;

Route::get('/v1/rec/{userID}', [Controller::class, 'getRecommendations']);
Route::get('/v1/search', [SearchController::class, 'search']);
