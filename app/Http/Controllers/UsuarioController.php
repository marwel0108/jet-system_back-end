<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Materias;
use App\Models\Carreras;

// TODO: Implement JWT auth
class UsuarioController extends Controller
{
    public function mostrar_datos_usuario($id)
    {
        $user = User::where('id', $id)->first();

        if (!$user) return response()->json([
            'msg' => 'No existe'
        ]);

        if ($user_type = $user['tipo_usuario'] == 1) {
            return response()->json($user, 200);
        }

        $alumno = Carreras::join('grupos AS g', 'g.id_carrera', '=', 'carreras.id')
                            ->join('alumnos AS a', 'a.id_grupo', '=', 'g.id')
                            ->join('users AS u', 'a.matricula', '=', 'u.id')
                            ->where('u.id', $id)
                            ->get(['a.matricula', 'u.nombre as nombre_alumno', 'u.apellido', 'g.nombre AS nombre_grupo', 'carreras.nombre AS nombre_carrera'])
                            ->first();
        $datos_materias = Materias::join('materias_cursadas', 'materias.id', '=', 'materias_cursadas.id_materia')
                                    ->where('materias_cursadas.id_alumno', $id)
                                    ->get(['materias.nombre', 'materias.creditos', 'materias_cursadas.estado']);
        return response()->json([
            "usuario" => $alumno,
            "info" => $datos_materias
        ], 200);


    }
}
