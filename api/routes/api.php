<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserWeatherApiController;

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

Route::controller(UserWeatherApiController::class)
    ->prefix('api/v1')
    ->group(function () {
        Route::get(uri: '/users', action: 'index');
        Route::get(uri: '/users/{user}', action: 'show');
    });
