<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\JsonSourceController;
use App\Http\Controllers\Api\SertifikatController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// JSON Source routes
Route::get('/json-sources', [JsonSourceController::class, 'index']);
Route::post('/json-sources', [JsonSourceController::class, 'store']);
Route::delete('/json-sources/{jsonSource}', [JsonSourceController::class, 'destroy']);
Route::get('/json-sources/{id}/fetch', [JsonSourceController::class, 'fetchData']);
Route::get('/json-sources/fetch-all', [JsonSourceController::class, 'fetchAll']);

// Sertifikat routes
Route::get('/sertifikat/count', [SertifikatController::class, 'getHistoryCount']);
Route::get('/sertifikat', [SertifikatController::class, 'index']);
Route::post('/sertifikat', [SertifikatController::class, 'store']);
Route::get('/sertifikat/{sertifikat}', [SertifikatController::class, 'show']);
Route::delete('/sertifikat/{sertifikat}', [SertifikatController::class, 'destroy']);
