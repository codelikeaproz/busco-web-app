<?php

use App\Http\Controllers\Api\CareerApiController;
use App\Http\Controllers\Api\HomeApiController;
use App\Http\Controllers\Api\NewsApiController;
use App\Http\Controllers\Api\QuedanApiController;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:60,1')->group(function (): void {
    Route::get('/home', HomeApiController::class);
    Route::get('/news', [NewsApiController::class, 'index']);
    Route::get('/news/{news}', [NewsApiController::class, 'show']);
    Route::get('/quedan', [QuedanApiController::class, 'index']);
    Route::get('/careers', [CareerApiController::class, 'index']);
    Route::get('/careers/{jobOpening}', [CareerApiController::class, 'show']);
});
