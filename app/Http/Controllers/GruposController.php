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

            foreach ($carreras as $carrera) {
                $grupos = Grupos::where('id_carrera', $carrera->id)->get(); 
                foreach ($grupos as $grupo) {
                    $alumnos = Alumnos::join('users AS u', 'alumnos.matricula', '=', 'u.id')
                                        ->where('alumnos.id_grupo', $grupo->id)
                                        ->where('alumnos.id_carrera', $carrera->id)
                                        ->get(['alumnos.matricula', 'u.nombre', 'u.apellido']);

                    if ($carrera->id == 1) {
                        array_push($isw, $alumnos);
                    } elseif ($carrera->id == 2) {
                        array_push($ilt, $alumnos);
                    } else {
                        array_push($im, $alumnos);
                    }
                }
            }
            return response()->json([
                'isw' => $isw,
                'ilt' => $ilt,
                'im' => $im
            ], 200);
        }

        $carrera = Alumnos::where('matricula', $id)
                            ->get('id_carrera')->first();

        $grupo = Alumnos::where('matricula', $id)
                            ->get('id_grupo')->first();

        $alumnos = Alumnos::join('users AS u', 'alumnos.matricula', '=', 'u.id')
                        ->where('alumnos.id_grupo', $grupo->id_grupo)
                        ->where('alumnos.id_carrera', $carrera->id_carrera)
                        ->get(['alumnos.matricula', 'u.nombre', 'u.apellido']);

        return response()->json([
            $alumnos
        ], 200);
    }
}
