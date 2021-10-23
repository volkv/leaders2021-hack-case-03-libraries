<?php

use App\Http\Controllers\API\Controller;
use App\Http\Controllers\API\SearchController;

Route::get('/v1/recs_for_user_id/{userID}', [Controller::class, 'getRecommendations']);
Route::get('/v1/neighbours_for_user_id/{userID}', [Controller::class, 'getNeighbours']);
Route::get('/v1/search', [SearchController::class, 'search']);
