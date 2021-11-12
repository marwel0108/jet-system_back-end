<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $matricula = $request['matricula'];
        $passwd = $request['passwd'];

        return response()->json([
            'Matricula' => $matricula,
        ], 200);
    }
}
