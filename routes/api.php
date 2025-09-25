<?php

use App\Http\Controllers\CampaignController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/pruebasw', [CampaignController::class, 'pruebasw'])->name('pruebasw.index');
Route::get('/pruebass', [CampaignController::class, 'pruebass'])->name('pruebass.index');
Route::get('/pruebasc', [CampaignController::class, 'pruebasc'])->name('pruebasc.index');
