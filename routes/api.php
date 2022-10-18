<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// endpont : Auth (login , perfil, logout, registro)
Route::group(["prefix" => "v1/auth"], function(){
    // /api/v1/auth
    Route::post("login", [AuthController::class, "ingresar"]);
    Route::post("registro", [AuthController::class, "registrar"]);

    Route::group(["middleware" => "auth:sanctum"], function(){
        
        Route::get("perfil", [AuthController::class, "perfil"]);
        Route::post("salir", [AuthController::class, "logout"]);

    });
});

// otras rutas
Route::group(["middleware" => "auth:sanctum"], function(){
    
    Route::apiResource("usuario", UsuarioController::class);

});