<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';



Route::get('/test', [TestController::class, 'test']);

Route::get('/{path?}', [Controller::class, 'index'])->where('path', '.*')->name('react');
