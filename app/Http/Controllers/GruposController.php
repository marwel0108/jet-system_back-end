<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alumnos;
use App\Models\Carreras;
use App\Models\Grupos;
use App\Models\User;

class GruposController extends Controller
{
    public function mostrar_grupos($id)
    {
        $user = User::where('id', $id)->first();

        if (!$user) return response()->json([
            'msg' => 'No existe'
        ]);

        if ($user_type = $user['tipo_usuario'] == 1) {
            $isw = array();
            $ilt = array();
            $im = array();

            $carreras = Carreras::all();
            $grupos = Grupos::all();

            // SELECT a.matricula, u.nombre, u.apellido, u.email
            // FROM alumnos a
            // INNER JOIN users u ON a.matricula = u.id
            // WHERE a.id_grupo = 3 AND a.id_carrera = 1; 

            // TODO: Create arrays for each carreer and group


            foreach ($carreras as $carrera) {
                foreach ($grupos as $grupo) {
                    $alumno = Alumnos::join('users AS u', 'alumnos.matricula', '=', 'u.id')
                                        ->where('alumnos.id_grupo', $grupo->id)
                                        ->where('alumnos.id_carrera', $carrera->id)
                                        ->get(['alumnos.matricula', 'u.nombre', 'u.apellido', 'u.email']);

                    if ($carrera->id == 1) {
                        array_push($isw, $alumno);
                    } elseif ($carrera->id == 2) {
                        array_push($ilt, $alumno);
                    } else {
                        array_push($im, $alumno);
                    }
                }
            }
            return response()->json([
                'isw' => $isw,
                'ilt' => $ilt,
                'im' => $im
            ], 200);
        }

        return response()->json([
            
        ], 200);
    }
}
