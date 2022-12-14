<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $nro_filas = $request->rows?$request->rows:10;
        // listado con paginación
        $productos = Producto::with('categoria')->orderBy('id', 'desc')->paginate($nro_filas);
        return response()->json($productos, 200);
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
            "nombre" => "required",
            "categoria_id" => "required"
        ]);
        // guardar
        $prod = new Producto();
        $prod->nombre = $request->nombre;
        $prod->precio = $request->precio;
        $prod->stock = $request->stock;
        $prod->descripcion = $request->descripcion;
        $prod->categoria_id = $request->categoria_id;
        $prod->save();
        // retornar
        return response()->json(["mensaje" => "producto Registrado"], 201);
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

    public function subirImagen(Request $request, $id)
    {
        if($file = $request->file("imagen")){
            $direccion_archivo = time() ."-". $file->getClientOriginalName();
            $file->move("imagenes/", $direccion_archivo);

            $nombre_archivo = "imagenes/". $direccion_archivo;

            $producto = Producto::find($id);
            $producto->imagen = $nombre_archivo;
            $producto->update();

            return response()->json(["mensaje" => "Imagen Actualizada"], 201);
        }
        return response()->json(["mensaje" => "Se requiere imagen"]);
    }
}
