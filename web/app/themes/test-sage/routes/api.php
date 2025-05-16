<?php

use App\Http\Controllers\LikeController;
use App\Http\Controllers\SearchController;
use App\Http\Middleware\UserIsAuthenticated;
use Illuminate\Support\Facades\Route;


Route::apiResource('/search', SearchController::class)->only('index');
Route::group(['middleware' => [UserIsAuthenticated::class]], function () {
    Route::apiResource('/like', LikeController::class)->only(['store', 'destroy']);
});
