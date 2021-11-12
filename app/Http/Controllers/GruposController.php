<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GruposController extends Controller
{
    public function show_grupos($matricula)
    {
        return response()->json([
            'msg' => 'Mostrando grupos para docente',
            'usuario' => $matricula
        ], 200);
    }
}
