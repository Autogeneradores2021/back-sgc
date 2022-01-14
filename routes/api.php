<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WizardController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\TrackingController;

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

Route::post('users/create', [UserController::class, 'create']);

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    # auth
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);

});

Route::group([

    'middleware' => 'api',
    'prefix' => 'request'

], function ($router) {
    # request
    Route::post('/create', [RequestController::class, 'create']);
    Route::get('', [RequestController::class, 'index']);

});


Route::group([

    'middleware' => 'api',
    'prefix' => 'tracking'

], function ($router) {
    Route::get('', [TrackingController::class, 'index']);
    Route::post('create', [TrackingController::class, 'create']);

});

Route::group([

    'middleware' => 'api',
    'prefix' => 'wizard'

], function ($router) {
    Route::post('{module}', [WizardController::class, 'index']);
    Route::post('{module}/complete/{step}', [WizardController::class, 'complete']);
    Route::post('{module}/show/{step}', [WizardController::class, 'show']);

});