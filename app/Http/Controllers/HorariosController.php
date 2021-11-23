<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HorariosController extends Controller
{
    public function horario($matricula)
    {
        return response()->json([
            'msg' => 'Mostrando horario para alumno',
            'usuario' => $matricula
        ], 200);
    }

    public function horarios($matricula)
    {
        return response()->json([
            'msg' => 'Mostrando horarios de grupos para docente',
            'usuario' => $matricula
        ], 200);
    }
}
