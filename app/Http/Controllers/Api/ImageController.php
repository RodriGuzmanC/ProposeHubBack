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


    /*public function search(Request $request)
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
    }*/

    public function search(Request $request, $nombre)
    {
        // Validar el parámetro de entrada
        /*$request->validate([
            'nombre' => 'string|max:255',
        ]);*/

        if (empty($nombre) || !is_string($nombre) || strlen($nombre) > 255) {
            return response()->json(['error' => 'El parámetro "nombre" es inválido.'], 400);
        }

        // Obtener el nombre del parámetro de la solicitud
        //$nombre = $request->input('nombre');
        //$nombre = 'a';
        // Definir el número de resultados por página
        $perPage = 10; // Puedes ajustar este valor según sea necesario
        $page = $request->input('page', 1); // Obtener la página actual, por defecto es 1
        //$page = 1;
        // Ejecutar el procedimiento almacenado con el parámetro 'nombre'
        $resultados = DB::select('CALL buscar_imagenes(?)', [$nombre]);

        // Transformar los resultados en una colección paginada
        $resultadosCollection = collect($resultados);

        // Ordenar la colección por 'created_at' en orden descendente antes de paginar
        $resultadosOrdenados = $resultadosCollection->sortByDesc('created_at')->values();

        // Dividir la colección en páginas
        $resultadosPaginados = $resultadosOrdenados->forPage($page, $perPage);

        // Crear el objeto de paginación manualmente
        $paginacion = new \Illuminate\Pagination\LengthAwarePaginator(
            $resultadosPaginados,
            $resultadosOrdenados->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Transformar los resultados para el formato deseado
        $paginacion->getCollection()->transform(function ($resultado) {
            return [
                'id' => $resultado->id ?? null,
                'nombre' => $resultado->nombre ?? null,
                'url' => env('ASSETS_PATH') . $resultado->path ?? null,
                'created_at' => $resultado->created_at ?? null
            ];
        });

        // Retornar los datos paginados como respuesta JSON
        return response()->json($paginacion);
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
        $imagenes = Imagen::orderBy('created_at', 'desc')->paginate(10); // Cambia 10 por la cantidad de elementos por página que prefieras

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
                'url' => env('ASSETS_PATH') . $imagen->path ?? null,
                'created_at' => $imagen->created_at ?? null
            ];
        });

        // Retornar los datos paginados con formato JSON
        return response()->json($imagenes);
    }
}
