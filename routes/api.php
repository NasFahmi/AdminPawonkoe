<?php

use App\Http\Controllers\HomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
Route::get('/test',function(){
    return response()->json('Test Work');
});

Route::get('/image/index',[HomeController::class,'indexImage']);
Route::get('/katalog',[HomeController::class,'katalog']);
Route::get('/katalog?query={search}',[HomeController::class,'productSearch']);
Route::get('/katalog/{slug}',[HomeController::class,'detailProduct']);