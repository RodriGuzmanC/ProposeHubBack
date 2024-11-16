<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Imagen;
use Illuminate\Support\Facades\DB;

class ImageController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Guardar la imagen en storage
        $path = $request->file('image')->store('public/images');
        $image = $request->file('image');

        // Guardar en la base de datos
        $imagen = new Imagen();
        $nombre = $image->getClientOriginalName();
        //$imagen->user_id = auth()->id();  // Asegúrate de tener autenticación
        $imagen->path = Storage::url($path);  // Guardamos la URL relativa
        $imagen->nombre = $nombre;
        $imagen->save();

        // Retornar la URL de la imagen
        return response()->json([
            [
                'url' => $imagen->path,
            ]
        ]);
    }


    public function search(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);
    
        // Obtener el nombre del parámetro de la solicitud
        $nombre = $request->input('nombre');
    
        // Ejecutar el procedimiento almacenado con el parámetro 'nombre_buscar'
        $resultados = DB::select('CALL buscar_imagenes(?)', [$nombre]);
    
        // Retornar los resultados como JSON
        return response()->json($resultados);
    }


    /*public function load(Request $request)
    {
        // Obtener todas las imágenes de la base de datos
        $imagenes = Imagen::all()->map(function ($imagen) {
            return [
                'id' => $imagen->id,
                'nombre' => $imagen->nombre,
                'url' => $imagen->path,
            ];
        });

        // Retornar todas las URLs de las imágenes
        return response()->json($imagenes);
    }*/

    public function load(Request $request)
    {
        // Obtener las imágenes paginadas de la base de datos
        $imagenes = Imagen::paginate(10); // Cambia 10 por la cantidad de elementos por página que prefieras

        // Formatear los datos de las imágenes
        $imagenesTransformadas = $imagenes->map(function ($imagen) {
            return [
                'id' => $imagen->id,
                'nombre' => $imagen->nombre,
                'url' => $imagen->path,
            ];
        });

        // Agregar las imágenes transformadas al objeto de paginación
        $imagenes->getCollection()->transform(function ($imagen) {
            return [
                'id' => $imagen->id,
                'nombre' => $imagen->nombre,
                'url' => $imagen->path,
            ];
        });

        // Retornar los datos paginados con formato JSON
        return response()->json($imagenes);
    }
}
