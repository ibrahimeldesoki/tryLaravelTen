<?php


use App\Http\Controllers\APIs\Workers\WorkerClockController;
use App\Http\Controllers\APIs\Workers\WorkerController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/worker/store', [WorkerController::class, 'store']);
Route::post('/worker/login', [WorkerController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('worker/clock-in' , [WorkerClockController::class, 'store']);
});
