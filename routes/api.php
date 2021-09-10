<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UsersController;
use App\Http\Controllers\CitiesController;
use App\Http\Controllers\ExchangesController;
use App\Http\Controllers\CountriesController;

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

Route::get('user/exchanges', [UsersController::class, 'user_exchanges']);

Route::get('exchange/search/{key}/{page?}', [ExchangesController::class, 'find']);

Route::get('countries', [CountriesController::class, 'index']);

Route::get('cities', [CitiesController::class, 'index']);

Route::apiResources([
    'user' => UsersController::class,
    'exchange' => ExchangesController::class
]);
