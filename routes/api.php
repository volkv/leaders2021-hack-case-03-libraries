<?php

use App\Http\Controllers\API\Controller;

Route::get('/v1/rec/{userID}', [Controller::class, 'getRecomendations']);
