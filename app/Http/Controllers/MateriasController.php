<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alumnos;
use App\Models\User;
use App\Models\Materias;
use App\Models\MateriasCursadas;

class MateriasController extends Controller
{
    public function mostrar_todas_materias()
    {
        $isw = array([], [], []);
        $ilt = array([], [], []);
        $im = array([], [], []);
        $materias = Materias::join('carreras AS c', 'materias.id_carrera', '=', 'c.id')
            ->get(['materias.id_carrera', 'materias.nombre', 'c.nombre AS nombre_carrera', 'materias.ciclo']);
        foreach ($materias as $materia) {
            if ($materia->id_carrera == 1) {
                array_push($isw[$materia->ciclo - 1], $materia);
            } elseif ($materia->id_carrera == 2) {
                array_push($ilt[$materia->ciclo - 1], $materia);
            } else {
                array_push($im[$materia->ciclo - 1], $materia);
            }
        }
        return response()->json([
            'isw' => $isw,
            'ilt' => $ilt,
            'im' => $im
        ], 200);
    }
}