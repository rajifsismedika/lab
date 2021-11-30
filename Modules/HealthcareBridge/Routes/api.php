<?php

use Illuminate\Http\Request;
use Modules\HealthcareBridge\Http\Controllers\Api\MaterialRequestController;
use Modules\HealthcareBridge\Http\Controllers\MaterialMutationController;

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
    Route::post('material-requests/all', [MaterialRequestController::class, 'index']);
    Route::post('material-requests', [MaterialRequestController::class, 'store']);
    Route::post('material-requests/items', [MaterialRequestController::class, 'storeItem']);
    Route::post('material-requests/multi-items', [MaterialRequestController::class, 'storeItems']);
    Route::post('material-requests/to/{kode_rs}', [MaterialRequestController::class, 'toRs']);
    Route::post('material-requests/to/{kode_rs}/{external_id}', [MaterialRequestController::class, 'show']);

    Route::post('material-mutations', [MaterialMutationController::class, 'store']);
    Route::post('material-mutations/multi-items', [MaterialMutationController::class, 'storeItems']);
    Route::post('material-mutations/to/{kode_rs}', [MaterialMutationController::class, 'toRs']);
});