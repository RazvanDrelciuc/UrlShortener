<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UrlController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/{hash}', [UrlController::class, 'redirect'])->where('hash', '[a-zA-Z0-9]{6}');
