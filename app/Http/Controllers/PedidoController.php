<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validar

        $request->validate([
            "cliente_id" => "required"
        ]);
        DB::beginTransaction();
        try {

            // crear pedido
            $pedido = new Pedido();
            $pedido->fecha_pedido = Carbon::now();
            $pedido->cliente_id = $request->cliente_id;
            $pedido->save();

            // asignacion de productos al pedido
            /*
            [
                {producto_id: 1, cantidad: 1},
                {producto_id: 40, cantidad: 2},
                {producto_id: 6, cantidad: 1},
            ]
            */
            foreach ($request->productos as $producto) {
                $pedido->productos()->attach($producto["producto_id"], ["cantidad" => $producto["cantidad"]]);
            }
            
            // actualizar el estado del pedido
            $pedido->estado = 2;
            $pedido->update();

            DB::commit();
            return response()->json(["mensaje" => "Pedido Registrado"], 201);
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return response()->json(["mensaje" => "Ocurrio un problema al registrar el pedido", "error" => $e], 422);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
