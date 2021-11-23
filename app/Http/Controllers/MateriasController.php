<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MateriasController extends Controller
{
    public function show_materias($matricula)
    {
        return response()->json([
            'msg' => 'Mostrando materias a docente',
            'usuario' => $matricula
        ], 200);
    }

    public function alta_materia($matricula, Request $request)
    {
        $materia = $request['materia'];

        return response()->json([
            'msg' => 'Dando de alta materia',
            'usuario' => $matricula,
            'materia' => $materia
        ], 200);
    }
    
    public function baja_materia($matricula, Request $request)
    {
        $materia = $request['materia'];

        return response()->json([
            'msg' => 'Dando de baja materia',
            'usuario' => $matricula,
            'materia' => $materia
        ], 200);
    }
    
}
