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

Route::post('login/', [AuthController::class, 'login']);

Route::get('profile/{id}', [UsuarioController::class, 'mostrar_datos_usuario']);
Route::get('alumno/materias/{id}', [MateriasController::class, 'mostrar_materias']);
Route::get('materias', [MateriasController::class, 'mostrar_todas_materias']);
Route::get('grupos/{id}', [GruposController::class, 'mostrar_grupos']);

// TODO: Implent routes for subscribe and unsubscribe

Route::post('alumno/dar_alta/{id}', [MateriasController::class, 'dar_alta']);
Route::post('alumno/dar_baja/{id}', [MateriasController::class, 'dar_baja']);
