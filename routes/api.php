<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProductoController;
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

    Route::get('/cliente/buscar', [ClienteController::class, "buscarCliente"]);
    // actualizar imagen
    Route::post("/producto/{id}/subir-imagen", [ProductoController::class, "subirImagen"]);
    
    Route::apiResource("usuario", UsuarioController::class);
    Route::apiResource("categoria", CategoriaController::class);
    Route::apiResource("producto", ProductoController::class);
    Route::apiResource("pedido", PedidoController::class);
    Route::apiResource("cliente", ClienteController::class);

});

Route::get("/no-permitido", function(){
    return response()->json(["mensaje" => "No Authorizado"]);
})->name('login');