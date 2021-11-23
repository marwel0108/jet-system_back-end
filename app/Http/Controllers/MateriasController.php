<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Materias;

class MateriasController extends Controller
{
    public function mostrar_materias($id)
    {
        $user = User::where('id', $id)->first();

        if (!$user) return response()->json([
            'msg' => 'No existe'
        ]);

        if ($user_type = $user['tipo_usuario'] == 1) {
            $isw = array();
            $ilt = array();
            $im = array();

            $materias = Materias::all();

            foreach ($materias as $materia) {
                if ($materia->id_carrera == 1) {
                    array_push($isw, $materia);
                } elseif ($materia->id_carrera == 2) {
                    array_push($ilt, $materia);
                } else {
                    array_push($im, $materia);
                }
            }
            return response()->json([
                'isw' => $isw,
                'ilt' => $ilt,
                'im' => $im
            ], 200);
        }

        $materias = Materias::join('materias_cursadas AS mc', 'materias.id', '=', 'mc.id_materia')
                            ->where('mc.id_alumno', $id)
                            ->where('mc.estado', '<>', 3)
                            ->get(['materias.nombre', 'materias.creditos', 'mc.estado']);

        return response()->json($materias, 200);
    }
}
