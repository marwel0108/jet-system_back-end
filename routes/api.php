<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
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

Route::post('login/', [AuthController::class, 'login']);

Route::prefix('profile')->group(function () {
    Route::get('/{id}', );
    Route::get('/horario/{id}', );
    Route::get('/horarios/{id}', );
    Route::get('/grupos/{id}', );
    Route::get('/materias/{id}', );
    Route::get('/materias/alta_materia/{id}', );
    Route::get('/materias/baja_materia/{id}', );
});