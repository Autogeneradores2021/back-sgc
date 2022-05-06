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
use App\Http\Controllers\IssuesController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FilterController;

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
    Route::get('update-role/{id}', [UserController::class, 'updateRole']);
    Route::put('/{id}', [UserController::class, 'updateState']);
    Route::get('me', [UserController::class, 'retrive']);
    Route::get('members', [UserController::class, 'getMembers']);
    Route::get('external', [UserController::class, 'getExternal']);
    Route::post('create', [UserController::class, 'create']);
    Route::get('permission/{name}', [UserController::class, 'permission']);
    Route::get('', [UserController::class, 'index']);

});

Route::group([

    'middleware' => 'api',
    'prefix' => 'employee'

], function ($router) {
    Route::get('', [EmployeeController::class, 'index']);

});

Route::group([

    'middleware' => 'api',
    'prefix' => 'filter'

], function ($router) {
    Route::get('', [FilterController::class, 'index']);
    Route::post('', [FilterController::class, 'create']);
    Route::delete('/{id}', [FilterController::class, 'delete']);

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
    Route::get('/is-owner/{id}', [RequestController::class, 'isOwner']);
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
    Route::get('overview/{id}', [ReportController::class, 'overview']);

});

Route::group([

    'middleware' => 'api',
    'prefix' => 'issue'

], function ($router) {
    Route::get('by-request/{id}', [IssuesController::class, 'byRequestId']);

});

