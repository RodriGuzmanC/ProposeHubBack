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

        // Guardar en la base de datos
        $imagen = new Imagen();
        //$imagen->user_id = auth()->id();  // Asegúrate de tener autenticación
        $imagen->path = Storage::url($path);  // Guardamos la URL relativa
        $imagen->save();

        // Retornar la URL de la imagen
        return response()->json([
            [
                'url' => $imagen->path,
            ]
        ]);
    }
}
