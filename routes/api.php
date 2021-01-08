<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\PollingController;

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
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

$prefix = 'v1';
/**
 *
 * All Api without Authentication
 *
 */


Route::group(['prefix' => $prefix], function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::group(['middleware' => ['auth:api'], 'prefix' => $prefix], function () {
    Route::post('/create-polling', [PollingController::class, 'createPolling']);
    Route::get('/polling-details/{id}', [PollingController::class, 'pollingDetails']);
    Route::post('/poll-voting', [PollingController::class, 'pollVoting']);
    Route::get('/polling-results/{id}', [PollingController::class, 'pollingResults']);
    Route::get('/polling-data', [PollingController::class, 'pollingData']);
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

