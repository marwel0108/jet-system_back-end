<?php

namespace App\Http\Controllers;

use App\Models\Alumnos;
use App\Models\Carreras;
use App\Models\Grupos;
use App\Models\Materias;
use App\Models\MateriasCursadas;
use App\Models\User;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => 'login']);
    }

    public function login()
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json([
                'error' => 'No autorizado'
            ], 401);
        }

        return $this->respondWithToken($token);
    }

    public function logout()
    {
        auth()->logout();

        return response()->json([
            'msg' => 'Sesión cerrada'
        ], 200);
    }

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

    public function mostrar_materias($id)
    {
        $user = User::where('id', $id)->first();

        if (!$user) return response()->json([
            'msg' => 'No existe alumno'
        ]);

        $materias = Materias::join('materias_cursadas AS mc', 'materias.id', '=', 'mc.id_materia')
            ->where('mc.id_alumno', $id)
            ->where('mc.estado', '<>', 3)
            ->get(['materias.nombre', 'materias.creditos', 'mc.estado']);

        return response()->json($materias, 200);
    }

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

    public function dar_alta($id)
    {
        $user = User::where('id', $id)->first();

        if (!$user) return response()->json([
            'msg' => 'No existe alumno'
        ]);

        $bodyContent = request(['materia']);

        $materia_prev = Materias::where('id', $bodyContent['materia'])
            ->get('id_materia_previa')
            ->first();

        $estado = MateriasCursadas::join('materias AS m', 'materias_cursadas.id_materia', '=', 'm.id_materia_previa')
            ->where('materias_cursadas.id_alumno', $id)
            ->where('materias_cursadas.id_materia', $materia_prev->id_materia_previa)
            ->get(['materias_cursadas.estado', 'm.nombre'])
            ->first();

        if ($estado->estado == 4) {
            return response()->json([
                'Error' => 'La materia previa está reprobada'
            ]);
        }

        $limite = Materias::where('id', $bodyContent['materia'])
            ->get(['alumnos_registrados', 'creditos'])
            ->first();

        if ($limite->alumnos_registrados == 30) {
            return response()->json([
                'Error' => 'El cupo de la materia ha sido alcanzado, lo sentimos'
            ]);
        }

        Materias::where('id', $bodyContent['materia'])
            ->update(['alumnos_registrados' => $limite->alumnos_registrados + 1]);

        MateriasCursadas::where('id_alumno', $id)
            ->where('id_materia', $bodyContent['materia'])
            ->update(['estado' => 2]);

        $creditos = Alumnos::where('matricula', $id)
            ->get('creditos')
            ->first();

        Alumnos::where('matricula', $id)
            ->update(['creditos' => $creditos->creditos + $limite->creditos]);



        return response()->json([
            'msg' => 'Materia dada de alta satisfactoriamente'
        ]);
    }

    public function dar_baja($id)
    {
        $user = User::where('id', $id)->first();

        if (!$user) return response()->json([
            'msg' => 'No existe alumno'
        ]);

        $bodyContent = request(['materia']);

        $carrera = Materias::where('id', $bodyContent['materia'])
            ->get(['alumnos_registrados', 'creditos'])
            ->first();

        $creditos = Alumnos::where('matricula', $id)
            ->get('creditos')
            ->first();

        Alumnos::where('matricula', $id)
            ->update(['creditos' => $creditos->creditos - $carrera->creditos]);

        Materias::where('id', $bodyContent['materia'])
            ->update(['alumnos_registrados' => $carrera->alumnos_registrados - 1]);

        MateriasCursadas::where('id_materia', $bodyContent['materia'])
            ->where('id_alumno', $id)
            ->update(['estado' => 1]);

        return response()->json([
            'msg' => 'Materia dada de baja satisfactoriamente',
        ]);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ], 200);
    }
}
