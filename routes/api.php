<?php

use App\Http\Controllers\AdController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\RatesController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group(array('prefix' => '/v1'), function () {
    Route::get('/getDisplayData', [ApiController ::class, 'getDisplayAds']);
    Route::get('/getExchangeRates', [ApiController::class, 'getExchangeRates']);
});