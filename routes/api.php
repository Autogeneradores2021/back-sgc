<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WizardController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\SelectableController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\ReportController;

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


Route::group([

    'middleware' => 'api',
    'prefix' => 'user'

], function ($router) {
    Route::get('me', [UserController::class, 'retrive']);
    Route::post('create', [UserController::class, 'create']);
    Route::get('', [UserController::class, 'index']);

});
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
    'prefix' => 'selectable'

], function ($router) {
    # request
    Route::delete('/{table}/delete/{code}', [SelectableController::class, 'delete']);
    Route::post('/{table}/create', [SelectableController::class, 'create']);
    Route::get('/{table}', [SelectableController::class, 'index']);

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
    Route::get('{module}/retrive/{step}', [WizardController::class, 'retrive']);
    Route::post('{module}/show/{step}', [WizardController::class, 'show']);

});


Route::group([

    'middleware' => 'api',
    'prefix' => 'report'

], function ($router) {
    Route::get('dashboard', [ReportController::class, 'dashboard']);

});