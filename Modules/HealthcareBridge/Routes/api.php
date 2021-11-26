<?php

use Illuminate\Http\Request;
use Modules\HealthcareBridge\Http\Controllers\Api\MaterialRequestController;

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

Route::middleware('auth:api')->get('/healthcarebridge', function (Request $request) {
    return $request->user();
});

Route::middleware(['hisauth'])->group(function () {
    Route::get('material-requests', [MaterialRequestController::class, 'index']);
    Route::post('material-requests', [MaterialRequestController::class, 'store']);
    Route::get('material-requests/{external_id}', [MaterialRequestController::class, 'show']);
    Route::get('material-requests/to/{kode_rs}', [MaterialRequestController::class, 'toRs']);
    Route::post('material-requests/items', [MaterialRequestController::class, 'storeItem']);
});