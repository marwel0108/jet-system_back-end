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

    public function dar_alta(Request $req, $id)
    {
        $user = User::where('id', $id)->first();

        if (!$user) return response()->json([
            'msg' => 'No existe alumno'
        ]);

        $bodyContent = $req->all();

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

    public function dar_baja(Request $req, $id)
    {
        $user = User::where('id', $id)->first();

        if (!$user) return response()->json([
            'msg' => 'No existe alumno'
        ]);

        $bodyContent = $req->all();

        $creditos_carrera = Materias::where('id', $bodyContent['materia'])
            ->get('creditos')
            ->first();

        $creditos = Alumnos::where('matricula', $id)
            ->get('creditos')
            ->first();

        Alumnos::where('matricula', $id)
            ->update(['creditos' => $creditos->creditos - $creditos_carrera->creditos]);

        Materias::where('id', $bodyContent['materia'])
            ->update(['alumnos_registrados' => $creditos->creditos - 1]);

        return response()->json([
            'msg' => 'Materia dada de baja satisfactoriamente'
        ]);
    }
}
