<?php

use Illuminate\Support\Facades\Route;
/*
Route::get('/', function () {
    return view('welcome');
});
*/
/*
Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');
*/
Route::get('/{any}', function () {
    if (File::exists(public_path($_SERVER["REQUEST_URI"]))) {
        return File::get(public_path($_SERVER["REQUEST_URI"]));
    }
    return view('welcome'); 
})->where('any', '.*');