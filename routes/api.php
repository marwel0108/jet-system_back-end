<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\HorariosController;
use App\Http\Controllers\GruposController;
use App\Http\Controllers\MateriasController;

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
    'prefix' => 'auth'
], function ($router) {

    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('profile/{id}', [AuthController::class, 'mostrar_datos_usuario']);
    Route::post('alumno/materias/{id}', [AuthController::class, 'mostrar_materias']);
    Route::post('alumno/dar_baja/{id}', [AuthController::class, 'dar_baja']);
    Route::post('alumno/dar_alta/{id}', [AuthController::class, 'dar_alta']);
    Route::post('grupos/{id}', [AuthController::class, 'mostrar_grupos']);
});

Route::post('materias', [MateriasController::class, 'mostrar_todas_materias']);

// TODO: Implent routes for subscribe and unsubscribe

