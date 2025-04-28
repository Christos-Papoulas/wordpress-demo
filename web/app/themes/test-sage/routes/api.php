<?php

use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;


Route::apiResource('/search', SearchController::class)->only('index');
