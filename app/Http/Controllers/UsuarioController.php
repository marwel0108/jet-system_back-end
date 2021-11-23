<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function show()
    {
        return response()->json([
            'msg' => 'Mostrar info del usuario'
        ], 200);
    }
}
