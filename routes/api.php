<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PlayerController;
use App\Http\Controllers\API\GameController;

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

Route::post('/login', [PlayerController::class, 'login']);
Route::post('/jugar', [GameController::class, 'play']);
Route::get('/estado', [GameController::class, 'status']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
