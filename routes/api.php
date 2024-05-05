<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\temp_images_Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
// Route::post('/temp-image', [temp_images_Controller::class, 'store']);


Route::post('/added', [BlogController::class, 'store']);
Route::get('/getBlog', [BlogController::class, 'index']);
Route::get('/getBlog/{id}', [BlogController::class, 'show']);
Route::put('/update/{id}',[BlogController::class,'update']);
Route::delete('/delete/{id}',[BlogController::class,'destroy']);
