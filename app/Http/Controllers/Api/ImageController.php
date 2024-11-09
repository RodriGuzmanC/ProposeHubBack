<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Imagen;

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

    public function load(Request $request)
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
    }
}
