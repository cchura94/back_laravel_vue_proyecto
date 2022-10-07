<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function ingresar(Request $request)
    {
        // validar
        $credenciales = $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);

        // verificar
        if(!Auth::attempt($credenciales)) {
            return response()->json([
                "mensaje" => "No Autorizado",
                "error" => true
            ], 401);
        }
        
        //generar token sanctum
        $user = $request->user();
        $tokenResult = $user->createToken('Token Personal');
        $token = $tokenResult->plainTextToken;

        //responder
        return response()->json([
            "access_token" => $token,
            "token_type" => "Bearer",
            "usuario" => $user
        ]);
    }

    public function registrar(Request $request)
    {
        // validar
        $request->validate([
            "name" => "required",
            "email" => "required|email|unique:users",
            "password" => "required"
        ]);

        // crear nuevo usuario
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);

        // respuesta        
        if ($user->save()) {
            return response()->json([
                "mensaje" => "Usuario registrado",
                "usuario" => $user,
                "error" => false
            ], 201);
        } else {
            return response()->json([
                "mensaje" => "Error al registrar el usuario",
                "error" => true
            ], 422);
        }
    }

    public function perfil()
    {
        return Auth::user();
    }

    public function logout()
    {
        Auth::user()->tokens()->delete();
        return response()->json(["mensaje" => "Log Out"]);
    }
}
