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

Route::get('/{id}', [UsuarioController::class, 'mostrar_datos_usuario']);
Route::get('/materias/{id}', [MateriasController::class, 'mostrar_materias']);
Route::get('/grupos/{id}', [GruposController::class, 'mostrar_grupos']);

// TODO: Implent routes for subscribe and unsubscribe

Route::prefix('profile')->group(function () {
    Route::get('/{matricula}', [UsuarioController::class, 'show']);
    Route::get('/horario/{matricula}', [HorariosController::class, 'horario']);
    Route::get('/horarios/{matricula}', [HorariosController::class, 'horarios']);
    Route::get('/grupos/{matricula}', [GruposController::class, 'show_grupos']);
    Route::get('/materias/{matricula}', [MateriasController::class, 'show_materias']);
    Route::post('/materias/alta_materia/{matricula}', [MateriasController::class, 'alta_materia']);
    Route::post('/materias/baja_materia/{matricula}', [MateriasController::class, 'baja_materia']);
});