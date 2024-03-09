<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/v1/', [\App\Http\Controllers\API\V1\MainController::class, 'index'])->name('api.index');
Route::get('/v1/token/{token}', [\App\Http\Controllers\API\V1\MainController::class, 'token'])->name('api.token');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
